<?php

namespace App\Http\Controllers\Auth;

use App\Domain\Identity\Enums\RoleName;
use App\Domain\Identity\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

class GoogleAuthController
{
    public function redirect(): RedirectResponse
    {
        if (! $this->isConfigured()) {
            return redirect()
                ->route('login')
                ->with('status', 'Google login belum dikonfigurasi. Isi GOOGLE_CLIENT_ID, GOOGLE_CLIENT_SECRET, dan GOOGLE_REDIRECT_URI di file .env.');
        }

        return Socialite::driver('google')
            ->scopes(['openid', 'profile', 'email'])
            ->redirect();
    }

    public function callback(Request $request): RedirectResponse
    {
        if (! $this->isConfigured()) {
            return redirect()
                ->route('login')
                ->with('status', 'Google login belum dikonfigurasi.');
        }

        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (Throwable $e) {
            report($e);

            return redirect()
                ->route('login')
                ->with('status', 'Login dengan Google gagal. Coba lagi.');
        }

        $email = (string) ($googleUser->getEmail() ?? '');

        if ($email === '') {
            return redirect()
                ->route('login')
                ->with('status', 'Akun Google tidak mengirim alamat email.');
        }

        $name = trim((string) (
            $googleUser->getName()
            ?: $googleUser->getNickname()
            ?: Str::before($email, '@')
            ?: 'Google User'
        ));

        $user = User::query()->where('email', $email)->first();

        if (! $user) {
            $user = User::query()->create([
                'name' => $name,
                'email' => $email,
                'password' => Str::random(40),
            ]);
        } elseif (blank($user->name)) {
            $user->forceFill(['name' => $name])->save();
        }

        if (! $user->email_verified_at) {
            $user->forceFill(['email_verified_at' => now()])->save();
        }

        $this->attachStudentRole($user);

        Auth::login($user, true);
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'));
    }

    private function isConfigured(): bool
    {
        return filled(config('services.google.client_id'))
            && filled(config('services.google.client_secret'))
            && filled(config('services.google.redirect'));
    }

    private function attachStudentRole(User $user): void
    {
        if (! Schema::hasTable('roles') || ! Schema::hasTable('role_user')) {
            return;
        }

        $role = Role::query()
            ->where('name', RoleName::Student->value)
            ->first();

        if (! $role) {
            return;
        }

        if (! $user->roles()->where('roles.id', $role->id)->exists()) {
            $user->roles()->attach($role->id);
        }
    }
}
