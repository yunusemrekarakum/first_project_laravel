<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePersmissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create(['name' => 'Super Admin'], ['guard_name' => 'user']);
        Role::create(['name' => 'Admin'], ['guard_name' => 'user']);
        Role::create(['name' => 'User'], ['guard_name' => 'user']);
        Permission::create(['name' => 'Products']);
        Permission::create(['name' => 'Categories']);
        Permission::create(['name' => 'Admins']);
    }
}
