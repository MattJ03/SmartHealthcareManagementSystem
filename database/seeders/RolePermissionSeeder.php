<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = Role::create(['name' => 'admin']);
        $doctor = Role::create(['name' => 'doctor']);
        $patient = Role::create(['name' => 'patient']);

        $permission1 = Permission::create(['name' => 'view all patients']);
        $permission2 = Permission::create(['name' => 'view patients']);
        $permission3 = Permission::create(['name' => 'create patient']);
        $permission4 = Permission::create(['name' => 'update patient info']);
        $permission5 = Permission::create(['name' => 'delete patient']);
        $permission6 = Permission::create(['name' => 'move patient']);
        $permission7 = Permission::create(['name' => 'view patient info']);
        $permission8 = Permission::create(['name' => 'manage prescription']);
        $permission9 = Permission::create(['name' => 'view medical records']);
        $permission10 = Permission::create(['name' => 'create doctor']);

        $admin->givePermissionTo([
            $permission1, $permission3, $permission4, $permission5, $permission6, $permission7
        ]);
        $doctor->givePermissionTo([
           $permission2, $permission4, $permission5, $permission7, $permission8, $permission9
        ]);
        $admin->givePermissionTo([
            $permission1, $permission3, $permission4, $permission5, $permission6, $permission7, $permission10
        ]);
    }
}
