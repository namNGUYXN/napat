<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use App\Services\MucBaiGiangService;
use Illuminate\Http\Request;

class TaiLieuController extends Controller
{
    protected $mucService;
    protected $authService;

    public function __construct(MucBaiGiangService $mucService,AuthService $authService)
    {
        $this->mucService = $mucService;
        $this->authService = $authService;
    }
    
    public function danhSachTheoGiangVien()
    {
        $idGiangVien = $this->authService->layIdNguoiDungDangNhap();
        $dsMucBaiGiang = $this->mucService->getByGiangVienId($idGiangVien);
        return view('danh-sach-tai-lieu', compact('dsMucBaiGiang'));
    }

    public function chiTiet($id)
    {
        $muc = $this->mucService->layChiTietVaDanhSachBaiGiang($id);

        if (!$muc) {
            abort(404, 'Không tìm thấy mục bài giảng');
        }

        return view('danh-sach-bai-giang', compact('muc'));
    }
}
