<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AuthService;
use App\Services\BaiService;
use App\Services\BaiTrongLopService;
use App\Services\LopHocPhanService;
use App\Services\BanTinService;
use App\Services\HocPhanService;
use App\Services\NguoiDungService;
use App\Services\ThanhVienLopService;
use Ramsey\Uuid\Type\Integer;

class LopHocPhanController extends Controller
{
    protected $authService;
    protected $lopHocPhanService;
    protected $tinService;
    protected $thanhVienService;
    protected $nguoiDungService;
    protected $baiService;
    protected $baiTrongLopService;
    protected $hocPhanService;

    public function __construct(
        AuthService $authService,
        LopHocPhanService $lopHocPhanService,
        BanTinService $tinService,
        ThanhVienLopService $thanhVienService,
        BaiService $baiService,
        BaiTrongLopService $baiTrongLopService,
        NguoiDungService $nguoiDungService,
        HocPhanService $hocPhanService
    ) {
        $this->authService = $authService;
        $this->lopHocPhanService = $lopHocPhanService;
        $this->tinService = $tinService;
        $this->thanhVienService = $thanhVienService;
        $this->baiService = $baiService;
        $this->baiTrongLopService = $baiTrongLopService;
        $this->nguoiDungService = $nguoiDungService;
        $this->hocPhanService = $hocPhanService;
        $this->middleware('lop_hoc_phan')->only('chiTiet');
        $this->middleware('bai_trong_lop')->only('xemNoiDungBai');
    }

    public function lopHocTheoHocPhan($id)
    {
        $hocPhan = $this->hocPhanService->layTheoId($id);
        $listLopHocPhan = $hocPhan->list_lop_hoc_phan()->paginate(6);

        // dd($listLopHocPhan->toArray());
        return view('modules.lop-hoc.danh-sach', compact('hocPhan', 'listLopHocPhan'));
    }

    public function lopHocCuaToi()
    {
        // $nguoiDung = $this->authService->layNguoiDungDangNhap();
        // $dsLopHoc = $this->lopHocPhanService->getLopHocCuaToi($nguoiDung);

        // return view('modules.lop-hoc.lop-hoc-cua-toi', compact('dsLopHoc'));
        //$dsLopHoc = $nguoiDung->list_lop_hoc_phan;

        $idNguoiDung = session('id_nguoi_dung');

        $dsLopHoc = $this->lopHocPhanService->getLopHocCuaToi($idNguoiDung);
        return view('modules.lop-hoc.lop-hoc-cua-toi', compact('dsLopHoc'));
    }

    public function chiTiet($slug)
    {
        $lopHocPhan = $this->lopHocPhanService->layChiTietLopHoc($slug);
        $listBanTin = $this->tinService->layBanTinLopHoc($lopHocPhan->id);
        $nguoiDung = $this->nguoiDungService->layTheoId(session('id_nguoi_dung'));
        $thanhVien = $this->thanhVienService->getAcceptedMembersByLopId($lopHocPhan->id);
        $yeuCau = $this->thanhVienService->getPendingMembersByLopId($lopHocPhan->id);
        //$nguoiDung = $this->nguoiDungService->layTheoId(session('id_nguoi_dung'));
        //$listMucBaiGiang = $nguoiDung->list_muc_bai_giang;
        $hocPhan = $lopHocPhan->hoc_phan;
        $listChuong = $lopHocPhan->bai_giang->list_chuong;
        $listChuongTrongLop = $lopHocPhan->list_bai->groupBy('id_chuong');
        // return $listChuongTrongLop[3][0]->pivot->cong_khai;
        // return $listChuongTrongLop[1]->flatten(1);
        // return $listChuongTrongLop;
        // dd($listBanTin->toArray());
        return view(
            'modules.lop-hoc.chi-tiet',
            compact(
                'lopHocPhan',
                'listBanTin',
                'nguoiDung',
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

        $result = $this->baiTrongLopService->congKhaiBai($lopHocPhan->id, $data);

        if ($result['success']) {
            $listChuong = $lopHocPhan->bai_giang->list_chuong;
            $listChuongTrongLop = $lopHocPhan->list_bai->groupBy('id_chuong');
            $tongSoBaiCongKhai = $listChuongTrongLop->flatten(1)
                ->filter(function ($bai) {
                    return $bai->pivot->cong_khai == true;
                })->count();

            $html = view('partials.lop-hoc-phan.chi-tiet.list-bai', compact(
                'listChuong',
                'listChuongTrongLop',
                'lopHocPhan'
            ))->render();

            return response()->json([
                'message' => $result['message'],
                'icon' => 'success',
                'html' => $html,
                'tongSoBaiCongKhai' => $tongSoBaiCongKhai
            ]);
        }

        return response()->json([
            'message' => $result['message'],
            'icon' => 'error'
        ]);
    }

    public function xemNoiDungBai($id, $slug)
    {
        $bai = $this->baiService->layTheoSlug($slug);
        $baiGiang = $bai->chuong->bai_giang;
        $lopHocPhan = $this->lopHocPhanService->layTheoId($id);
        $giangVienXem = session('id_nguoi_dung') == $baiGiang->id_giang_vien;

        $baiTrongLop = $this->baiTrongLopService->layBaiTrongLop($id, $bai->id, $giangVienXem);

        $listChuong = $baiGiang->list_chuong;
        $listChuongTrongLop = $lopHocPhan->list_bai->groupBy('id_chuong');
        
        // dd($listChuongTrongLop->toArray());

        return view('modules.bai.chi-tiet', compact(
            'baiTrongLop',
            'lopHocPhan',
            'listChuong',
            'listChuongTrongLop'
        ));
    }
}
