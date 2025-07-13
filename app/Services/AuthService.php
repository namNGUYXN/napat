<?php

namespace App\Services;

use App\DatLaiMatKhau;
use App\Mail\DatLaiMatKhauMail;
use App\NguoiDung;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

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
      'vai_tro' => $nguoiDung->vai_tro,
      'is_change_pass' => $nguoiDung->is_change_pass
    ]);

    if ($ghiNhoDangNhap) {
      $token = Str::random(60);
      $nguoiDung->token_remember = hash('sha256', $token);
      $nguoiDung->save();

      // Lưu token vào cookie
      Cookie::queue('token_remember', $token, 60 * 24 * 30); // 1 thang
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

  public function layNguoiDungDangNhap()
  {
    return (object)[
      'id' => session('id_nguoi_dung'),
      'ho_ten' => session('ho_ten'),
      'vai_tro' => session('vai_tro')
    ];
  }

  function guiLienKetDatLaiMatKhau(string $email)
  {
    $nguoiDung = NguoiDung::where('email', $email)->first();
    if (!$nguoiDung) {
      return [
        'success' => false,
        'message' => 'Email không tồn tại trong hệ thống.',
      ];
    }

    $token = Str::random(60);

    // Lưu token vào bảng dat_lai_mat_khau
    DatLaiMatKhau::updateOrCreate(
      ['email' => $nguoiDung->email],
      [
        'token' => $token,
        'created_at' => Carbon::now(),
      ]
    );

    // Gửi email
    try {
      $data = [
        'token' => $token,
        'nguoi_dung' => $nguoiDung
      ];

      Mail::to($nguoiDung->email)->queue(new DatLaiMatKhauMail($data));
    } catch (\Exception $e) {
      return [
        'success' => false,
        'message' => 'Không thể gửi email. Vui lòng thử lại sau.',
      ];
    }

    return [
      'success' => true,
      'message' => 'Liên kết đặt lại mật khẩu đã được gửi đến email của bạn!',
    ];
  }

  function xacThucTokenDatLaiMatKhau(string $token)
  {
    $reset = DatLaiMatKhau::where('token', $token)->first();

    if (!$reset || Carbon::parse($reset->created_at)->addMinutes(10)->isPast()) {
      return [
        'success' => false,
        'message' => 'Liên kết đặt lại mật khẩu không hợp lệ hoặc đã hết hạn.'
      ];
    }

    return [
      'success' => true,
      'email' => $reset->email
    ];
  }

  function datLaiMatKhau(array $data)
  {
    $reset = DatLaiMatKhau::where([
      ['email', $data['email']],
      ['token', $data['token']]
    ])->first();

    if (!$reset || Carbon::parse($reset->created_at)->addMinutes(10)->isPast()) {
      return [
        'success' => false,
        'message' => 'Liên kết đặt lại mật khẩu không hợp lệ hoặc đã hết hạn.',
      ];
    }

    $nguoiDung = NguoiDung::where('email', $data['email'])->first();
    $nguoiDung->mat_khau = $data['mat_khau'];
    $nguoiDung->save();

    // Xóa token khỏi db
    DatLaiMatKhau::where('email', $data['email'])->delete();

    return [
      'success' => true,
      'message' => 'Mật khẩu đã được đặt lại thành công.',
    ];
  }

  public function doiMatKhauLanDau(int $idNguoiDung, string $newPassword): array
  {
    // Kiểm tra mật khẩu mới
    $passwordPattern = '/^[A-Z](?=.*[a-z])(?=.*\d)(?=.*[\W_]).{5,31}$/';
    if (!preg_match($passwordPattern, $newPassword)) {
      return [
        'status' => false,
        'message' => 'Mật khẩu mới phải bắt đầu bằng chữ in hoa, chứa ít nhất 1 chữ thường, 1 số, 1 ký tự đặc biệt và có độ dài từ 6 đến 32 ký tự.'
      ];
    }

    $nguoiDung = NguoiDung::findOrFail($idNguoiDung);

    $nguoiDung->mat_khau = $newPassword;
    $nguoiDung->is_change_pass = true;
    $nguoiDung->save();

    return [
      'status' => true,
      'message' => 'Thay đổi mật khẩu thành công.'
    ];
  }
}
