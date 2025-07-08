<?php

namespace App\Http\Controllers;

use App\Services\BaiGiangService;
use App\Services\KhoaService;
use App\Services\NguoiDungService;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    protected $khoaService;
    protected $nguoiDungService;
    protected $baiGiangService;

    public function __construct(KhoaService $khoaService, NguoiDungService $nguoiDungService, BaiGiangService $baiGiangService)
    {
        $this->khoaService = $khoaService;
        $this->nguoiDungService = $nguoiDungService;
        $this->baiGiangService = $baiGiangService;
    }
    // Trang chủ phía client - Home
    function home()
    {
        $dsKhoa = $this->khoaService->layListKhoa();
        return view('home', compact('dsKhoa'));
    }

    // Trang chủ phía admin - Dashboard
    function dashboard()
    {
        $tongSoKhoa = $this->khoaService->layListKhoa()->count();
        $tongSoSinhVien = $this->nguoiDungService->layTheoSinhVien()->count();
        $tongSoGiangVien = $this->nguoiDungService->layTheoGiangVien()->count();
        $tongSoBaiGiang = $this->baiGiangService->layTatCa()->count();

        return view('admin.dashboard', compact(
            'tongSoKhoa',
            'tongSoSinhVien',
            'tongSoGiangVien',
            'tongSoBaiGiang'
        ));
    }
}
