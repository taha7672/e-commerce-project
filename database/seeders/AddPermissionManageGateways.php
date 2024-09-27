<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AddPermissionManageGateways extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'payment-gateways',
        ];
        foreach ($permissions as $permission) {
            $exists=Permission::where('name', $permission)->where('guard_name','admin')->exists();
            if(!$exists){
                Permission::create(['name' => $permission,'guard_name'=>'admin']);
            }
        }
        $role=Role::firstOrCreate(['name'=>'superadmin','guard_name'=>'admin']);
        $permissions=Permission::where('guard_name','admin')->pluck('id')->toArray();
        $role->permissions()->syncWithoutDetaching($permissions);

        // attach superadmin role to first admin
        $admin=Admin::where('is_superadmin',1)->first();
        if($admin && !$admin->hasRole('superadmin')){
            $admin->syncRoles(['superadmin']);
        }
    }
}
