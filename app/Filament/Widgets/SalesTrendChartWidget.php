<?php

namespace App\Filament\Widgets;

use App\Filament\Widgets\Concerns\InteractsWithDashboardFilters;
use App\Services\DashboardStatsService;
use Filament\Widgets\ChartWidget;

class SalesTrendChartWidget extends ChartWidget
{
    use InteractsWithDashboardFilters;

    protected static ?int $sort = 5;

    protected ?string $heading = 'Sales revenue trend';

    protected int | string | array $columnSpan = 1;

    protected function getData(): array
    {
        $filters = $this->dashboardFilters();
        $trend = $this->statsService()->salesTrend($filters);
        $days = $filters->trendDays;

        return [
            'datasets' => [
                [
                    'label' => 'Revenue (₹)',
                    'data' => $trend['data'],
                    'borderColor' => '#f59e0b',
                    'backgroundColor' => 'rgba(245, 158, 11, 0.15)',
                    'fill' => true,
                    'tension' => 0.3,
                ],
            ],
            'labels' => $trend['labels'],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    public function getHeading(): ?string
    {
        $days = $this->dashboardFilters()->trendDays;

        return "Sales revenue trend (last {$days} days)";
    }
}
