<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'name' => 'Product 1',
                'image' => 'product1.jpg',
                'slug' => 'product-1',
                'description' => 'Description for product 1',
                'price' => 99.99,
                'is_active' => true,
                'is_deleted' => false,
            ],
            [
                'name' => 'Product 2',
                'image' => 'product2.jpg',
                'slug' => 'product-2',
                'description' => 'Description for product 2',
                'price' => 149.99,
                'is_active' => true,
                'is_deleted' => false,
            ],
            [
                'name' => 'Product 3',
                'slug' => 'product-3',
                'image' => 'product3.jpg',
                'description' => 'Description for product 3',
                'price' => 199.99,
                'is_active' => true,
                'is_deleted' => false,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
