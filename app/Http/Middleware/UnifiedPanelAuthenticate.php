<?php

namespace App\Http\Middleware;

// COURSE_SINGLE_LOGIN_V1
class UnifiedPanelAuthenticate extends \Filament\Http\Middleware\Authenticate
{
    protected function redirectTo($request): ?string
    {
        return route('login');
    }
}
