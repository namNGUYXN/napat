<?php

use App\MucBaiGiang;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MucBaiGiangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $data = [
            [
                'ten' => 'Lập trình Web cơ bản',
                'slug' => Str::slug('Lập trình Web cơ bản'),
                'mo_ta_ngan' => 'Giới thiệu về HTML, CSS và JS',
                'hinh_anh' => 'images/web-co-ban.jpg',
                'id_giang_vien' => 2, 
                'is_delete' => false,
            ],
            [
                'ten' => 'Phát triển ứng dụng Laravel',
                'slug' => Str::slug('Phát triển ứng dụng Laravel'),
                'mo_ta_ngan' => 'Tài liệu và bài giảng về Laravel 10',
                'hinh_anh' => 'images/laravel.jpg',
                'id_giang_vien' => 2,
                'is_delete' => false,
            ]
        ];

        foreach ($data as $item) {
            MucBaiGiang::create($item);
        }
    }
}
