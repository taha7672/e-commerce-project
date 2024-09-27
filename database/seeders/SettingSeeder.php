<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings=[
            ['key'=>'site_status','value'=>1,'value_type'=>'number']
        ];
        foreach($settings as $setting){
            Setting::firstOrCreate([
                'key'=>$setting['key']
            ],[
                'value'=>$setting['value'],
                'value_type'=>$setting['value_type']
            ]);
        }
    }
}
