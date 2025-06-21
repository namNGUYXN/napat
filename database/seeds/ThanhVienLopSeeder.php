<?php

use App\ThanhVienLop;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class ThanhVienLopSeeder extends Seeder
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
                'id_nguoi_dung' => 4, // Lê Gia Nghi
                'is_accept' => true,
            ],
            [
                'id_lop_hoc_phan' => 1,
                'id_nguoi_dung' => 2, // Trần Hoài Ân
                'is_accept' => null,
            ],
            [
                'id_lop_hoc_phan' => 2,
                'id_nguoi_dung' => 3, // Nguyễn Tấn Phát
                'is_accept' => null,
            ],
            [
                'id_lop_hoc_phan' => 2,
                'id_nguoi_dung' => 4, // Lê Gia Nghi
                'is_accept' => true,
            ],
            [
                'id_lop_hoc_phan' => 1,
                'id_nguoi_dung' => 5, // Võ Thị Ánh
                'is_accept' => false,
            ],
            [
                'id_lop_hoc_phan' => 2,
                'id_nguoi_dung' => 5, // Võ Thị Ánh
                'is_accept' => false,
            ],
        ];

        foreach ($data as $item) {
            ThanhVienLop::create($item);
        }
    }
}