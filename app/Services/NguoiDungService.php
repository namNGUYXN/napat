<?php

namespace App\Services;

use App\NguoiDung;
use Illuminate\Support\Facades\Hash;

class NguoiDungService
{
  public function layTheoId($id)
  {
    return NguoiDung::find($id);
  }

  public function doiMatKhau(NguoiDung $nguoiDung, string $currentPassword, string $newPassword): array
    {
        if (!Hash::check($currentPassword, $nguoiDung->mat_khau)) {
            return [
                'status' => false,
                'message' => 'Mật khẩu hiện tại không chính xác.'
            ];
        }

        $nguoiDung->mat_khau = Hash::make($newPassword);
        $nguoiDung->save();

        return [
            'status' => true,
            'message' => 'Thay đổi mật khẩu thành công.'
        ];
    }
}