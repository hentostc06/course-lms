<?php

namespace App\Http\Responses;

use App\Domain\Identity\Services\AccessPortal;
use Laravel\Fortify\Contracts\LoginResponse;
use Laravel\Fortify\Contracts\TwoFactorLoginResponse;
use Laravel\Passkeys\Contracts\PasskeyLoginResponse;

// COURSE_SINGLE_LOGIN_V1
class PortalLoginResponse implements LoginResponse, TwoFactorLoginResponse, PasskeyLoginResponse
{
    public function toResponse($request)
    {
        $target = app(AccessPortal::class)->afterLogin($request);

        if ($request->wantsJson()) {
            if ($request->routeIs('two-factor.login.store')) {
                return response()
                    ->noContent()
                    ->header('X-Login-Redirect', $target);
            }

            return response()->json([
                'two_factor' => false,
                'redirect' => $target,
            ]);
        }

        return redirect($target);
    }
}
