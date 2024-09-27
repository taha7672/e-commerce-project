<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductTagsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $productTags = [
            [
                'product_id' => 1,
                'tags_id' => 1,
            ],
            [
                'product_id' => 1,
                'tags_id' => 2,
            ],
            [
                'product_id' => 2,
                'tags_id' => 3,
            ],
            [
                'product_id' => 2,
                'tags_id' => 4,
            ],
            [
                'product_id' => 3,
                'tags_id' => 1,
            ],
        ];

        DB::table('product_tags')->insert($productTags);
    }
}
