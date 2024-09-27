<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SitesSettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        DB::table('sites_settings')->insert([
            'brand_name' => 'My Site',
            'description' => 'Welcome to My Site! We offer the best products and services for you.',
            'email' => 'info@mysite.com',
            'phone_number' => '+1234567890',
            'selected_language_id' => 1, 
            'selected_currencies_id' => 1, 
            'social_media_details' => 'Follow us on Twitter, Facebook, and Instagram!',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
