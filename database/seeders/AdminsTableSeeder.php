<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('admins')->insert(array (
            0 => array (
                'id' => 1,
                'name' => 'Nguyễn Văn Cường',
                'email' => 'nguyenvancuong@honghafeed.com.vn',
                'password' => bcrypt('Hongha@123'),
                'role_id' => 1,
                'status' => 'On',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ),
            1 => array (
                'id' => 2,
                'name' => 'Phạm Thị Trang',
                'email' => 'phamthitrang@honghafeed.com.vn',
                'password' => bcrypt('Hongha@123'),
                'role_id' => 2,
                'status' => fake()->randomElement(['On', 'Off']),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ),
            2 => array (
                'id' => 3,
                'name' => 'Bùi Thị Nụ',
                'email' => 'buithinu@honghafeed.com.vn',
                'password' => bcrypt('Hongha@123'),
                'role_id' => 3,
                'status' => fake()->randomElement(['On', 'Off']),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ),
            3 => array (
                'id' => 4,
                'name' => 'Lê Thị Hồng',
                'email' => 'lethihong@honghafeed.com.vn',
                'password' => bcrypt('Hongha@123'),
                'role_id' => 4,
                'status' => fake()->randomElement(['On', 'Off']),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ),
            4 => array (
                'id' => 5,
                'name' => 'Tạ Văn Toại',
                'email' => 'tavantoai@honghafeed.com.vn',
                'password' => bcrypt('Hongha@123'),
                'role_id' => 5,
                'status' => fake()->randomElement(['On', 'Off']),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ),
        ));
    }
}
