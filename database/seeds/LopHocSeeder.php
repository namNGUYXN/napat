<?php

use Illuminate\Database\Seeder;
use App\LopHoc;
use Illuminate\Support\Str;
use Carbon\Carbon;

class LopHocSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now();

        $data = [
            [
                'ma' => 'WEB01',
                'ten' => 'Lập trình Web cơ bản',
                'slug' => Str::slug('Lập trình Web cơ bản'),
                'mo_ta_ngan' => 'Giới thiệu về HTML, CSS và JS',
                'hinh_anh' => 'images/web-co-ban.jpg',
                'id_hoc_phan' => 1,
                'id_giang_vien' => 2,
                'ngay_tao' => $now,
                'is_delete' => false,
            ],
            [
                'ma' => 'LARAVEL01',
                'ten' => 'Phát triển ứng dụng Laravel',
                'slug' => Str::slug('Phát triển ứng dụng Laravel'),
                'mo_ta_ngan' => 'Tài liệu và bài giảng về Laravel 10',
                'hinh_anh' => 'images/laravel.jpg',
                'id_hoc_phan' => 1,
                'id_giang_vien' => 2,
                'ngay_tao' => $now,
                'is_delete' => false,
            ],
        ];

        foreach ($data as $item) {
            LopHoc::create($item);
        }
    }
}
