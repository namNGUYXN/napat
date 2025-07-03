<?php

use App\Menu;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuSeeder extends Seeder
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
                'ten' => 'Trang chủ',
                'id_loai_menu' => 1,
                'id_menu_cha' => null,
                'gia_tri' => '',
                'thu_tu' => 1
            ],
            [
                'ten' => 'Khoa',
                'id_loai_menu' => 3,
                'id_menu_cha' => null,
                'gia_tri' => '',
                'thu_tu' => 3
            ],
            [
                'ten' => 'Công Nghệ Thông Tin',
                'id_loai_menu' => 4,
                'id_menu_cha' => 2,
                'gia_tri' => 'cong-nghe-thong-tin/lop-hoc-phan',
                'thu_tu' => 1
            ],
            [
                'ten' => 'Giáo Dục Đại Cương',
                'id_loai_menu' => 4,
                'id_menu_cha' => 2,
                'gia_tri' => 'giao-duc-dai-cuong/lop-hoc-phan',
                'thu_tu' => 2
            ],
            [
                'ten' => 'Lớp học của tôi',
                'id_loai_menu' => 2,
                'id_menu_cha' => null,
                'gia_tri' => '',
                'thu_tu' => 2
            ],
            [
                'ten' => 'Công Nghệ Ô Tô',
                'id_loai_menu' => 4,
                'id_menu_cha' => 2,
                'gia_tri' => 'cong-nghe-o-to/lop-hoc-phan',
                'thu_tu' => 4
            ],
            [
                'ten' => 'Quản lý bài giảng',
                'id_loai_menu' => 5,
                'id_menu_cha' => null,
                'gia_tri' => '',
                'thu_tu' => 4
            ],
            [
                'ten' => 'Điện Tử',
                'id_loai_menu' => 4,
                'id_menu_cha' => 2,
                'gia_tri' => 'dien-tu/lop-hoc-phan',
                'thu_tu' => 3
            ]
        ];

        Menu::insert($data);
    }
}
