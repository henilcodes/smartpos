<?php

namespace Database\Seeders;

use App\Models\Tax;
use App\Models\TaxGroup;
use Illuminate\Database\Seeder;

class TaxGroupSeeder extends Seeder
{
    public function run(): void
    {
        $groups = [
            [
                'name' => 'Standard GST 18%',
                'hsn' => 'HSN-18',
                'notes' => 'Default rate for most packaged goods.',
                'taxes' => ['CGST 9%', 'SGST 9%'],
            ],
            [
                'name' => 'Interstate GST 18%',
                'hsn' => 'HSN-IGST-18',
                'notes' => 'Used for interstate supply.',
                'taxes' => ['IGST 18%'],
            ],
            [
                'name' => 'Reduced GST 5%',
                'hsn' => 'HSN-05',
                'notes' => 'Essential and reduced-rate items.',
                'taxes' => ['GST 5%'],
            ],
        ];

        foreach ($groups as $groupData) {
            $taxNames = $groupData['taxes'];
            unset($groupData['taxes']);

            $group = TaxGroup::query()->updateOrCreate(
                ['hsn' => $groupData['hsn']],
                $groupData,
            );

            $taxIds = Tax::query()
                ->whereIn('name', $taxNames)
                ->pluck('id');

            $group->taxes()->sync($taxIds);
        }
    }
}
