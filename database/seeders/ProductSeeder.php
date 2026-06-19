<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\TaxGroup;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $taxGroup = TaxGroup::query()->where('hsn', 'HSN-18')->first();
        $reducedTaxGroup = TaxGroup::query()->where('hsn', 'HSN-05')->first();

        $products = [
            [
                'name' => 'Amul Taaza Toned Milk 1L',
                'sku' => 'AMUL-MILK-1L',
                'barcode' => '8901262010123',
                'brand' => 'Amul',
                'categories' => ['Dairy', 'Beverages'],
                'mrp' => 62, 'purchase_rate' => 52, 'rate_a' => 58, 'rate_b' => 60, 'rate_c' => 61,
                'qty' => 120, 'security_stock' => 20, 'unit' => 'LTR', 'unit_value' => 1,
                'featured' => true, 'tags' => 'milk,dairy,taaza',
            ],
            [
                'name' => 'Britannia Good Day Cashew Cookies 600g',
                'sku' => 'BRIT-GOODDAY-600',
                'barcode' => '8901065110178',
                'brand' => 'Britannia',
                'categories' => ['Snacks'],
                'mrp' => 120, 'purchase_rate' => 92, 'rate_a' => 105, 'rate_b' => 110, 'rate_c' => 115,
                'qty' => 80, 'security_stock' => 15, 'unit' => 'PKT', 'unit_value' => 600,
                'featured' => true, 'tags' => 'biscuit,cookies,snacks',
            ],
            [
                'name' => 'Haldiram\'s Aloo Bhujia 400g',
                'sku' => 'HALD-BHUJIA-400',
                'barcode' => '8901491100045',
                'brand' => 'Haldiram\'s',
                'categories' => ['Snacks'],
                'mrp' => 95, 'purchase_rate' => 72, 'rate_a' => 85, 'rate_b' => 88, 'rate_c' => 90,
                'qty' => 65, 'security_stock' => 10, 'unit' => 'PKT', 'unit_value' => 400,
                'featured' => false, 'tags' => 'namkeen,bhujia,snacks',
            ],
            [
                'name' => 'Harpic Disinfectant Toilet Cleaner Liquid, Original - 500ml',
                'sku' => 'HARPIC-TC-500',
                'barcode' => '8901030865432',
                'brand' => 'Harpic',
                'categories' => ['Household'],
                'mrp' => 110, 'purchase_rate' => 82, 'rate_a' => 99, 'rate_b' => 102, 'rate_c' => 105,
                'qty' => 45, 'security_stock' => 8, 'unit' => 'ML', 'unit_value' => 500,
                'featured' => false, 'tags' => 'cleaner,toilet,household',
            ],
            [
                'name' => 'Tata Salt 1kg',
                'sku' => 'TATA-SALT-1KG',
                'barcode' => '8901058000123',
                'brand' => 'Tata Salt',
                'categories' => ['Staples'],
                'mrp' => 30, 'purchase_rate' => 22, 'rate_a' => 26, 'rate_b' => 27, 'rate_c' => 28,
                'qty' => 200, 'security_stock' => 30, 'unit' => 'KG', 'unit_value' => 1,
                'featured' => false, 'tags' => 'salt,staples,iodized',
                'tax_group_id' => $reducedTaxGroup?->id,
            ],
            [
                'name' => 'Parle-G Gold Biscuits 1kg',
                'sku' => 'PARLE-G-1KG',
                'barcode' => '8901719100123',
                'brand' => 'Parle',
                'categories' => ['Snacks'],
                'mrp' => 140, 'purchase_rate' => 108, 'rate_a' => 125, 'rate_b' => 130, 'rate_c' => 135,
                'qty' => 90, 'security_stock' => 12, 'unit' => 'KG', 'unit_value' => 1,
                'featured' => true, 'tags' => 'parle,biscuit,glucose',
            ],
            [
                'name' => 'Amul Butter 500g',
                'sku' => 'AMUL-BUTTER-500',
                'barcode' => '8901262010456',
                'brand' => 'Amul',
                'categories' => ['Dairy'],
                'mrp' => 290, 'purchase_rate' => 245, 'rate_a' => 270, 'rate_b' => 278, 'rate_c' => 285,
                'qty' => 55, 'security_stock' => 10, 'unit' => 'GM', 'unit_value' => 500,
                'featured' => false, 'tags' => 'butter,dairy,amul',
            ],
            [
                'name' => 'Britannia Cheese Slices 200g',
                'sku' => 'BRIT-CHEESE-200',
                'barcode' => '8901065110456',
                'brand' => 'Britannia',
                'categories' => ['Dairy'],
                'mrp' => 145, 'purchase_rate' => 118, 'rate_a' => 132, 'rate_b' => 136, 'rate_c' => 140,
                'qty' => 40, 'security_stock' => 8, 'unit' => 'GM', 'unit_value' => 200,
                'featured' => false, 'tags' => 'cheese,dairy,slices',
            ],
        ];

        foreach ($products as $productData) {
            $brand = Brand::query()->where('name', $productData['brand'])->first();
            $categoryNames = $productData['categories'];
            unset($productData['brand'], $productData['categories']);

            $product = Product::query()->updateOrCreate(
                ['sku' => $productData['sku']],
                [
                    ...$productData,
                    'brand_id' => $brand?->id,
                    'tax_group_id' => $productData['tax_group_id'] ?? $taxGroup?->id,
                    'description' => '<p>Demo product: '.$productData['name'].'</p>',
                    'product_discounts' => [
                        ['name' => 'Retail discount', 'type' => 'percentage', 'value' => 3],
                    ],
                    'is_active' => true,
                    'requires_shipping' => true,
                    'backorder' => false,
                    'is_secondary' => false,
                    'published_at' => now()->subDays(7),
                    'expired_at' => now()->addMonths(6),
                ],
            );

            $categoryIds = Category::query()
                ->whereIn('name', $categoryNames)
                ->pluck('id');

            $product->categories()->sync($categoryIds);
        }
    }
}
