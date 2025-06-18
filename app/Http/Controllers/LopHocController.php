<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AuthService;
use App\Services\BaiGiangLopService;
use App\Services\LopHocService;
use App\Services\BanTinService;
use App\Services\NguoiDungService;
use App\Services\ThanhVienLopService;
use Ramsey\Uuid\Type\Integer;

class LopHocController extends Controller
{
    protected $authService;
    protected $lopService;
    protected $tinService;
    protected $thanhVienService;
    protected $nguoiDungService;
    protected $baiGiangLopService;

    public function __construct(AuthService $authService, 
    LopHocService $lopService, BanTinService $tinService, 
    ThanhVienLopService $thanhVienService, NguoiDungService $nguoiDungService,
    BaiGiangLopService $baiGiangLopService)
    {
        $this->authService = $authService;
        $this->lopService = $lopService;
        $this->tinService = $tinService;
        $this->thanhVienService = $thanhVienService;
        $this->nguoiDungService = $nguoiDungService;
        $this->baiGiangLopService = $baiGiangLopService;
    }
    public function lopHocCuaToi()
    {
        $nguoiDung = $this->authService->layNguoiDungDangNhap();
        $dsLopHoc = $this->lopService->getLopHocCuaToi($nguoiDung);

        return view('modules.lop-hoc.lop-hoc-cua-toi', compact('dsLopHoc'));
    }

    public function chiTiet($slug)
    {
        $lop = $this->lopService->layChiTietLopHoc($slug);
        $banTin = $this->tinService->layBanTinLopHoc($lop->id);
        $thanhVien = $this->thanhVienService->getAcceptedMembersByLopId($lop->id);
        $yeuCau = $this->thanhVienService->getPendingMembersByLopId($lop->id);
        $nguoiDung = $this->nguoiDungService->layTheoId(session('id_nguoi_dung'));
        $listMucBaiGiang = $nguoiDung->list_muc_bai_giang;
        $hocPhan = $lop->hoc_phan;
        $listChuong = $hocPhan->list_chuong;
        $listBaiGiang = $this->lopService->layListBaiGiangTrongLop($lop->id)->bai_giang_lop;
        // return $listBaiGiang;
        // echo "<pre>";
        // print_r($listBaiGiang);
        // echo "</pre>";
        // return;
        return view(
            'modules.lop-hoc.chi-tiet',
            compact(
                'lop',
                'banTin',
                'thanhVien',
                'yeuCau',
                'listMucBaiGiang',
                'hocPhan',
                'listChuong',
                'listBaiGiang'
            )
        );
    }

    public function layListBaiGiangTrongLop($id)
    {
        $lopHoc = $this->lopService->layListBaiGiangTrongLop($id);
        return response()->json([
            'data' => $lopHoc
        ]);
    }

    public function ganBaiGiang(Request $request, $idLopHoc, $idChuong)
    {
        $input = $request->input('listIdBaiGiang');

        $listIdBaiGiang = array_map('intval', $input);

        $result = $this->baiGiangLopService->them($listIdBaiGiang, $idLopHoc, $idChuong);
        
        return response()->json([
            'success' => $result['success'],
            'message' => $result['message']
        ]);
    }

    public function layListBaiGiangTheoChuongTrongLop($idLopHoc, $idChuong)
    {
        $listBaiGiang = $this->lopService->layListBaiGiangTheoChuongTrongLop($idLopHoc, $idChuong);

        return response()->json([
            'data' => $listBaiGiang
        ]);
    }

    public function goBaiGiang($idLopHoc, $idChuong, $id)
    {
        $result = $this->baiGiangLopService->goBaiGiang($idLopHoc, $idChuong, $id);
        
        return response()->json([
            'success' => $result['success'],
            'message' => $result['message']
        ]);
    }
}
