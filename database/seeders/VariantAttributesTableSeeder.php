<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\VariantAttribute;

class VariantAttributesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $variantAttributes = [
            [
                'attribute_name' => 'Color',
                'subcategory_id' => 1, 
            ],
            [
                'attribute_name' => 'Size',
                'subcategory_id' => 1, 
            ],
            [
                'attribute_name' => 'Material',
                'subcategory_id' => 2, 
            ],
        ];

        foreach ($variantAttributes as $attribute) {
            VariantAttribute::create($attribute);
        }
    }
}
