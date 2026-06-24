<?php

namespace App\Filament\Widgets;

use App\Filament\Widgets\Concerns\InteractsWithDashboardFilters;
use Filament\Widgets\ChartWidget;

class TopProductsChartWidget extends ChartWidget
{
    use InteractsWithDashboardFilters;

    protected static ?int $sort = 9;

    protected ?string $heading = 'Top selling products (revenue)';

    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        $top = $this->statsService()->topProducts($this->dashboardFilters());

        return [
            'datasets' => [
                [
                    'label' => 'Revenue (₹)',
                    'data' => $top['data'],
                    'backgroundColor' => '#f59e0b',
                    'borderColor' => '#d97706',
                    'borderWidth' => 1,
                ],
            ],
            'labels' => $top['labels'] ?: ['No sales in range'],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
