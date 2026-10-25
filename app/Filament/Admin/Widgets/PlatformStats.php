<?php
namespace App\Filament\Admin\Widgets;

use App\Domain\Catalog\Models\Category;
use App\Domain\Catalog\Models\Course;
use App\Domain\Instructor\Models\InstructorProfile;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PlatformStats extends StatsOverviewWidget
{
    protected ?string $pollingInterval = null;
    protected static bool $isLazy = false;

    protected function getStats(): array
    {
        return [
            Stat::make('Pengguna terdaftar', User::query()->count())
                ->description('Seluruh akun dalam aplikasi'),
            Stat::make('Kursus', Course::query()->count())
                ->description('Total kursus dari semua instruktur'),
            Stat::make('Menunggu review', Course::query()->where('status', 'pending_review')->count())
                ->description('Memerlukan keputusan admin'),
            Stat::make('Pengajuan instruktur', InstructorProfile::query()->where('status', 'pending')->count())
                ->description('Belum diverifikasi'),
            Stat::make('Kategori aktif', Category::query()->where('is_active', true)->count())
                ->description('Tersedia dalam katalog'),
        ];
    }
}
