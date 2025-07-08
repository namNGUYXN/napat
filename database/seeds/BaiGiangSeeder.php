<?php

use App\BaiGiang;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BaiGiangSeeder extends Seeder
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
                'slug' => Str::slug('Lập trình Web cơ bản') . '-' . Str::random(5),
                'mo_ta_ngan' => 'Giới thiệu về HTML, CSS và JS',
                'hinh_anh' => 'images/bai-giang/no-image.png',
                'id_giang_vien' => 2,
            ],
            [
                'ten' => 'Phát triển ứng dụng Laravel',
                'slug' => Str::slug('Phát triển ứng dụng Laravel') . '-' . Str::random(5),
                'mo_ta_ngan' => 'Tài liệu và bài giảng về Laravel 10',
                'hinh_anh' => 'images/bai-giang/no-image.png',
                'id_giang_vien' => 3,
            ]
        ];

        BaiGiang::insert($data);
    }
}
