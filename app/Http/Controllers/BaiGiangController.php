<?php

namespace App\Http\Controllers;

use App\Services\BaiGiangService;
use Illuminate\Http\Request;

class BaiGiangController extends Controller
{
    protected $baiGiangService;

    public function __construct(BaiGiangService $baiGiangService)
    {
        $this->baiGiangService = $baiGiangService;
    }

    // Hiển thị form chỉnh sửa bài giảng
    public function chinhSua($id)
    {
        $baiGiang = $this->baiGiangService->layChiTietBaiGiang($id);

        return view('chinh-sua-bai-giang', compact('baiGiang'));
    }

    // Trang chủ phía admin - Dashboard
    function danhSach()
    {
        return view('danh-sach-bai-giang');
    }
}
