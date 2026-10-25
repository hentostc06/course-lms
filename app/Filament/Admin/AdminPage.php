<?php

namespace App\Filament\Admin;

use Filament\Pages\Page;

abstract class AdminPage extends Page
{
    public static function canAccess(): bool
    {
        $user = auth()->user();
        return $user !== null && $user->hasVerifiedEmail() && $user->hasRole('admin');
    }

    public function boot(): void
    {
        abort_unless(static::canAccess(), 403);
    }
}
