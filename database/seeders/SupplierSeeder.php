<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        $suppliers = [
            [
                'name' => 'Fresh Foods Distributors',
                'email' => 'orders@freshfoods.example.com',
                'phone' => '9122001100',
                'address' => '45 Wholesale Market, Pune, Maharashtra',
                'is_active' => true,
                'notes' => 'Primary dairy and staples supplier.',
            ],
            [
                'name' => 'Metro FMCG Supplies',
                'email' => 'purchase@metrofmcg.example.com',
                'phone' => '9122002200',
                'address' => '88 Industrial Estate, Ahmedabad, Gujarat',
                'is_active' => true,
                'notes' => 'Snacks and packaged goods.',
            ],
            [
                'name' => 'CleanHome Wholesale',
                'email' => 'sales@cleanhome.example.com',
                'phone' => '9122003300',
                'address' => '19 Ring Road, Delhi',
                'is_active' => true,
                'notes' => 'Household and personal care items.',
            ],
        ];

        foreach ($suppliers as $supplier) {
            Supplier::query()->updateOrCreate(
                ['phone' => $supplier['phone']],
                $supplier,
            );
        }
    }
}
