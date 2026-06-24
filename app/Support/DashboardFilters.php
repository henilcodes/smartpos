<?php

namespace App\Support;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class DashboardFilters
{
    public function __construct(
        public ?string $dateFrom = null,
        public ?string $dateTo = null,
        public ?string $supplierId = null,
        public ?string $inventoryStatus = 'pending',
        public ?string $paymentStatus = 'pending',
        public ?string $paymentMode = null,
        public ?string $stockAlert = null,
        public int $trendDays = 7,
    ) {}

    /**
     * @param  array<string, mixed>|null  $filters
     */
    public static function from(?array $filters): self
    {
        if (blank($filters)) {
            return new self(
                dateFrom: now()->startOfMonth()->toDateString(),
                dateTo: now()->toDateString(),
            );
        }

        return new self(
            dateFrom: filled($filters['date_from'] ?? null) ? (string) $filters['date_from'] : null,
            dateTo: filled($filters['date_to'] ?? null) ? (string) $filters['date_to'] : null,
            supplierId: filled($filters['supplier_id'] ?? null) ? (string) $filters['supplier_id'] : null,
            inventoryStatus: filled($filters['inventory_status'] ?? null) ? (string) $filters['inventory_status'] : 'pending',
            paymentStatus: filled($filters['payment_status'] ?? null) ? (string) $filters['payment_status'] : 'pending',
            paymentMode: filled($filters['payment_mode'] ?? null) ? (string) $filters['payment_mode'] : null,
            stockAlert: filled($filters['stock_alert'] ?? null) ? (string) $filters['stock_alert'] : null,
            trendDays: (int) ($filters['trend_days'] ?? 7),
        );
    }

    public function withInventoryStatus(string $status): self
    {
        return new self(
            dateFrom: $this->dateFrom,
            dateTo: $this->dateTo,
            supplierId: $this->supplierId,
            inventoryStatus: $status,
            paymentStatus: $this->paymentStatus,
            paymentMode: $this->paymentMode,
            stockAlert: $this->stockAlert,
            trendDays: $this->trendDays,
        );
    }

    public function ordersFrom(): ?Carbon
    {
        return $this->dateFrom ? Carbon::parse($this->dateFrom)->startOfDay() : null;
    }

    public function ordersUntil(): ?Carbon
    {
        return $this->dateTo ? Carbon::parse($this->dateTo)->endOfDay() : null;
    }

    /**
     * @param  Builder<\Illuminate\Database\Eloquent\Model>  $query
     * @return Builder<\Illuminate\Database\Eloquent\Model>
     */
    public function applyToOrders(Builder $query): Builder
    {
        return $query
            ->when($this->ordersFrom(), fn (Builder $q, Carbon $from): Builder => $q->where('ordered_at', '>=', $from))
            ->when($this->ordersUntil(), fn (Builder $q, Carbon $to): Builder => $q->where('ordered_at', '<=', $to))
            ->when(filled($this->paymentMode), fn (Builder $q): Builder => $q->where('payment_mode', $this->paymentMode));
    }

    /**
     * @param  Builder<\Illuminate\Database\Eloquent\Model>  $query
     * @return Builder<\Illuminate\Database\Eloquent\Model>
     */
    public function applyToInventories(Builder $query): Builder
    {
        return $query
            ->when($this->ordersFrom(), fn (Builder $q, Carbon $from): Builder => $q->where('date', '>=', $from))
            ->when($this->ordersUntil(), fn (Builder $q, Carbon $to): Builder => $q->where('date', '<=', $to))
            ->when(filled($this->supplierId), fn (Builder $q): Builder => $q->where('supplier_id', $this->supplierId))
            ->when(
                filled($this->inventoryStatus) && $this->inventoryStatus !== 'all',
                fn (Builder $q): Builder => $q->where('status', $this->inventoryStatus),
            );
    }

    /**
     * @param  Builder<\Illuminate\Database\Eloquent\Model>  $query
     * @return Builder<\Illuminate\Database\Eloquent\Model>
     */
    public function applyToPayments(Builder $query): Builder
    {
        return $query
            ->when($this->ordersFrom(), fn (Builder $q, Carbon $from): Builder => $q->where('payment_at', '>=', $from))
            ->when($this->ordersUntil(), fn (Builder $q, Carbon $to): Builder => $q->where('payment_at', '<=', $to))
            ->when(
                filled($this->paymentStatus) && $this->paymentStatus !== 'all',
                fn (Builder $q): Builder => $q->where('status', $this->paymentStatus),
            );
    }

    public function appliesLowStockOnly(): bool
    {
        return $this->stockAlert === 'low_stock';
    }

    public function appliesOutOfStockOnly(): bool
    {
        return $this->stockAlert === 'out_of_stock';
    }

    public function appliesExpiringOnly(): bool
    {
        return $this->stockAlert === 'expiring';
    }
}
