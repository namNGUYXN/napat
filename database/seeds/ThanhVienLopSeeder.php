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
                'id_lop_hoc' => 1,
                'id_sinh_vien' => 4, // Lê Gia Nghi
                'is_accept' => true,
            ],
            [
                'id_lop_hoc' => 1,
                'id_sinh_vien' => 5, // Võ Thị Ánh
                'is_accept' => false,
            ],
            [
                'id_lop_hoc' => 2,
                'id_sinh_vien' => 4, // Lê Gia Nghi
                'is_accept' => true,
            ],
            [
                'id_lop_hoc' => 2,
                'id_sinh_vien' => 5, // Võ Thị Ánh
                'is_accept' => false,
            ],
        ];

        foreach ($data as $item) {
            ThanhVienLop::create($item);
        }
    }
}
