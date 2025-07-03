<?php

use Illuminate\Database\Seeder;
use App\LopHocPhan;
use Illuminate\Support\Str;
use Carbon\Carbon;

class LopHocPhanSeeder extends Seeder
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
                'slug' => Str::slug('Lập trình Web cơ bản') . '-' . 1,
                'mo_ta_ngan' => 'Giới thiệu về HTML, CSS và JS',
                'hinh_anh' => 'images/lop-hoc-phan/no-image.png',
                'id_giang_vien' => 2,
                'id_bai_giang' => 1,
                'id_khoa' => 1,
                'ngay_tao' => $now,
                'is_delete' => false,
            ],
            [
                'ma' => 'LARAVEL01',
                'ten' => 'Phát triển ứng dụng Laravel',
                'slug' => Str::slug('Phát triển ứng dụng Laravel') . '-' . 2,
                'mo_ta_ngan' => 'Tài liệu và bài giảng về Laravel 10',
                'hinh_anh' => 'images/lop-hoc-phan/no-image.png',
                'id_giang_vien' => 3,
                'id_bai_giang' => 2,
                'id_khoa' => 1,
                'ngay_tao' => $now,
                'is_delete' => false,
            ],
        ];

        LopHocPhan::insert($data);
    }
}
