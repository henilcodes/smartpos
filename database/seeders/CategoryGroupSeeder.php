<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\CategoryGroup;
use Illuminate\Database\Seeder;

class CategoryGroupSeeder extends Seeder
{
    public function run(): void
    {
        $groups = [
            [
                'name' => 'Daily essentials',
                'featured' => true,
                'is_visible' => true,
                'sr' => 1,
                'categories' => ['Dairy', 'Staples', 'Beverages'],
            ],
            [
                'name' => 'Home & pantry',
                'featured' => false,
                'is_visible' => true,
                'sr' => 2,
                'categories' => ['Snacks', 'Household', 'Personal Care'],
            ],
        ];

        foreach ($groups as $groupData) {
            $categoryNames = $groupData['categories'];
            unset($groupData['categories']);

            $group = CategoryGroup::query()->updateOrCreate(
                ['name' => $groupData['name']],
                $groupData,
            );

            $categoryIds = Category::query()
                ->whereIn('name', $categoryNames)
                ->pluck('id');

            $group->categories()->sync($categoryIds);
        }
    }
}
