<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class UserGrowthChart extends ChartWidget
{
    protected ?string $heading = 'Pertumbuhan pengguna';
    protected ?string $description = 'Pendaftaran akun baru berdasarkan tanggal';
    protected ?string $maxHeight = '270px';
    protected static ?int $sort = 10;
    protected static bool $isLazy = false;
    protected ?string $pollingInterval = null;

    protected function getFilters(): ?array
    {
        return [
            '7' => '7 hari',
            '30' => '30 hari',
            '90' => '90 hari',
        ];
    }

    protected function getData(): array
    {
        $days = in_array($this->filter, ['7', '30', '90'], true)
            ? (int) $this->filter
            : 30;

        $start = now()->startOfDay()->subDays($days - 1);

        $counts = DB::table('users')
            ->selectRaw('DATE(created_at) AS day, COUNT(*) AS total')
            ->where('created_at', '>=', $start)
            ->groupByRaw('DATE(created_at)')
            ->pluck('total', 'day');

        $labels = [];
        $values = [];

        for ($i = 0; $i < $days; $i++) {
            $date = $start->copy()->addDays($i);
            $labels[] = $date->format('d M');
            $values[] = (int) (
                $counts[$date->toDateString()] ?? 0
            );
        }

        return [
            'datasets' => [[
                'label' => 'Pengguna baru',
                'data' => $values,
                'borderColor' => '#D97757',
                'backgroundColor' => 'rgba(217,119,87,0.12)',
                'borderWidth' => 2,
                'pointRadius' => 2,
                'pointHoverRadius' => 5,
                'tension' => 0.32,
                'fill' => true,
            ]],
            'labels' => $labels,
        ];
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => ['display' => false],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => ['precision' => 0],
                ],
                'x' => [
                    'grid' => ['display' => false],
                ],
            ],
            'interaction' => [
                'intersect' => false,
                'mode' => 'index',
            ],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
