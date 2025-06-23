<?php

use App\Chuong;
use Illuminate\Database\Seeder;

class ChuongSeeder extends Seeder
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
                'tieu_de' => 'Chuong 2: lop 1',
                'mo_ta_ngan' => 'abbbbbb',
                'id_bai_giang' => 1,
                'thu_tu' => 2,
                'is_delete' => false
            ],
            [
                'tieu_de' => 'Chuong 2: xyz',
                'mo_ta_ngan' => 'Mo ta chuong 2.',
                'id_bai_giang' => 2,
                'thu_tu' => 1,
                'is_delete' => false
            ],
            [
                'tieu_de' => 'Chương 1: Giới thiệu',
                'mo_ta_ngan' => 'Mo ta chuong 1.',
                'id_bai_giang' => 1,
                'thu_tu' => 1,
                'is_delete' => false
            ],
            [
                'tieu_de' => 'Chuong 3: lop 1',
                'mo_ta_ngan' => null,
                'id_bai_giang' => 1,
                'thu_tu' => 3,
                'is_delete' => false
            ]
        ];

        Chuong::insert($data);
    }
}
