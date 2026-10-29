<?php

namespace App\Http\Middleware;

use App\Domain\Identity\Services\AccessPortal;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

// COURSE_SINGLE_LOGIN_V1
class EnsurePortalAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        if (
            $request->routeIs('filament.admin.auth.login')
            && $request->isMethod('GET')
        ) {
            return redirect()->route('login');
        }

        $portal = app(AccessPortal::class);
        $area = $portal->area($request->route()?->getName());

        if ($area === null) {
            return $next($request);
        }

        if ($request->user() === null) {
            if ($request->expectsJson()) {
                abort(401, 'Silakan masuk untuk melanjutkan.');
            }

            if ($request->isMethod('GET')) {
                return redirect()->guest(route('login'));
            }

            return redirect()->route('login')->with(
                'status',
                'Silakan masuk, lalu ulangi tindakan Anda.'
            );
        }

        if (! $request->user()->hasVerifiedEmail()) {
            if ($request->expectsJson()) {
                abort(403, 'Verifikasi email Anda untuk melanjutkan.');
            }

            return redirect()->guest(route('verification.notice'));
        }

        abort_unless(
            $portal->allowed($request->user(), $area),
            403,
            'Role akun Anda tidak memiliki akses ke halaman ini.'
        );

        return $next($request);
    }
}
