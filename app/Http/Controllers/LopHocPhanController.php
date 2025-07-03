<?php

namespace App\Http\Controllers;

use App\Helpers\UploadImageHelper;
use Illuminate\Http\Request;
use App\Services\AuthService;
use App\Services\BaiService;
use App\Services\BaiTrongLopService;
use App\Services\LopHocPhanService;
use App\Services\BanTinService;
use App\Services\HocPhanService;
use App\Services\KhoaService;
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
    protected $khoaService;
    protected $uploadImageHelper;

    public function __construct(
        AuthService $authService,
        LopHocPhanService $lopHocPhanService,
        BanTinService $tinService,
        ThanhVienLopService $thanhVienService,
        BaiService $baiService,
        BaiTrongLopService $baiTrongLopService,
        NguoiDungService $nguoiDungService,
        KhoaService $khoaService,
        UploadImageHelper $uploadImageHelper
    ) {
        $this->authService = $authService;
        $this->lopHocPhanService = $lopHocPhanService;
        $this->tinService = $tinService;
        $this->thanhVienService = $thanhVienService;
        $this->baiService = $baiService;
        $this->baiTrongLopService = $baiTrongLopService;
        $this->nguoiDungService = $nguoiDungService;
        $this->khoaService = $khoaService;
        $this->uploadImageHelper = $uploadImageHelper;
        $this->middleware('lop_hoc_phan')->only('chiTiet');
        $this->middleware('bai_trong_lop')->only('xemNoiDungBai');
        $this->middleware('bai_giang')->only('them');
    }

    public function lopHocPhanTheoKhoa($slug)
    {
        $khoa = $this->khoaService->layTheoSlug($slug);
        $listLopHocPhan = $khoa->list_lop_hoc_phan()->paginate(6);

        // dd($listLopHocPhan->toArray());
        return view('modules.lop-hoc.danh-sach', compact('khoa', 'listLopHocPhan'));
    }

    public function lopHocCuaToi()
    {
        $idNguoiDung = session('id_nguoi_dung');
        $nguoiDung = $this->nguoiDungService->layTheoId($idNguoiDung);

        $dsLopHoc = $this->lopHocPhanService->getLopHocCuaToi($idNguoiDung);
        $listBaiGiang = $nguoiDung->list_bai_giang;
        $listKhoa = $this->khoaService->layListKhoa();
        return view('modules.lop-hoc.lop-hoc-cua-toi', compact(
            'dsLopHoc',
            'listBaiGiang',
            'listKhoa'
        ));
    }

    public function chiTiet($slug)
    {
        $lopHocPhan = $this->lopHocPhanService->layChiTietLopHoc($slug);
        $listBanTin = $this->tinService->layBanTinLopHoc($lopHocPhan->id);
        $nguoiDung = $this->nguoiDungService->layTheoId(session('id_nguoi_dung'));
        $thanhVien = $this->thanhVienService->getAcceptedMembersByLopId($lopHocPhan->id);
        $yeuCau = $this->thanhVienService->getPendingMembersByLopId($lopHocPhan->id);
        //$nguoiDung = $this->nguoiDungService->layTheoId(session('id_nguoi_dung'));
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

    public function them(Request $request)
    {
        $data = $request->validate(
            [
                'ten' => 'required|string|max:100',
                'id_khoa' => 'required|exists:khoa,id',
                'id_bai_giang' => 'required|exists:bai_giang,id',
                'mo_ta_ngan' => 'nullable|string|max:255',
                'hinh_anh' => 'image'
            ],
            [
                'ten.required' => 'Vui lòng nhập tên bài giảng',
                'ten.max' => 'Tên bài giảng tối đa 100 ký tự',
                'id_khoa.required' => 'Vui lòng chọn khoa',
                'id_khoa.exists' => 'Khoa không tồn tại',
                'id_bai_giang.required' => 'Vui lòng chọn bài giảng',
                'id_bai_giang.exists' => 'Bài giảng không tồn tại',
                'mo_ta_ngan.max' => 'Mô tả tối đa 255 ký tự',
                'hinh_anh.image' => 'Hình ảnh không hợp lệ'
            ]
        );
        $data['hinh_anh'] = NULL;

        // var_dump($request->file('hinh_anh'));
        if ($request->hasFile('hinh_anh')) {
            $file = $request->file('hinh_anh');
            $data['hinh_anh'] = $this->uploadImageHelper->upload($file, 'lop-hoc-phan');
        }

        $result = $this->lopHocPhanService->them($data);

        if ($result['success']) {
            $lopHocPhanVuaThem = $result['data'];
            $baiGiang = $lopHocPhanVuaThem->bai_giang;
            $messageError = [];

            // Chèn giảng viên vào bảng thanh_vien_lop
            $resultInsert = $this->thanhVienService->them($lopHocPhanVuaThem->id);
            if (!$resultInsert['success']) $messageError['thanh_vien_lop'] = $resultInsert['message'];

            // Chèn các bài của bài giảng vào bai_trong_lop
            $resultInsert = $this->baiTrongLopService->them($lopHocPhanVuaThem->id, $baiGiang->list_chuong);
            if (!$resultInsert['success']) $messageError['thanh_vien_lop'] = $resultInsert['message'];

            if (!empty($messageError)) dd($messageError);

            return redirect()->route('lop-hoc.lop-hoc-cua-toi')->with([
                'message' => $result['message'],
                'icon' => 'success'
            ]);
        }

        return redirect()->back()->with([
            'message' => $result['message'],
            'icon' => 'error'
        ]);
    }
}
