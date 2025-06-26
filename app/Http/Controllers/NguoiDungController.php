<?php

namespace App\Http\Controllers;

use App\Helpers\UploadImageHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Services\AuthService;
use App\Services\NguoiDungService;

class NguoiDungController extends Controller
{
    protected $authService;
    protected $nguoiDungService;
    protected $uploadImageHelper;

    function __construct(AuthService $authService, NguoiDungService $nguoiDungService, UploadImageHelper $uploadImageHelper)
    {
        $this->authService = $authService;
        $this->nguoiDungService = $nguoiDungService;
        $this->uploadImageHelper = $uploadImageHelper;
    }

    public function chiTiet()
    {
        $id = $this->authService->layIdNguoiDungDangNhap();
        $nguoiDung = $this->nguoiDungService->layTheoId($id);
        return view('modules.tai-khoan.chi-tiet', compact('nguoiDung'));
    }

    public function doiMatKhau(Request $request)
    {
        $id = $this->authService->layIdNguoiDungDangNhap();
        $nguoiDung = $this->nguoiDungService->layTheoId($id);

        $result = $this->nguoiDungService->doiMatKhau(
            $nguoiDung,
            $request->currentPassword,
            $request->newPassword
        );

        return response()->json($result);
    }

    public function capNhatThongTinCaNhan(Request $request)
    {
        $data = $request->validate(
            [
                'ho_ten' => 'required|string|max:100',
                'sdt' => [
                    'nullable',
                    'unique:nguoi_dung,sdt,' . session('id_nguoi_dung'),
                    'regex:/^(\+84|0)\d{9}$/',
                ],
                'hinh_anh' => 'image'
            ],
            [
                'ho_ten.required' => 'Vui lòng nhập họ tên',
                'ho_ten.max' => 'Họ tên tối đa 100 ký tự',
                'sdt.unique' => 'Số điện thoại đã tồn tại trong hệ thống',
                'sdt.regex' => 'Số điện thoại phải bắt đầu +84 hoặc 0 và kế tiếp tối đa là 9 số',
                'hinh_anh.image' => 'Hình ảnh không hợp lệ'
            ]
        );

        $data['hinh_anh'] = NULL;

        $id = $this->authService->layIdNguoiDungDangNhap();
        $nguoiDung = $this->nguoiDungService->layTheoId($id);

        if ($request->hasFile('hinh_anh')) {
            $file = $request->file('hinh_anh');
            
            // Xóa ảnh trong storage (nếu ảnh mặc định thì ko xóa)
            $hinh_anh_goc = $nguoiDung->hinh_anh;
            if (!Str::contains($hinh_anh_goc, 'no-avatar.png')) {
                $this->uploadImageHelper->delete($hinh_anh_goc);
            }

            $data['hinh_anh'] = $this->uploadImageHelper->upload($file, 'nguoi-dung');
        }

        $result = $this->nguoiDungService->capNhatThongTin($data, $nguoiDung);

        if ($result['success']) {
            return response()->json([
                'message' => $result['message'],
                'icon' => 'success'
            ]);
        }

        return response()->json([
            'message' => $result['message'],
            'icon' => 'error'
        ]);
    }
}
