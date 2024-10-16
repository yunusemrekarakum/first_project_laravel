<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Hash;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Database\Seeders\RolePersmissionSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolePersmissionSeeder::class,
        ]);
        //User::factory(10)->create();
        Category::factory(50)->create();
        Product::factory(1000)->create();
        //$superadminrole = Role::firstOrCreate(['name' => 'Super Admin']);
        //$adminRole = Role::firstOrCreate(['name' => 'Admin']);
        //$userRole = Role::firstOrCreate(['name' => 'User']);
        //$user = User::factory()->create([
        //    'name' => 'Yunus Emre Karakum',
        //    'email' => 'yunusemrekarakum@gmail.com',
        //    'password' => Hash::make("123123"),
        //]);
        //$user->assignRole($superadminrole);
    }
}
