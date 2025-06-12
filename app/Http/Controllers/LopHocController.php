<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AuthService;
use App\Services\LopHocService;
use App\Services\BanTinService;
use App\Services\ThanhVienLopService;
class LopHocController extends Controller
{
    protected $authService;
    protected $lopService;
    protected $tinService;
    protected $thanhVienService;
    public function __construct(AuthService $authService,LopHocService $lopService,BanTinService $tinService,ThanhVienLopService $thanhVienService)
    {
        $this->authService = $authService;
        $this->lopService = $lopService;
        $this->tinService = $tinService;
        $this->thanhVienService = $thanhVienService;
    }
    public function lopHocCuaToi()
    {
        $nguoiDung = $this->authService->layNguoiDungDangNhap();
        $dsLopHoc = $this->lopService->getLopHocCuaToi($nguoiDung);

        return view('modules.lop-hoc.lop-hoc-cua-toi', compact('dsLopHoc'));
    }
    public function chiTietLopHoc($slug)
    {
        $lop = $this->lopService->layChiTietLopHoc($slug);
        $banTin = $this->tinService->layBanTinLopHoc($lop->id);
        $thanhVien = $this->thanhVienService->getAcceptedMembersByLopId($lop->id);
        $yeuCau = $this->thanhVienService->getPendingMembersByLopId($lop->id);
        return view('modules.lop-hoc.chi-tiet-lop-hoc', compact('lop', 'banTin','thanhVien','yeuCau'));
    }
}
