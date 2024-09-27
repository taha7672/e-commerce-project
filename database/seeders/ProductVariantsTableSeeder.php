<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ProductVariant;

class ProductVariantsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $productVariants = [
            [
                'product_id' => 1,
                'sku' => 'SKU001',
                'price' => 99.99,
                'stock' => 10,
            ],
            [
                'product_id' => 1, 
                'sku' => 'SKU002',
                'price' => 109.99,
                'stock' => 5,
            ],
            [
                'product_id' => 2, 
                'sku' => 'SKU003',
                'price' => 89.99,
                'stock' => 20,
            ],
        ];

        foreach ($productVariants as $variant) {
            ProductVariant::create($variant);
        }
    }
}
