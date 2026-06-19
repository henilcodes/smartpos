<?php

namespace Database\Seeders;

use App\Models\Tax;
use Illuminate\Database\Seeder;

class TaxSeeder extends Seeder
{
    public function run(): void
    {
        $taxes = [
            ['name' => 'CGST 9%', 'type' => 'percentage', 'rate' => 9.00],
            ['name' => 'SGST 9%', 'type' => 'percentage', 'rate' => 9.00],
            ['name' => 'IGST 18%', 'type' => 'percentage', 'rate' => 18.00],
            ['name' => 'GST 5%', 'type' => 'percentage', 'rate' => 5.00],
            ['name' => 'Flat cess', 'type' => 'fixed', 'rate' => 12.00],
        ];

        foreach ($taxes as $tax) {
            Tax::query()->updateOrCreate(
                ['name' => $tax['name']],
                $tax,
            );
        }
    }
}
