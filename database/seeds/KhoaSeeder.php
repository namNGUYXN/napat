<?php

use App\Khoa;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class KhoaSeeder extends Seeder
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
                'ma' => 'CNTT',
                'ten' => 'Công Nghệ Thông Tin',
                'slug' => Str::slug('Công Nghệ Thông Tin'),
                'mo_ta_ngan' => null,
                'email' => null,
            ],
            [
                'ma' => 'GDDC',
                'ten' => 'Giáo Dục Đại Cương',
                'slug' => Str::slug('Giáo Dục Đại Cương'),
                'mo_ta_ngan' => null,
                'email' => null,
            ],
            [
                'ma' => 'CNOT',
                'ten' => 'Công Nghệ Ô Tô',
                'slug' => Str::slug('Công Nghệ Ô Tô'),
                'mo_ta_ngan' => null,
                'email' => null,
            ],
            [
                'ma' => 'DT',
                'ten' => 'Điện Tử',
                'slug' => Str::slug('Điện Tử'),
                'mo_ta_ngan' => null,
                'email' => null,
            ]
        ];

        Khoa::insert($data);
    }
}
