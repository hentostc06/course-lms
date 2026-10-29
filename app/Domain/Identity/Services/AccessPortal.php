<?php

namespace App\Domain\Identity\Services;

use App\Domain\Identity\Enums\RoleName;
use App\Domain\Instructor\Enums\InstructorStatus;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

// COURSE_SINGLE_LOGIN_V1
class AccessPortal
{
    public function navigation(?User $user): array
    {
        if ($user === null) {
            return [
                'admin' => false,
                'student' => false,
                'instructor' => false,
                'studio' => false,
            ];
        }

        $user->load(['roles', 'instructorProfile']);

        $roles = $user->roles
            ->map(fn ($role) => $role->name->value)
            ->all();

        return [
            'admin' => in_array(RoleName::Admin->value, $roles, true)
                || in_array(RoleName::SuperAdmin->value, $roles, true),
            'student' => in_array(RoleName::Student->value, $roles, true),
            'instructor' => in_array(RoleName::Instructor->value, $roles, true),
            'studio' => in_array(RoleName::Instructor->value, $roles, true)
                && $user->instructorProfile?->status === InstructorStatus::Approved,
        ];
    }

    public function home(User $user): string
    {
        if (! $user->hasVerifiedEmail()) {
            return route('verification.notice', absolute: false);
        }

        $access = $this->navigation($user);

        return match (true) {
            $access['admin'] => '/admin',
            $access['studio'] => '/instructor/courses',
            $access['instructor'] => '/instructor/onboarding',
            $access['student'] => '/my-learning',
            default => '/dashboard',
        };
    }

    public function area(?string $name): ?string
    {
        $name ??= '';

        return match (true) {
            str_starts_with($name, 'filament.admin.'),
            str_starts_with($name, 'moderation.') => 'admin',
            str_starts_with($name, 'instructor.onboarding') => 'onboarding',
            str_starts_with($name, 'instructor.') => 'instructor',
            str_starts_with($name, 'learning.') => 'student',
            default => null,
        };
    }

    public function allowed(User $user, string $area): bool
    {
        $access = $this->navigation($user);

        return match ($area) {
            'admin' => $access['admin'],
            'student' => $access['student'],
            'instructor' => $access['studio'],
            'onboarding' => true,
            default => false,
        };
    }

    public function afterLogin(Request $request, string $fallback = '/dashboard'): string
    {
        $user = $request->user();

        abort_unless($user instanceof User, 401);

        $target = $this->safeTarget(
            $request,
            $request->session()->pull('url.intended')
        );

        if (! $user->hasVerifiedEmail()) {
            if ($target !== null) {
                $request->session()->put('url.intended', $target);
            }

            return route('verification.notice', absolute: false);
        }

        return $target ?? $fallback;
    }

    private function safeTarget(Request $request, mixed $url): ?string
    {
        if (
            ! is_string($url)
            || $url === ''
            || strlen($url) > 4096
            || preg_match('/[\x00-\x20\x7f\\\\]/', $url)
        ) {
            return null;
        }

        $parts = parse_url($url);

        if (
            $parts === false
            || isset($parts['user'])
            || isset($parts['pass'])
            || isset($parts['fragment'])
        ) {
            return null;
        }

        if (isset($parts['host']) || isset($parts['scheme'])) {
            $scheme = strtolower($parts['scheme'] ?? '');
            $port = $parts['port'] ?? ($scheme === 'https' ? 443 : 80);

            if (
                ! in_array($scheme, ['http', 'https'], true)
                || strtolower($parts['host'] ?? '') !== strtolower($request->getHost())
                || $scheme !== $request->getScheme()
                || $port !== $request->getPort()
            ) {
                return null;
            }
        }

        $path = $parts['path'] ?? '/';
        $decoded = rawurldecode($path);

        if (
            ! str_starts_with($path, '/')
            || str_starts_with($path, '//')
            || str_starts_with($decoded, '//')
            || preg_match('/[\x00-\x1f\x7f\\\\]/', $decoded)
        ) {
            return null;
        }

        $target = $path.(isset($parts['query']) ? '?'.$parts['query'] : '');

        try {
            $route = Route::getRoutes()->match(
                Request::create($target, 'GET')
            );
        } catch (
            NotFoundHttpException
            |MethodNotAllowedHttpException
            |\InvalidArgumentException
        ) {
            return null;
        }

        $name = $route->getName() ?? '';

        if (in_array($name, [
            'home',
            'courses.index',
            'courses.show',
            'courses.preview',
            'courses.join',
            'dashboard',
            'profile.edit',
            'security.edit',
            'appearance.edit',
        ], true)) {
            return $target;
        }

        if (str_starts_with($name, 'filament.admin.auth.')) {
            return null;
        }

        $area = $this->area($name);

        return $area !== null
            && $this->allowed($request->user(), $area)
                ? $target
                : null;
    }
}
