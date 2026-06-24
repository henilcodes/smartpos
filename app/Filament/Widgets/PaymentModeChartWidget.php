<?php

namespace App\Filament\Widgets;

use App\Filament\Widgets\Concerns\InteractsWithDashboardFilters;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Str;

class PaymentModeChartWidget extends ChartWidget
{
    use InteractsWithDashboardFilters;

    protected static ?int $sort = 7;

    protected ?string $heading = 'Sales by payment mode';

    protected int | string | array $columnSpan = 1;

    protected function getData(): array
    {
        $breakdown = $this->statsService()->paymentModeBreakdown($this->dashboardFilters());

        $labels = array_map(
            fn (string $mode): string => Str::upper($mode),
            array_keys($breakdown),
        );

        return [
            'datasets' => [
                [
                    'label' => 'Revenue (₹)',
                    'data' => array_values($breakdown),
                    'backgroundColor' => [
                        '#f59e0b',
                        '#3b82f6',
                        '#10b981',
                        '#8b5cf6',
                        '#ef4444',
                    ],
                ],
            ],
            'labels' => $labels ?: ['No data'],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
