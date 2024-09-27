<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LanguagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $languages = [
            ['language_code' => 'en', 'language_name' => 'English'],
            ['language_code' => 'tr', 'language_name' => 'Turkish'],
        ];

        DB::table('languages')->insert($languages);
    }
}
