<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CurrenciesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $currencies = [
            ['currency_code' => 'USD', 'currency_name' => 'United States Dollar', 'exchange_rate_to_usd' => 1],
            ['currency_code' => 'TRY', 'currency_name' => 'Turkish Lira', 'exchange_rate_to_usd' => 33.70],
        ];

        DB::table('currencies')->insert($currencies);
    }
}
