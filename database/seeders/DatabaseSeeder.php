<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        /**
     * Seed the application's database.
     */
     $this->call([
            CategoriesTableSeeder::class,
            SubCategoriesTableSeeder::class,
            TagsTableSeeder::class,
            VariantAttributesTableSeeder::class,
            ProductsTableSeeder::class,
            ProductCategoriesTableSeeder::class,
            ProductTagsTableSeeder::class,
            ProductVariantsTableSeeder::class,
            VariantAttributeValuesTableSeeder::class,

            CurrenciesTableSeeder::class,
            LanguagesTableSeeder::class,
            SitesSettingsTableSeeder::class,

            RolesTableSeeder::class,
            AdminSeeder::class,
            AdminPermissionSeeder::class,
            TicketPrioritySeeder::class,
            // UsersTableSeeder::class,
        ]);

        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
