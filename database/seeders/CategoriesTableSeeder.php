<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $categories = [
            [
                'name' => 'Electronics',
                'slug' => 'electronics',
                'image' => 'electronics.jpg',
                'description' => 'All kinds of electronics',
                'is_active' => true,
                'is_deleted' => false,
            ],
            [
                'name' => 'Books',
                'slug' => 'books',
                'image' => 'books.jpg',
                'description' => 'Wide range of books',
                'is_active' => true,
                'is_deleted' => false,
            ],
            [
                'name' => 'Clothing',
                'slug' => 'clothing',
                'image' => 'clothing.jpg',
                'description' => 'Latest fashion clothing',
                'is_active' => true,
                'is_deleted' => false,
            ],

        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
