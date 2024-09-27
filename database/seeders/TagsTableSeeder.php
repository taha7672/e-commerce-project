<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TagsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tags = [
            [
                'name' => 'Technology',
                'image' => 'tech.jpg',
                'description' => 'All things related to technology',
                'is_active' => true,
            ],
            [
                'name' => 'Lifestyle',
                'image' => 'lifestyle.jpg',
                'description' => 'Tips and advice on lifestyle',
                'is_active' => true,
            ],
            [
                'name' => 'Education',
                'image' => 'education.jpg',
                'description' => 'Educational resources and information',
                'is_active' => true,
            ],
            [
                'name' => 'Health',
                'image' => 'health.jpg',
                'description' => 'Health and wellness tips',
                'is_active' => true,
            ],
        ];

        // Insert data into tags table
        DB::table('tags')->insert($tags);
    }
}
