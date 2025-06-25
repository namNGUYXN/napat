<?php

use App\BaiTrongLop;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BaiTrongLopSeeder extends Seeder
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
                'id_lop_hoc_phan' => 1,
                'id_bai' => 1,
                'cong_khai' => true
            ],
            [
                'id_lop_hoc_phan' => 1,
                'id_bai' => 4,
                'cong_khai' => false
            ],
            [
                'id_lop_hoc_phan' => 2,
                'id_bai' => 2,
                'cong_khai' => true
            ],
            [
                'id_lop_hoc_phan' => 1,
                'id_bai' => 5,
                'cong_khai' => false
            ],
            [
                'id_lop_hoc_phan' => 1,
                'id_bai' => 3,
                'cong_khai' => true
            ],
            [
                'id_lop_hoc_phan' => 1,
                'id_bai' => 6,
                'cong_khai' => true
            ],
        ];

        BaiTrongLop::insert($data);
    }
}