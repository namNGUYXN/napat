<?php

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
        $listMenu = [
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
                'gia_tri' => '1',
                'thu_tu' => 1
            ],
            [
                'ten' => 'Giáo Dục Đại Cương',
                'id_loai_menu' => 4,
                'id_menu_cha' => 2,
                'gia_tri' => '2',
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
                'gia_tri' => '3',
                'thu_tu' => 4
            ],
            [
                'ten' => 'Quản lý mục bài giảng',
                'id_loai_menu' => 6,
                'id_menu_cha' => null,
                'gia_tri' => '',
                'thu_tu' => 4
            ],
            [
                'ten' => 'Điện Tử',
                'id_loai_menu' => 4,
                'id_menu_cha' => 2,
                'gia_tri' => '4',
                'thu_tu' => 3
            ],
            [
                'ten' => 'Thiết kế website',
                'id_loai_menu' => 5,
                'id_menu_cha' => 3,
                'gia_tri' => '1',
                'thu_tu' => 1
            ],
            [
                'ten' => 'Vật lý đại cương',
                'id_loai_menu' => 5,
                'id_menu_cha' => 4,
                'gia_tri' => '3',
                'thu_tu' => 1
            ],
            [
                'ten' => 'Cơ sở dữ liệu',
                'id_loai_menu' => 5,
                'id_menu_cha' => 3,
                'gia_tri' => '2',
                'thu_tu' => 2
            ],
        ];

        foreach ($listMenu as $menu) {
            DB::table('menu')->insert([
                'ten' => $menu['ten'],
                'id_loai_menu' => $menu['id_loai_menu'],
                'id_menu_cha' => $menu['id_menu_cha'],
                'gia_tri' => $menu['gia_tri'],
                'thu_tu' => $menu['thu_tu']
            ]);
        }
    }
}
