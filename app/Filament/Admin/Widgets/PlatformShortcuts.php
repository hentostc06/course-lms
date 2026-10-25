<?php
namespace App\Filament\Admin\Widgets;

use Filament\Widgets\Widget;

class PlatformShortcuts extends Widget
{
    protected string $view = 'filament.admin.widgets.platform-shortcuts';
    protected int | string | array $columnSpan = 'full';
    protected static bool $isLazy = false;
}
