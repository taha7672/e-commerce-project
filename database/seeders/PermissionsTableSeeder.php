<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PermissionsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('permissions')->insert([
            [ 
                'name' => 'orders',
                'guard_name' => 'admin' 
            ],
            [ 
                'name' => 'payment_methods',
                'guard_name' => 'admin' 
            ]
        ]);
    }
}
