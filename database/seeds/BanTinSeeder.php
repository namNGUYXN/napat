<?php

use App\BanTin;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class BanTinSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'noi_dung' => 'Chào mừng các bạn đến với lớp học!',
                'id_ban_tin_cha' => null,
                'id_thanh_vien_lop' => 2, // Trần Hoài Ân (Giảng viên)
                'id_lop_hoc_phan' => 1,
                'ngay_tao' => Carbon::now()->subDays(3),
                'is_delete' => false,
            ],
            [
                'noi_dung' => 'Tuần này học chương 3 và 4 nhé!',
                'id_ban_tin_cha' => null,
                'id_thanh_vien_lop' => 3, // Nguyễn Tấn Phát (Giảng viên)
                'id_lop_hoc_phan' => 2,
                'ngay_tao' => Carbon::now()->subDays(2),
                'is_delete' => false,
            ],
            [
                'noi_dung' => 'Nhớ mang tài liệu thực hành.',
                'id_ban_tin_cha' => null,
                'id_thanh_vien_lop' => 2, // Trần Hoài Ân (Giảng viên)
                'id_lop_hoc_phan' => 1,
                'ngay_tao' => Carbon::now()->subDay(),
                'is_delete' => false,
            ],
            [
                'noi_dung' => 'Dạ thầy ơi có cần mang máy tính không ạ?',
                'id_ban_tin_cha' => 3, // trả lời bản tin 3
                'id_thanh_vien_lop' => 1, // Lê Gia Nghi (Sinh viên)
                'id_lop_hoc_phan' => 1,
                'ngay_tao' => Carbon::now()->subHours(20),
                'is_delete' => false,
            ],
            [
                'noi_dung' => 'Em in tài liệu rồi ạ!',
                'ngay_tao' => Carbon::now()->subHours(15),
                'id_thanh_vien_lop' => 1, // Lê Gia Nghi (Sinh viên)
                'id_lop_hoc_phan' => 1,
                'id_ban_tin_cha' => 3,
                'is_delete' => false,
            ],
        ];

        BanTin::insert($data);
    }
}
