<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Hash;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        //User::factory(10)->create();
        //Category::factory(50)->create();
        //Product::factory(1000)->create();
        //$adminRole = Role::firstOrCreate(['name' => 'admin']);
        //$userRole = Role::firstOrCreate(['name' => 'user']);
        //$user = User::factory()->create([
        //    'name' => 'Yunus Emre Karakum',
        //    'email' => 'yunusemrekarakum@gmail.com',
        //    'password' => Hash::make("123123"),
        //]);
        //$user->assignRole($adminRole);

        //User::factory()->create([
        //    'name' => 'Test User',
        //    'email' => 'test@example.com',
        //]);
    }
}
