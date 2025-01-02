<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QualitiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('qualities')->delete();

        DB::table('qualities')->insert(array (
            0 =>
                array (
                    'id' => 1,
                    'material_id' => 1,
                    'detail' => '- Màu vàng đặc trưng, mùi vị đặc trưng không có mùi hôi, mốc, mùi lạ khác, không có mọt và VSV gây bệnh.
                    - Độ ẩm ≤ 14.5%, Tạp chất ≤ 2,0%.
                    - Aflatoxin ≤ 50 ppb.',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            1 =>
                array (
                    'id' => 2,
                    'material_id' => 2,
                    'detail' => '- Độ ẩm ≤ 0.5%, Béo ≥ 98%.
                    - AV ≤ 3 %.
                    - Mùi đặc trưng, không có mùi khét, mùi ôi, mùi hóa chất.
                    - Màu trắng trắng đục.- Độ ẩm ≤ 12%, Độ đạm ≥ 26%.
                    - Xơ ≤ 10%, Profat > 34%.
                    - Béo > 6%.
                    - Aflatoxin ≤ 20 ppb, Vomitoxin ≤ 5 ppm.
                    - Không có mọt sống, ko lẫn kim loại vật lạ.
                    - Màu vàng sậm, vàng nhạt, không cháy, mùi đặc trưng.',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
            ),
            2 =>
                array (
                    'id' => 3,
                    'material_id' => 3,
                    'detail' => '- Độ ẩm ≤ 0.5%, Béo ≥ 98%.
                    - AV ≤ 3 %.
                    - Mùi đặc trưng, không có mùi khét, mùi ôi, mùi hóa chất.
                    - Màu trắng trắng đục.',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            3 =>
                array (
                    'id' => 4,
                    'material_id' => 4,
                    'detail' => 'Theo công thức Hồng Hà',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            4 =>
                array (
                    'id' => 5,
                    'material_id' => 5,
                    'detail' => 'Theo công thức Hồng Hà',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            5 =>
                array (
                    'id' => 6,
                    'material_id' => 6,
                    'detail' => 'Theo công thức Hồng Hà',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            6 =>
                array (
                    'id' => 7,
                    'material_id' => 7,
                    'detail' => 'Theo công thức Hồng Hà',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            7 =>
                array (
                    'id' => 8,
                    'material_id' => 8,
                    'detail' => 'Theo công thức Hồng Hà',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            8 =>
                array (
                    'id' => 9,
                    'material_id' => 9,
                    'detail' => 'Theo công thức Hồng Hà',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            9 =>
                array (
                    'id' => 10,
                    'material_id' => 10,
                    'detail' => 'Theo công thức Hồng Hà',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            10 =>
                array (
                    'id' => 11,
                    'material_id' => 11,
                    'detail' => 'Theo công thức Hồng Hà',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            11 =>
                array (
                    'id' => 12,
                    'material_id' => 12,
                    'detail' => 'Theo công thức Hồng Hà',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            12 =>
                array (
                    'id' => 13,
                    'material_id' => 13,
                    'detail' => 'Chiều rộng: 60±1. Chiều dài: 105±1. Trọng lượng: 130gr±2. In cuộn: in theo mẫu. May đáy: may 2 đường chỉ. Lồng túi PE: 30gr. Đóng kiện: 500c/kiện, 100c/bó.',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            13 =>
                array (
                    'id' => 14,
                    'material_id' => 14,
                    'detail' => 'Chiều rộng: 52±1. Chiều dài: 88±1. Trọng lượng: 101gr±2. In cuộn: in theo mẫu. May đáy: may 2 đường chỉ. Lồng túi PE: 21gr. Đóng kiện: 500c/kiện, 100c/bó.',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            14 =>
                array (
                    'id' => 15,
                    'material_id' => 15,
                    'detail' => 'Chiều rộng: 60±1. Chiều dài: 100±1. Trọng lượng: 100gr±2. In cuộn: in theo mẫu. May đáy: may 2 đường chỉ. Lồng túi PE: 30gr. Đóng kiện: 500c/kiện, 100c/bó.',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
        ));
    }
}
