<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LoaiMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $listLoaiMenu = [
            [
                'ten' => 'Trang chủ',
                'slug' => Str::slug('Trang chủ'),
                'thu_tu' => 1
            ],
            [
                'ten' => 'Lớp học của tôi',
                'slug' => Str::slug('Lớp học của tôi'),
                'thu_tu' => 2
            ],
            [
                'ten' => 'Khoa',
                'slug' => Str::slug('Khoa'),
                'thu_tu' => 3
            ],
            [
                'ten' => 'Chỉ mục khoa',
                'slug' => Str::slug('Chỉ mục khoa'),
                'thu_tu' => 4
            ],
            [
                'ten' => 'Chỉ mục học phần',
                'slug' => Str::slug('Chỉ mục học phần'),
                'thu_tu' => 5
            ],
            [
                'ten' => 'Quản lý mục bài giảng',
                'slug' => Str::slug('Quản lý mục bài giảng'),
                'thu_tu' => 6
            ]
        ];

        foreach ($listLoaiMenu as $menu) {
            DB::table('loai_menu')->insert([
                'ten' => $menu['ten'],
                'slug' => $menu['slug'],
                'thu_tu' => $menu['thu_tu']
            ]);
        }
    }
}
