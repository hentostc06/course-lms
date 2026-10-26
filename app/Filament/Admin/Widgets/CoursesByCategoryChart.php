<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class CoursesByCategoryChart extends ChartWidget
{
    protected ?string $heading = 'Kursus per kategori';
    protected ?string $description = 'Distribusi konten pada setiap kategori';
    protected ?string $maxHeight = '290px';
    protected static ?int $sort = 40;
    protected static bool $isLazy = false;
    protected ?string $pollingInterval = null;

    protected function getData(): array
    {
        $rows = DB::table('categories')
            ->leftJoin('courses', function ($join): void {
                $join->on(
                    'courses.category_id',
                    '=',
                    'categories.id'
                )->whereNull('courses.deleted_at');
            })
            ->select('categories.name')
            ->selectRaw(
                'COUNT(courses.id) AS course_total'
            )
            ->groupBy(
                'categories.id',
                'categories.name'
            )
            ->orderByDesc('course_total')
            ->limit(8)
            ->get();

        return [
            'datasets' => [[
                'label' => 'Jumlah kursus',
                'data' => $rows
                    ->pluck('course_total')
                    ->map(fn ($n): int => (int) $n)
                    ->all(),
                'backgroundColor' => '#BA8D6B',
                'hoverBackgroundColor' => '#D7A884',
                'borderRadius' => 5,
                'maxBarThickness' => 26,
            ]],
            'labels' => $rows
                ->pluck('name')
                ->all(),
        ];
    }

    protected function getOptions(): array
    {
        return [
            'indexAxis' => 'y',
            'plugins' => [
                'legend' => ['display' => false],
            ],
            'scales' => [
                'x' => [
                    'beginAtZero' => true,
                    'ticks' => ['precision' => 0],
                ],
                'y' => [
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
