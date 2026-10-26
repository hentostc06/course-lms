<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class CourseStatusChart extends ChartWidget
{
    protected ?string $heading = 'Status publikasi kursus';
    protected ?string $description = 'Distribusi status seluruh kursus aktif';
    protected ?string $maxHeight = '290px';
    protected static ?int $sort = 30;
    protected static bool $isLazy = false;
    protected ?string $pollingInterval = null;

    protected function getData(): array
    {
        $statuses = [
            'published' => 'Tayang',
            'pending_review' => 'Menunggu review',
            'draft' => 'Draft',
            'rejected' => 'Ditolak',
            'archived' => 'Diarsipkan',
        ];

        $counts = DB::table('courses')
            ->whereNull('deleted_at')
            ->select('status')
            ->selectRaw('COUNT(*) AS total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return [
            'datasets' => [[
                'label' => 'Kursus',
                'data' => array_map(
                    fn (string $status): int =>
                        (int) ($counts[$status] ?? 0),
                    array_keys($statuses)
                ),
                'backgroundColor' => [
                    '#D97757',
                    '#D5A96A',
                    '#8B8C84',
                    '#C48179',
                    '#6D6259',
                ],
                'borderWidth' => 0,
                'hoverOffset' => 6,
            ]],
            'labels' => array_values($statuses),
        ];
    }

    protected function getOptions(): array
    {
        return [
            'cutout' => '67%',
            'plugins' => [
                'legend' => [
                    'position' => 'bottom',
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
