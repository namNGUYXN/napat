<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class NguoiDungSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $listNguoiDung = [
            [
                'ho_ten' => 'Nguyễn Phương Nam',
                'email' => '0306221252@caothang.edu.vn',
                'sdt' => '0362763235',
                'hinh_anh' => null,
                'mat_khau' => Hash::make('Nam1234!'),
                'vai_tro' => 'Admin',
                'is_active' => true,
            ],
            [
                'ho_ten' => 'Trần Hoài Ân',
                'email' => 'phuongnam3235@gmail.com',
                'sdt' => null,
                'hinh_anh' => null,
                'mat_khau' => Hash::make('Mk123!'),
                'vai_tro' => 'Giảng viên',
                'is_active' => true,
            ],
            [
                'ho_ten' => 'Nguyễn Tấn Phát',
                'email' => '0306221262@caothang.edu.vn',
                'sdt' => null,
                'hinh_anh' => null,
                'mat_khau' => Hash::make('Mk123!'),
                'vai_tro' => 'Giảng viên',
                'is_active' => true,
            ],
            [
                'ho_ten' => 'Lê Gia Nghi',
                'email' => 'phuongnam8481@gmail.com',
                'sdt' => null,
                'hinh_anh' => null,
                'mat_khau' => Hash::make('Mk123!'),
                'vai_tro' => 'Sinh viên',
                'is_active' => true,
            ],
            [
                'ho_ten' => 'Võ Thị Ánh',
                'email' => 'thesouth1124@gmail.com',
                'sdt' => null,
                'hinh_anh' => null,
                'mat_khau' => Hash::make('Mk123!'),
                'vai_tro' => 'Sinh viên',
                'is_active' => true,
            ]
        ];

        foreach ($listNguoiDung as $nguoiDung) {
            DB::table('nguoi_dung')->insert([
                'ho_ten' => $nguoiDung['ho_ten'],
                'email' => $nguoiDung['email'],
                'sdt' => $nguoiDung['sdt'],
                'hinh_anh' => $nguoiDung['hinh_anh'],
                'mat_khau' => $nguoiDung['mat_khau'],
                'vai_tro' => $nguoiDung['vai_tro'],
                'is_active' => $nguoiDung['is_active'],
            ]);
        }
    }
}
