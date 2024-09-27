<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\VariantAttributeValue;

class VariantAttributeValuesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $variantAttributeValues = [
            [
                'variant_id' => 1, 
                'attribute_id' => 1, 
                'value' => 'Red',
            ],
            [
                'variant_id' => 1, 
                'attribute_id' => 2,
                'value' => 'Large',
            ],
            [
                'variant_id' => 2, 
                'attribute_id' => 1, 
                'value' => 'Blue',
            ],
        ];

        foreach ($variantAttributeValues as $value) {
            VariantAttributeValue::create($value);
        }
    }
}
