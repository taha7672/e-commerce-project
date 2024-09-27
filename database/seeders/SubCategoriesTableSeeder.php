<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SubCategory;

class SubCategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $subCategories = [
            [
                'category_id' => 1,
                'name' => 'Mobile Phones',
                'slug' => 'mobile-phones',
                'image' => 'mobile_phones.jpg',
                'description' => 'All kinds of mobile phones',
                'is_active' => true,
                'is_deleted' => false,
            ],
            [
                'category_id' => 1,
                'name' => 'Laptops',
                'slug' => 'laptops',
                'image' => 'laptops.jpg',
                'description' => 'All kinds of laptops',
                'is_active' => true,
                'is_deleted' => false,
            ],
            [
                'category_id' => 2,
                'name' => 'Fiction',
                'slug' => 'fiction',
                'image' => 'fiction.jpg',
                'description' => 'Fiction books',
                'is_active' => true,
                'is_deleted' => false,
            ],

        ];

        foreach ($subCategories as $subCategory) {
            SubCategory::create($subCategory);
        }
    }
}
