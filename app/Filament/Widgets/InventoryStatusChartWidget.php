<?php

namespace App\Filament\Widgets;

use App\Filament\Widgets\Concerns\InteractsWithDashboardFilters;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Str;

class InventoryStatusChartWidget extends ChartWidget
{
    use InteractsWithDashboardFilters;

    protected static ?int $sort = 8;

    protected ?string $heading = 'Supplier bills by status';

    protected int | string | array $columnSpan = 1;

    protected function getData(): array
    {
        $breakdown = $this->statsService()->inventoryStatusBreakdown($this->dashboardFilters());

        return [
            'datasets' => [
                [
                    'label' => 'Bills',
                    'data' => array_values($breakdown),
                    'backgroundColor' => [
                        '#f59e0b',
                        '#10b981',
                        '#ef4444',
                    ],
                ],
            ],
            'labels' => array_map(
                fn (string $status): string => Str::headline($status),
                array_keys($breakdown),
            ) ?: ['No data'],
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
