<?php

namespace App\Filament\Widgets;

use App\Filament\Widgets\Concerns\InteractsWithDashboardFilters;
use Filament\Widgets\ChartWidget;

class OrdersCountChartWidget extends ChartWidget
{
    use InteractsWithDashboardFilters;

    protected static ?int $sort = 6;

    protected int | string | array $columnSpan = 1;

    protected function getData(): array
    {
        $filters = $this->dashboardFilters();
        $trend = $this->statsService()->ordersCountTrend($filters);

        return [
            'datasets' => [
                [
                    'label' => 'Orders',
                    'data' => $trend['data'],
                    'backgroundColor' => '#3b82f6',
                    'borderColor' => '#2563eb',
                    'borderWidth' => 1,
                ],
            ],
            'labels' => $trend['labels'],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    public function getHeading(): ?string
    {
        $days = $this->dashboardFilters()->trendDays;

        return "Orders per day (last {$days} days)";
    }
}
