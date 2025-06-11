<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class HocPhanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $listKhoa = [
            [
                'ma' => Str::random(20),
                'ten' => 'Thiết kế website',
                'slug' => Str::slug('Thiết kế website'),
                'mo_ta_ngan' => null,
                'so_tin_chi' => 3,
                'id_khoa' => 1
            ],
            [
                'ma' => Str::random(20),
                'ten' => 'Cơ sở dữ liệu',
                'slug' => Str::slug('Cơ sở dữ liệu'),
                'mo_ta_ngan' => null,
                'so_tin_chi' => 3,
                'id_khoa' => 1
            ],
            [
                'ma' => Str::random(20),
                'ten' => 'Vật lý đại cương',
                'slug' => Str::slug('Vật lý đại cương'),
                'mo_ta_ngan' => null,
                'so_tin_chi' => 3,
                'id_khoa' => 2
            ],
            [
                'ma' => Str::random(20),
                'ten' => 'Chính trị II',
                'slug' => Str::slug('Chính trị II'),
                'mo_ta_ngan' => null,
                'so_tin_chi' => 3,
                'id_khoa' => 2
            ],
            [
                'ma' => Str::random(20),
                'ten' => 'Toán cao cấp',
                'slug' => Str::slug('Toán cao cấp'),
                'mo_ta_ngan' => null,
                'so_tin_chi' => 3,
                'id_khoa' => 2
            ],
            [
                'ma' => Str::random(20),
                'ten' => 'Lập trình web ASP.NET Core',
                'slug' => Str::slug('Lập trình web ASP.NET Core'),
                'mo_ta_ngan' => null,
                'so_tin_chi' => 3,
                'id_khoa' => 1
            ],
            [
                'ma' => Str::random(20),
                'ten' => 'Chính trị I',
                'slug' => Str::slug('Chính trị I'),
                'mo_ta_ngan' => null,
                'so_tin_chi' => 3,
                'id_khoa' => 2
            ],
            [
                'ma' => Str::random(20),
                'ten' => 'Cấu trúc dữ liệu & giải thuật',
                'slug' => Str::slug('Cấu trúc dữ liệu & giải thuật'),
                'mo_ta_ngan' => null,
                'so_tin_chi' => 3,
                'id_khoa' => 1
            ],
            [
                'ma' => Str::random(20),
                'ten' => 'Tiếng Anh 2',
                'slug' => Str::slug('Tiếng Anh 2'),
                'mo_ta_ngan' => null,
                'so_tin_chi' => 3,
                'id_khoa' => 2
            ],
            [
                'ma' => Str::random(20),
                'ten' => 'Tiếng Anh 1',
                'slug' => Str::slug('Tiếng Anh 1'),
                'mo_ta_ngan' => null,
                'so_tin_chi' => 3,
                'id_khoa' => 2
            ]
        ];

        foreach ($listKhoa as $khoa) {
            DB::table('hoc_phan')->insert([
                'ma' => $khoa['ma'],
                'ten' => $khoa['ten'],
                'slug' => $khoa['slug'],
                'mo_ta_ngan' => $khoa['mo_ta_ngan'],
                'so_tin_chi' => $khoa['so_tin_chi'],
                'id_khoa' => $khoa['id_khoa']
            ]);
        }
    }
}
