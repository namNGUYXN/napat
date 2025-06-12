<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class BanTinSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $listBanTin = [
            [
                'noi_dung' => 'Chào mừng các bạn đến với lớp học!',
                'ngay_dang' => Carbon::now()->subDays(3),
                'id_nguoi_dung' => 2, // Trần Hoài Ân (Giảng viên)
                'id_lop_hoc' => 1,
                'id_ban_tin_cha' => null,
                'is_delete' => false,
            ],
            [
                'noi_dung' => 'Tuần này học chương 3 và 4 nhé!',
                'ngay_dang' => Carbon::now()->subDays(2),
                'id_nguoi_dung' => 3, // Nguyễn Tấn Phát (Giảng viên)
                'id_lop_hoc' => 2,
                'id_ban_tin_cha' => null,
                'is_delete' => false,
            ],
            [
                'noi_dung' => 'Nhớ mang tài liệu thực hành.',
                'ngay_dang' => Carbon::now()->subDay(),
                'id_nguoi_dung' => 2,
                'id_lop_hoc' => 1,
                'id_ban_tin_cha' => null,
                'is_delete' => false,
            ],
            [
                'noi_dung' => 'Dạ thầy ơi có cần mang máy tính không ạ?',
                'ngay_dang' => Carbon::now()->subHours(20),
                'id_nguoi_dung' => 4, // Lê Gia Nghi (Sinh viên)
                'id_lop_hoc' => 1,
                'id_ban_tin_cha' => 3, // trả lời bản tin 3
                'is_delete' => false,
            ],
            [
                'noi_dung' => 'Em in tài liệu rồi ạ!',
                'ngay_dang' => Carbon::now()->subHours(15),
                'id_nguoi_dung' => 5, // Võ Thị Ánh (Sinh viên)
                'id_lop_hoc' => 1,
                'id_ban_tin_cha' => 3,
                'is_delete' => false,
            ],
        ];

        foreach ($listBanTin as $banTin) {
            DB::table('ban_tin')->insert($banTin);
        }
    }
}
