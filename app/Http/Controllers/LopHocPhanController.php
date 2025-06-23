<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AuthService;
use App\Services\BaiService;
use App\Services\BaiTrongLopService;
use App\Services\LopHocPhanService;
use App\Services\BanTinService;
use App\Services\NguoiDungService;
use App\Services\ThanhVienLopService;
use Ramsey\Uuid\Type\Integer;

class LopHocPhanController extends Controller
{
    protected $authService;
    protected $lopHocPhanService;
    protected $tinService;
    protected $thanhVienService;
    // protected $nguoiDungService;
    protected $baiService;
    protected $baiTrongLopService;

    public function __construct(
        AuthService $authService,
        LopHocPhanService $lopHocPhanService,
        BanTinService $tinService,
        ThanhVienLopService $thanhVienService,
        BaiService $baiService,
        BaiTrongLopService $baiTrongLopService
        //, NguoiDungService $nguoiDungService,
    ) {
        $this->authService = $authService;
        $this->lopHocPhanService = $lopHocPhanService;
        $this->tinService = $tinService;
        $this->thanhVienService = $thanhVienService;
        $this->baiService = $baiService;
        $this->baiTrongLopService = $baiTrongLopService;
        //     $this->nguoiDungService = $nguoiDungService;
    }
    public function lopHocCuaToi()
    {
        $nguoiDung = $this->authService->layNguoiDungDangNhap();
        $dsLopHoc = $this->lopHocPhanService->getLopHocCuaToi($nguoiDung);

        return view('modules.lop-hoc.lop-hoc-cua-toi', compact('dsLopHoc'));
    }

    public function chiTiet($slug)
    {
        $lop = $this->lopHocPhanService->layChiTietLopHoc($slug);
        $banTin = $this->tinService->layBanTinLopHoc($lop->id);
        $thanhVien = $this->thanhVienService->getAcceptedMembersByLopId($lop->id);
        $yeuCau = $this->thanhVienService->getPendingMembersByLopId($lop->id);
        //$nguoiDung = $this->nguoiDungService->layTheoId(session('id_nguoi_dung'));
        //$listMucBaiGiang = $nguoiDung->list_muc_bai_giang;
        $hocPhan = $lop->hoc_phan;
        $listChuong = $lop->bai_giang->list_chuong;
        $listChuongTrongLop = $lop->list_bai->groupBy('id_chuong');
        // return $listChuongTrongLop[3][0]->pivot->cong_khai;
        // return $listChuongTrongLop[1]->flatten(1);
        // return $listChuongTrongLop;
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
                //'listMucBaiGiang',
                'hocPhan',
                'listChuong',
                'listChuongTrongLop',
            )
        );
    }

    public function congKhaiBaiTrongLop(Request $request, $slug)
    {
        $lopHocPhan = $this->lopHocPhanService->layTheoSlug($slug);
        $data = $request->input('listBaiTrongLop');

        $result = $this->baiTrongLopService->congKhaiBaiTrongLop($lopHocPhan->id, $data);

        return response()->json([
            'success' => $result['success'],
            'message' => $result['message']
        ]);
    }

    public function xemNoiDungBai($id, $slug)
    {
        $bai = $this->baiService->layTheoSlug($slug);
        $baiTrongLop = $this->baiTrongLopService->layBaiTrongLop($id, $bai->id);

        return view('modules.bai.chi-tiet', compact('baiTrongLop'));
    }

    // public function layListBaiGiangTrongLop($id)
    // {
    //     $lopHoc = $this->lopService->layListBaiGiangTrongLop($id);
    //     return response()->json([
    //         'data' => $lopHoc
    //     ]);
    // }

    // public function ganBaiGiang(Request $request, $idLopHoc, $idChuong)
    // {
    //     $input = $request->input('listIdBaiGiang');

    //     $listIdBaiGiang = array_map('intval', $input);

    //     $result = $this->baiGiangLopService->them($listIdBaiGiang, $idLopHoc, $idChuong);

    //     return response()->json([
    //         'success' => $result['success'],
    //         'message' => $result['message']
    //     ]);
    // }

    // public function layListBaiGiangTheoChuongTrongLop($idLopHoc, $idChuong)
    // {
    //     $listBaiGiang = $this->lopService->layListBaiGiangTheoChuongTrongLop($idLopHoc, $idChuong);

    //     return response()->json([
    //         'data' => $listBaiGiang
    //     ]);
    // }

    // public function goBaiGiang($idLopHoc, $idChuong, $id)
    // {
    //     $result = $this->baiGiangLopService->goBaiGiang($idLopHoc, $idChuong, $id);

    //     return response()->json([
    //         'success' => $result['success'],
    //         'message' => $result['message']
    //     ]);
    // }
    // public function lopHocTheoHocPhan($id)
    // {
    //     $dsLopHoc = $this->lopService->layLopHocTheoHocPhan($id);

    //     return view('modules.lop-hoc.danh-sach', compact('dsLopHoc'));
    // }
}
