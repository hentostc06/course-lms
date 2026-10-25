<?php
namespace App\Filament\Admin\Pages;

use App\Filament\Admin\AdminPage;

class FinanceReadiness extends AdminPage
{
    protected static ?string $slug = 'keuangan';
    protected static string | \UnitEnum | null $navigationGroup = 'Keuangan';
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $title = 'Keuangan & kesiapan pembayaran';
    protected static ?int $navigationSort = 6;
    protected string $view = 'filament.admin.pages.finance-readiness';
}
