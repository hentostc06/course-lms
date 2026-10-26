<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class CourseGrowthChart extends ChartWidget
{
    protected ?string $heading = 'Kursus ditambahkan';
    protected ?string $description = 'Kursus baru per hari dari seluruh instruktur';
    protected ?string $maxHeight = '270px';
    protected static ?int $sort = 20;
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

        $counts = DB::table('courses')
            ->selectRaw('DATE(created_at) AS day, COUNT(*) AS total')
            ->whereNull('deleted_at')
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
                'label' => 'Kursus baru',
                'data' => $values,
                'backgroundColor' => '#BC926A',
                'hoverBackgroundColor' => '#D1AB82',
                'borderRadius' => 5,
                'maxBarThickness' => 20,
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
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
