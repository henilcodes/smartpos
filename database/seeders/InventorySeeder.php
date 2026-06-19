<?php

namespace Database\Seeders;

use App\Models\Inventory;
use App\Models\InventoryItem;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Database\Seeder;

class InventorySeeder extends Seeder
{
    public function run(): void
    {
        $freshFoods = Supplier::query()->where('phone', '9122001100')->first();
        $metroFmcg = Supplier::query()->where('phone', '9122002200')->first();

        if (! $freshFoods || ! $metroFmcg) {
            return;
        }

        $receipts = [
            [
                'code' => 'INV-DEMO-001',
                'supplier_id' => $freshFoods->id,
                'status' => 'completed',
                'date' => now()->subDays(5),
                'notes' => 'Weekly dairy and staples restock.',
                'skus' => ['AMUL-MILK-1L', 'TATA-SALT-1KG', 'AMUL-BUTTER-500'],
            ],
            [
                'code' => 'INV-DEMO-002',
                'supplier_id' => $metroFmcg->id,
                'status' => 'pending',
                'date' => now()->subDay(),
                'notes' => 'Snacks shipment awaiting verification.',
                'skus' => ['BRIT-GOODDAY-600', 'HALD-BHUJIA-400', 'PARLE-G-1KG'],
            ],
        ];

        foreach ($receipts as $index => $receiptData) {
            $skus = $receiptData['skus'];
            unset($receiptData['skus']);

            $inventory = Inventory::query()->updateOrCreate(
                ['code' => $receiptData['code']],
                $receiptData,
            );

            foreach ($skus as $sr => $sku) {
                $product = Product::query()->where('sku', $sku)->first();

                if (! $product) {
                    continue;
                }

                InventoryItem::query()->updateOrCreate(
                    [
                        'inventory_id' => $inventory->id,
                        'product_id' => $product->id,
                    ],
                    [
                        'sr' => $sr + 1,
                        'qty' => 50,
                        'mrp' => $product->mrp,
                        'purchase_rate' => $product->purchase_rate,
                        'rate_a' => $product->rate_a,
                        'rate_b' => $product->rate_b,
                        'rate_c' => $product->rate_c,
                        'expiry_date' => now()->addMonths(4),
                        'is_locked' => false,
                    ],
                );
            }
        }
    }
}
