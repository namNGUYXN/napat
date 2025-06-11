<?php

namespace App\Services;

use App\NguoiDung;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class AuthService
{
  function dangNhap(array $thongTinDangNhap, $ghiNhoDangNhap = false)
  {
    $nguoiDung = NguoiDung::where('email', $thongTinDangNhap['email'])->first();

    if (!$nguoiDung) {
      return [
        'success' => false,
        'message' => 'Email hoặc mật khẩu không chính xác'
      ];
    }

    if (!Hash::check($thongTinDangNhap['mat_khau'], $nguoiDung->mat_khau) || !$nguoiDung->is_active) {
      return [
        'success' => false,
        'message' => !$nguoiDung->is_active ? 'Tài khoản của bạn đã bị khóa' : 'Email hoặc mật khẩu không chính xác'
      ];
    }

    session([
      'id_nguoi_dung' => $nguoiDung->id,
      'ho_ten' => $nguoiDung->ho_ten,
      'vai_tro' => $nguoiDung->vai_tro
    ]);

    if ($ghiNhoDangNhap) {
      $token = Str::random(60);
      $nguoiDung->token_remember = hash('sha256', $token);
      $nguoiDung->save();

      // Lưu token vào cookie
      Cookie::queue('token_remember', $token, 60*24*30); // 1 thang
    }

    return [
      'success' => true,
      'message' => 'Đăng nhập thành công'
    ];
  }

  function dangXuat()
  {
    $nguoiDung = NguoiDung::find(session('id_nguoi_dung'));
    if ($nguoiDung) {
      $nguoiDung->token_remember = null;
      $nguoiDung->save();
    }
    // Xóa tất cả session
    session()->flush();
    // Xóa cookie token_remember
    Cookie::queue(Cookie::forget('token_remember'));
    return ['success' => true, 'message' => 'Đã đăng xuất'];
  }
  public function layIdNguoiDungDangNhap()
  {
    return session('id_nguoi_dung');
  }
}
