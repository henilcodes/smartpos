<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        $brands = [
            ['name' => 'Amul', 'website' => 'https://amul.com', 'featured' => true, 'is_visible' => true, 'sr' => 1],
            ['name' => 'Britannia', 'website' => 'https://britannia.co.in', 'featured' => true, 'is_visible' => true, 'sr' => 2],
            ['name' => 'Haldiram\'s', 'website' => 'https://haldirams.com', 'featured' => false, 'is_visible' => true, 'sr' => 3],
            ['name' => 'Harpic', 'website' => 'https://www.harpic.co.in', 'featured' => false, 'is_visible' => true, 'sr' => 4],
            ['name' => 'Tata Salt', 'website' => 'https://www.tatasalt.com', 'featured' => false, 'is_visible' => true, 'sr' => 5],
            ['name' => 'Parle', 'website' => 'https://www.parleproducts.com', 'featured' => true, 'is_visible' => true, 'sr' => 6],
        ];

        foreach ($brands as $index => $brand) {
            Brand::query()->updateOrCreate(
                ['slug' => Str::slug($brand['name'])],
                [
                    ...$brand,
                    'slug' => Str::slug($brand['name']),
                    'description' => "Demo brand record for {$brand['name']}.",
                ],
            );
        }
    }
}
