<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('roles')->insert(array (
            0 => array (
                'id' => 1,
                'name' => 'Quản trị',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ),
            1 => array (
                'id' => 2,
                'name' => 'Nhân viên mua hàng',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ),
            2 => array (
                'id' => 3,
                'name' => 'Nhân viên kiểm soát',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ),
            3 => array (
                'id' => 4,
                'name' => 'Trưởng phòng mua hàng',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ),
            4 => array (
                'id' => 5,
                'name' => 'Giám đốc',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ),
        ));
    }
}
