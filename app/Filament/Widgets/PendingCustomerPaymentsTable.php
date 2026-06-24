<?php

namespace App\Filament\Widgets;

use App\Filament\Widgets\Concerns\InteractsWithDashboardFilters;
use App\Models\Payment;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class PendingCustomerPaymentsTable extends BaseWidget
{
    use InteractsWithDashboardFilters;

    protected static ?int $sort = 13;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        $filters = $this->dashboardFilters();

        return $table
            ->heading('Customer payments')
            ->description('Payment records filtered by dashboard payment status and date range.')
            ->query(fn (): Builder => $this->statsService()->pendingCustomerPaymentsQuery($filters))
            ->columns([
                TextColumn::make('code')
                    ->label('Payment #')
                    ->searchable()
                    ->placeholder('—'),

                TextColumn::make('payable_type')
                    ->label('Linked to')
                    ->formatStateUsing(fn (?string $state): string => $state ? class_basename($state) : '—'),

                TextColumn::make('method')
                    ->label('Method')
                    ->placeholder('—'),

                TextColumn::make('amount')
                    ->label('Amount')
                    ->money('INR')
                    ->alignEnd(),

                TextColumn::make('payment_at')
                    ->label('Payment date')
                    ->dateTime()
                    ->sortable(),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'completed' => 'success',
                        'failed' => 'danger',
                        default => 'gray',
                    }),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'completed' => 'Completed',
                        'failed' => 'Failed',
                    ]),
            ])
            ->striped()
            ->paginated([10, 25, 50])
            ->defaultPaginationPageOption(10)
            ->emptyStateHeading('No customer payments found')
            ->emptyStateDescription('Adjust dashboard filters or create payment records for orders.')
            ->poll('60s');
    }
}
