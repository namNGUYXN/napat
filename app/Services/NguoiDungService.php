<?php

namespace App\Services;

use App\NguoiDung;
use Illuminate\Support\Facades\Hash;

class NguoiDungService
{
    public function layTheoId($id)
    {
        return NguoiDung::findOrFail($id);
    }

    public function doiMatKhau(NguoiDung $nguoiDung, string $currentPassword, string $newPassword): array
    {
        if (!Hash::check($currentPassword, $nguoiDung->mat_khau)) {
            return [
                'status' => false,
                'message' => 'Mật khẩu hiện tại không chính xác.'
            ];
        }
        // Kiểm tra mật khẩu mới
        $passwordPattern = '/^[A-Z](?=.*[a-z])(?=.*\d)(?=.*[\W_]).{5,31}$/';
        if (!preg_match($passwordPattern, $newPassword)) {
            return [
                'status' => false,
                'message' => 'Mật khẩu mới phải bắt đầu bằng chữ in hoa, chứa ít nhất 1 chữ thường, 1 số, 1 ký tự đặc biệt và có độ dài từ 6 đến 32 ký tự.'
            ];
        }

        $nguoiDung->mat_khau = $newPassword;
        $nguoiDung->save();

        return [
            'status' => true,
            'message' => 'Thay đổi mật khẩu thành công.'
        ];
    }
}
