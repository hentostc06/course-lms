<?php
namespace App\Filament\Admin\Widgets;

use Filament\Widgets\Widget;

class PlatformShortcuts extends Widget
{
    public static function canView(): bool
    {
        return false;
    }

    protected static bool $isDiscovered = false;

    protected string $view = 'filament.admin.widgets.platform-shortcuts';
    protected int | string | array $columnSpan = 'full';
    protected static bool $isLazy = false;
}
