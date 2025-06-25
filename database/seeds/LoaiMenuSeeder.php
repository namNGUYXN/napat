<?php

use App\LoaiMenu;
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
        $data = [
            [
                'ten' => 'Trang chủ',
                'slug' => Str::slug('Trang chủ'),
                'icon' => 'fas fa-house-user',
                'thu_tu' => 1
            ],
            [
                'ten' => 'Lớp học của tôi',
                'slug' => Str::slug('Lớp học của tôi'),
                'icon' => 'fas fa-users',
                'thu_tu' => 2
            ],
            [
                'ten' => 'Danh mục khoa',
                'slug' => Str::slug('Danh mục khoa'),
                'icon' => 'fas fa-layer-group',
                'thu_tu' => 3
            ],
            [
                'ten' => 'Khoa',
                'slug' => Str::slug('Khoa'),
                'icon' => '',
                'thu_tu' => 4
            ],
            [
                'ten' => 'Học phần',
                'slug' => Str::slug('Học phần'),
                'icon' => '',
                'thu_tu' => 5
            ],
            [
                'ten' => 'Bài giảng',
                'slug' => Str::slug('Bài giảng'),
                'icon' => 'fas fa-folder-open',
                'thu_tu' => 6
            ]
        ];

        LoaiMenu::insert($data);
    }
}
