<?php

namespace App\Imports;

use App\NguoiDung;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Str;

class NguoiDungImport implements ToModel
{
    public function model(array $row)
    {
        return new NguoiDung([
            'ho_ten'    => $row[0],
            'email'     => $row[1],
            'sdt'       => $row[2],
            'mat_khau' => $this->taoMatKhauNgauNhien(),
            'vai_tro'   => $row[4],
            'is_active' => true,
            'ngay_tao'  => now()
        ]);
    }

    private function taoMatKhauNgauNhien($length = 6)
    {
        $chu = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $so = '0123456789';
        $kyTuDacBiet = '!@#$%^&*';

        $all = $chu . $so . $kyTuDacBiet;

        $password = Str::random(3); // Khởi tạo ngẫu nhiên ban đầu

        // Đảm bảo có ít nhất 1 ký tự thuộc mỗi nhóm
        $password .= $chu[rand(0, strlen($chu) - 1)];
        $password .= $so[rand(0, strlen($so) - 1)];
        $password .= $kyTuDacBiet[rand(0, strlen($kyTuDacBiet) - 1)];

        return Str::substr(str_shuffle($password), 0, $length);
    }
}
