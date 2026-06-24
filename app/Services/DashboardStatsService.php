<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\Inventory;
use App\Models\InventoryItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Supplier;
use App\Support\DashboardFilters;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class DashboardStatsService
{
    public const NEAR_EXPIRY_DAYS = 30;

    public function formatCurrency(float|int|string|null $amount): string
    {
        return '₹' . number_format((float) $amount, 2);
    }

    /**
     * @return array<string, int|float|string>
     */
    public function overview(?DashboardFilters $filters = null): array
    {
        $filters ??= DashboardFilters::from(null);
        $today = now()->startOfDay();
        $monthStart = now()->startOfMonth();

        $ordersInRange = $this->ordersQuery($filters);
        $inventoriesInRange = $this->inventoriesQuery($filters);
        $paymentsInRange = $this->paymentsQuery($filters);

        return [
            'total_products' => Product::query()->where('is_active', true)->count(),
            'total_stock_qty' => (int) Product::query()->sum('qty'),
            'stock_value' => (float) Product::query()
                ->selectRaw('COALESCE(SUM(qty * purchase_rate), 0) as value')
                ->value('value'),
            'low_stock_count' => $this->lowStockProductsQuery($filters)->count(),
            'out_of_stock_count' => Product::query()->where('is_active', true)->where('qty', 0)->count(),
            'expiring_soon_count' => $this->nearExpiryProductsQuery()->count()
                + $this->nearExpiryInventoryItemsQuery()->count(),
            'expired_count' => $this->expiredProductsQuery()->count()
                + $this->expiredInventoryItemsQuery()->count(),
            'sales_today' => (float) $this->ordersQuery()->where('ordered_at', '>=', $today)->sum('grand_total'),
            'sales_month' => (float) $this->ordersQuery()->where('ordered_at', '>=', $monthStart)->sum('grand_total'),
            'sales_in_range' => (float) (clone $ordersInRange)->sum('grand_total'),
            'orders_today' => $this->ordersQuery()->where('ordered_at', '>=', $today)->count(),
            'orders_month' => $this->ordersQuery()->where('ordered_at', '>=', $monthStart)->count(),
            'orders_in_range' => (clone $ordersInRange)->count(),
            'total_orders' => Order::query()->count(),
            'active_customers' => Customer::query()->where('is_active', true)->count(),
            'active_suppliers' => Supplier::query()->where('is_active', true)->count(),
            'pending_supplier_bills' => (clone $inventoriesInRange)->count(),
            'pending_supplier_bill_amount' => (float) $this->inventoryBillAmount($filters),
            'pending_customer_payments' => (clone $paymentsInRange)->count(),
            'pending_customer_payment_amount' => (float) (clone $paymentsInRange)->sum('amount'),
        ];
    }

    public function ordersQuery(?DashboardFilters $filters = null): Builder
    {
        $filters ??= DashboardFilters::from(null);

        return $filters->applyToOrders(Order::query());
    }

    public function inventoriesQuery(?DashboardFilters $filters = null): Builder
    {
        $filters ??= DashboardFilters::from(null);

        return $filters->applyToInventories(
            Inventory::query()->with(['supplier', 'items']),
        );
    }

    public function paymentsQuery(?DashboardFilters $filters = null): Builder
    {
        $filters ??= DashboardFilters::from(null);

        return $filters->applyToPayments(
            Payment::query()->with('payable'),
        );
    }

    public function lowStockProductsQuery(?DashboardFilters $filters = null): Builder
    {
        $filters ??= DashboardFilters::from(null);

        $query = Product::query()->where('is_active', true);

        if ($filters->appliesOutOfStockOnly()) {
            return $query->where('qty', 0)->orderBy('name');
        }

        if ($filters->appliesLowStockOnly()) {
            return $query
                ->whereColumn('qty', '<=', 'security_stock')
                ->where('qty', '>', 0)
                ->orderBy('qty')
                ->orderBy('name');
        }

        return $query
            ->whereColumn('qty', '<=', 'security_stock')
            ->orderBy('qty')
            ->orderBy('name');
    }

    public function nearExpiryProductsQuery(): Builder
    {
        return Product::query()
            ->where('is_active', true)
            ->whereNotNull('expired_at')
            ->whereBetween('expired_at', [now()->startOfDay(), now()->addDays(self::NEAR_EXPIRY_DAYS)->endOfDay()])
            ->orderBy('expired_at');
    }

    public function expiredProductsQuery(): Builder
    {
        return Product::query()
            ->where('is_active', true)
            ->whereNotNull('expired_at')
            ->where('expired_at', '<', now()->startOfDay())
            ->orderBy('expired_at');
    }

    public function nearExpiryInventoryItemsQuery(): Builder
    {
        return InventoryItem::query()
            ->with(['product', 'inventory.supplier'])
            ->whereNotNull('expiry_date')
            ->whereBetween('expiry_date', [now()->startOfDay(), now()->addDays(self::NEAR_EXPIRY_DAYS)->endOfDay()])
            ->orderBy('expiry_date');
    }

    public function expiredInventoryItemsQuery(): Builder
    {
        return InventoryItem::query()
            ->with(['product', 'inventory.supplier'])
            ->whereNotNull('expiry_date')
            ->where('expiry_date', '<', now()->startOfDay())
            ->orderBy('expiry_date');
    }

    public function pendingSupplierInventoriesQuery(?DashboardFilters $filters = null): Builder
    {
        return $this->inventoriesQuery($filters)->latest('date');
    }

    public function pendingCustomerPaymentsQuery(?DashboardFilters $filters = null): Builder
    {
        return $this->paymentsQuery($filters)->latest('payment_at');
    }

    public function recentOrdersQuery(?DashboardFilters $filters = null): Builder
    {
        return $this->ordersQuery($filters)
            ->with('customer')
            ->latest('ordered_at');
    }

    /**
     * @return array{labels: list<string>, data: list<float>}
     */
    public function salesTrend(?DashboardFilters $filters = null): array
    {
        $filters ??= DashboardFilters::from(null);
        $days = max(1, $filters->trendDays);
        $start = now()->subDays($days - 1)->startOfDay();

        $totals = $this->ordersQuery($filters)
            ->where('ordered_at', '>=', $start)
            ->selectRaw('DATE(ordered_at) as order_date, COALESCE(SUM(grand_total), 0) as total')
            ->groupBy('order_date')
            ->orderBy('order_date')
            ->pluck('total', 'order_date');

        $labels = [];
        $data = [];

        for ($i = 0; $i < $days; $i++) {
            $date = $start->copy()->addDays($i);
            $key = $date->toDateString();
            $labels[] = $date->format('d M');
            $data[] = (float) ($totals[$key] ?? 0);
        }

        return compact('labels', 'data');
    }

    /**
     * @return array{labels: list<string>, data: list<int>}
     */
    public function ordersCountTrend(?DashboardFilters $filters = null): array
    {
        $filters ??= DashboardFilters::from(null);
        $days = max(1, $filters->trendDays);
        $start = now()->subDays($days - 1)->startOfDay();

        $counts = $this->ordersQuery($filters)
            ->where('ordered_at', '>=', $start)
            ->selectRaw('DATE(ordered_at) as order_date, COUNT(*) as total')
            ->groupBy('order_date')
            ->orderBy('order_date')
            ->pluck('total', 'order_date');

        $labels = [];
        $data = [];

        for ($i = 0; $i < $days; $i++) {
            $date = $start->copy()->addDays($i);
            $key = $date->toDateString();
            $labels[] = $date->format('d M');
            $data[] = (int) ($counts[$key] ?? 0);
        }

        return compact('labels', 'data');
    }

    /**
     * @return array<string, float>
     */
    public function paymentModeBreakdown(?DashboardFilters $filters = null): array
    {
        $filters ??= DashboardFilters::from(null);

        return $this->ordersQuery($filters)
            ->select('payment_mode', DB::raw('COALESCE(SUM(grand_total), 0) as total'))
            ->groupBy('payment_mode')
            ->pluck('total', 'payment_mode')
            ->map(fn ($total): float => (float) $total)
            ->all();
    }

    /**
     * @return array<string, int>
     */
    public function inventoryStatusBreakdown(?DashboardFilters $filters = null): array
    {
        $filters ??= DashboardFilters::from(null);

        $base = Inventory::query();
        $base = $filters->applyToInventories($base);

        if (filled($filters->inventoryStatus) && $filters->inventoryStatus !== 'all') {
            return [
                $filters->inventoryStatus => (clone $base)->count(),
            ];
        }

        return $base
            ->select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->map(fn ($count): int => (int) $count)
            ->all();
    }

    /**
     * @return array{labels: list<string>, data: list<float>}
     */
    public function topProducts(?DashboardFilters $filters = null, int $limit = 5): array
    {
        $filters ??= DashboardFilters::from(null);

        $from = $filters->ordersFrom();
        $until = $filters->ordersUntil();

        $rows = OrderItem::query()
            ->select('product_name', DB::raw('SUM(qty) as total_qty'), DB::raw('SUM(final_price) as revenue'))
            ->whereHas('order', function (Builder $query) use ($filters, $from, $until): void {
                if ($from) {
                    $query->where('ordered_at', '>=', $from);
                }

                if ($until) {
                    $query->where('ordered_at', '<=', $until);
                }

                if (filled($filters->paymentMode)) {
                    $query->where('payment_mode', $filters->paymentMode);
                }
            })
            ->groupBy('product_name')
            ->orderByDesc('total_qty')
            ->limit($limit)
            ->get();

        return [
            'labels' => $rows->pluck('product_name')->all(),
            'data' => $rows->pluck('revenue')->map(fn ($v): float => (float) $v)->all(),
        ];
    }

    public function inventoryBillAmount(?DashboardFilters $filters = null): float
    {
        $filters ??= DashboardFilters::from(null);

        $inventoryIds = $this->inventoriesQuery($filters)->pluck('id');

        if ($inventoryIds->isEmpty()) {
            return 0.0;
        }

        return (float) InventoryItem::query()
            ->whereIn('inventory_id', $inventoryIds)
            ->selectRaw('COALESCE(SUM(qty * purchase_rate), 0) as total')
            ->value('total');
    }

    /**
     * @return Collection<int, array{source: string, name: string, reference: string, expiry_date: Carbon, qty: int|null, party: string|null}>
     */
    public function nearExpiryRows(int $limit = 10, ?DashboardFilters $filters = null): Collection
    {
        $filters ??= DashboardFilters::from(null);

        if ($filters->appliesExpiringOnly()) {
            $limit = 20;
        }

        $productRows = $this->nearExpiryProductsQuery()
            ->limit($limit)
            ->get()
            ->map(fn (Product $product): array => [
                'source' => 'Product',
                'name' => $product->name,
                'reference' => $product->sku ?? '—',
                'expiry_date' => $product->expired_at,
                'qty' => $product->qty,
                'party' => null,
            ]);

        $itemRows = $this->nearExpiryInventoryItemsQuery()
            ->limit($limit)
            ->get()
            ->map(fn (InventoryItem $item): array => [
                'source' => 'Stock batch',
                'name' => $item->product?->name ?? '—',
                'reference' => $item->inventory?->code ?? '—',
                'expiry_date' => $item->expiry_date,
                'qty' => $item->qty,
                'party' => $item->inventory?->supplier?->name,
            ]);

        return $productRows
            ->concat($itemRows)
            ->sortBy('expiry_date')
            ->take($limit)
            ->values();
    }
}
