<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Dairy', 'featured' => true, 'is_visible' => true, 'sr' => 1, 'description' => '<p>Milk, butter, cheese and curd.</p>'],
            ['name' => 'Snacks', 'featured' => true, 'is_visible' => true, 'sr' => 2, 'description' => '<p>Biscuits, chips and namkeen.</p>'],
            ['name' => 'Beverages', 'featured' => false, 'is_visible' => true, 'sr' => 3, 'description' => '<p>Juices, soft drinks and tea.</p>'],
            ['name' => 'Household', 'featured' => false, 'is_visible' => true, 'sr' => 4, 'description' => '<p>Cleaning and home care products.</p>'],
            ['name' => 'Staples', 'featured' => false, 'is_visible' => true, 'sr' => 5, 'description' => '<p>Rice, flour, pulses and salt.</p>'],
            ['name' => 'Personal Care', 'featured' => false, 'is_visible' => true, 'sr' => 6, 'description' => '<p>Soap, shampoo and grooming items.</p>'],
        ];

        foreach ($categories as $category) {
            Category::query()->updateOrCreate(
                ['slug' => Str::slug($category['name'])],
                [
                    ...$category,
                    'slug' => Str::slug($category['name']),
                ],
            );
        }
    }
}
