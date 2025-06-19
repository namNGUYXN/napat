<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AuthService;
use App\Services\NguoiDungService;
class NguoiDungController extends Controller
{
    protected $authService;
    protected $nguoiDungService;

    function __construct(AuthService $authService, NguoiDungService $nguoiDungService)
    {
        $this->authService = $authService;
        $this->nguoiDungService = $nguoiDungService;
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
}
