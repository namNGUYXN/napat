<?php

namespace App\Services;

use App\NguoiDung;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\NguoiDungImport;
use App\Mail\ThemNguoiDungMail;
use Illuminate\Support\Facades\Mail;

class NguoiDungService
{
    public function layTheoId($id)
    {
        return NguoiDung::findOrFail($id);
    }

    public function layTheoSinhVien()
    {
        return NguoiDung::where('vai_tro', 'Sinh viên')->get();
    }

    public function layTheoGiangVien()
    {
        return NguoiDung::where('vai_tro', 'Giảng viên')->get();
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


    public function capNhatThongTin(array $data, $nguoiDung)
    {
        try {
            DB::beginTransaction();

            $nguoiDung->ho_ten = $data['ho_ten'];
            $nguoiDung->hinh_anh = $data['hinh_anh'] ?? $nguoiDung->hinh_anh;
            $nguoiDung->sdt = $data['sdt'];
            $nguoiDung->save();

            DB::commit();
            return [
                'success' => true,
                'message' => 'Cập nhật thông tin cá nhân thành công'
            ];
        } catch (\Exception $e) {
            DB::rollback();
            return [
                'success' => false,
                'message' => 'Lỗi khi cập nhật thông tin: ' + $e->getMessage()
            ];
        }
    }

    public function danhSachNguoiDung($vaiTro = null, $keyword = null, $perPage = 5)
    {
        $query = NguoiDung::query();

        if ($vaiTro !== null) {
            $query->where('vai_tro', $vaiTro); // 1: giảng viên, 2: sinh viên
        }

        if ($keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('ho_ten', 'like', "%$keyword%")
                    ->orWhere('email', 'like', "%$keyword%")
                    ->orWhere('sdt', 'like', "%$keyword%");
            });
        }

        return $query->orderByDesc('ngay_tao')->paginate($perPage);
    }

    public function themNguoiDung($data)
    {
        $matKhau = $this->taoMatKhauNgauNhien(); // Tạo mật khẩu ngẫu nhiên
        $nguoiDung = NguoiDung::create([
            'ho_ten' => $data['ho_ten'],
            'email' => $data['email'],
            'sdt' => $data['sdt'] ?? null,
            'mat_khau' => $matKhau,
            'vai_tro' => $data['vai_tro'],
            'is_active' => true,
            'ngay_tao' => Carbon::now(),
        ]);

        // Gửi email
        try {
            $data = [
                'nguoiDung' => $nguoiDung,
                'matKhau' => $matKhau
            ];

            Mail::to($nguoiDung->email)->send(new ThemNguoiDungMail($data));
            return [
                'success' => true,
                'message' => 'Thêm người dùng thành công!'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Không thể gửi email. Vui lòng thử lại sau.',
            ];
        }
    }

    public function importTuExcel($file)
    {
        $import = new NguoiDungImport();
        Excel::import($import, $file);

        // Nếu có lỗi thì không lưu
        if ($import->failures()->isNotEmpty()) {
            return [
                'success' => false,
                'failures' => $import->failures(),
            ];
        }

        try {
            // Lưu toàn bộ dữ liệu hợp lệ
            foreach ($import->getValidRows() as $row) {
                $nguoiDung = NguoiDung::create($row);

                $data = [
                    'nguoiDung' => $nguoiDung,
                    'matKhau' => $row['mat_khau']
                ];

                Mail::to($nguoiDung->email)->send(new ThemNguoiDungMail($data));
            }

            return ['success' => true];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Không thể gửi email. Vui lòng thử lại sau.',
            ];
        }
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

    public function capNhatNguoiDung($id, $data)
    {
        $nguoiDung = NguoiDung::findOrFail($id);

        $updateData = [
            'ho_ten' => $data['ho_ten'],
            'sdt' => $data['sdt'] ?? null,
            'vai_tro' => $data['vai_tro'],
        ];

        if (!$nguoiDung->is_logged) {
            $updateData['email'] = $data['email'];
        }

        $nguoiDung->update($updateData);

        return $nguoiDung;
    }

    public function khoaMo($id)
    {
        $nguoiDung = NguoiDung::findOrFail($id);
        $nguoiDung->is_active = !$nguoiDung->is_active;
        $nguoiDung->save();
    }
}
