<?php

namespace App\Imports;

use App\NguoiDung;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Validators\Failure;

class NguoiDungImport implements OnEachRow, SkipsOnFailure
{
    use SkipsFailures;

    public function onRow(Row $row)
    {
        $rowIndex = $row->getIndex();
        $rowData = $row->toArray();

        $messages = [
            'ho_ten.required' => 'Họ tên không được để trống.',
            'ho_ten.regex' => 'Họ tên chỉ được chứa chữ cái và khoảng trắng.',
            'email.required' => 'Email là bắt buộc.',
            //'email.email' => 'Email không đúng định dạng.',
            'email.unique' => 'Email đã tồn tại trong hệ thống.',
            'sdt.numeric' => 'Số điện thoại phải là số.',
            'vai_tro.required' => 'Vai trò là bắt buộc.',
            'vai_tro.in' => 'Vai trò chỉ được là Giảng viên, Sinh viên hoặc Admin.',
        ];

        $validator = Validator::make([
            'ho_ten'  => $rowData[0] ?? null,
            'email'   => $rowData[1] ?? null,
            'sdt'     => $rowData[2] ?? null,
            'vai_tro' => $rowData[3] ?? null,
        ], [
            'ho_ten' => ['required', 'regex:/^[\p{L}\s]+$/u'],
            'email' => ['required', //'email',
             'unique:nguoi_dung,email'],
            'sdt' => ['nullable', 'numeric'],
            'vai_tro' => ['required', Rule::in(['Giảng viên', 'Sinh viên', 'Admin'])],
        ], $messages);

        if ($validator->fails()) {
            // ✅ Truyền từng Failure, KHÔNG truyền mảng []
            $this->onFailure(new Failure(
                $rowIndex,
                '', // Không cần field cụ thể
                $validator->errors()->all(),
                $rowData
            ));
            return;
        }


        NguoiDung::create([
            'ho_ten'    => $rowData[0],
            'email'     => $rowData[1],
            'sdt'       => $rowData[2],
            'mat_khau'  => $this->taoMatKhauNgauNhien(),
            'vai_tro'   => $rowData[3],
            'is_active' => true,
            'ngay_tao'  => now(),
        ]);
    }

    //Hàm tạo mật khẩu tự động
    private function taoMatKhauNgauNhien($length = 6)
    {
        $chu = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $so = '0123456789';
        $kyTuDacBiet = '!@#$%^&*';

        $password = Str::random(3);
        $password .= $chu[rand(0, strlen($chu) - 1)];
        $password .= $so[rand(0, strlen($so) - 1)];
        $password .= $kyTuDacBiet[rand(0, strlen($kyTuDacBiet) - 1)];

        return Str::substr(str_shuffle($password), 0, $length);
    }
}
