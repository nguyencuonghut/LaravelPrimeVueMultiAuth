<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            SuppliersTableSeeder::class,
            RolesTableSeeder::class,
            AdminsTableSeeder::class,
            MaterialsTableSeeder::class,
        ]);

        User::factory(100)->create();

        User::factory()->create([
            'name' => 'Tony Nguyen',
            'email' => 'nguyencuonghut55@gmail.com',
            'password' => bcrypt('Hongha@123'),
            'status' => 'On',
            'supplier_id' => 1,
        ]);
    }
}
