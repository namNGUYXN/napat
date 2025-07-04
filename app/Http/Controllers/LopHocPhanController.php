<?php

namespace App\Http\Controllers;

use App\Helpers\UploadImageHelper;
use Illuminate\Http\Request;
use App\Services\AuthService;
use App\Services\BaiGiangService;
use App\Services\BaiService;
use App\Services\BaiTrongLopService;
use App\Services\LopHocPhanService;
use App\Services\BanTinService;
use App\Services\KhoaService;
use App\Services\NguoiDungService;
use App\Services\ThanhVienLopService;
use App\Services\BaiTapService;
use Illuminate\Support\Str;

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
    protected $baiGiangService;
    protected $baiTapService;

    public function __construct(
        AuthService $authService,
        LopHocPhanService $lopHocPhanService,
        BanTinService $tinService,
        ThanhVienLopService $thanhVienService,
        BaiService $baiService,
        BaiTrongLopService $baiTrongLopService,
        NguoiDungService $nguoiDungService,
        KhoaService $khoaService,
        UploadImageHelper $uploadImageHelper,
        BaiGiangService $baiGiangService,
        BaiTapService $baiTapService
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
        $this->baiGiangService = $baiGiangService;
        $this->middleware('lop_hoc_phan')->only('chiTiet', 'modalChinhSua', 'chinhSua');
        $this->middleware('bai_trong_lop')->only('xemNoiDungBai');
        $this->middleware('bai_giang')->only('them', 'modalChinhSua', 'chinhSua');
        $this->baiTapService = $baiTapService;
    }

    public function lopHocPhanTheoKhoa(Request $request, $slug)
    {
        $idNguoiDung = session('id_nguoi_dung');
        $nguoiDung = $this->nguoiDungService->layTheoId($idNguoiDung);

        $khoa = $this->khoaService->layTheoSlug($slug);
        $listKhoa = $this->khoaService->layListKhoa();
        $listBaiGiang = $nguoiDung->list_bai_giang;
        $listLopHocPhan = $khoa->list_lop_hoc_phan()->paginate(6);

        // Kiểm tra số trang
        $page = (int) $request->input('page', 1);
        $lastPage = $listLopHocPhan->lastPage();

        if ($page > $lastPage && $lastPage > 0) {
            return redirect()->route('lop-hoc.index', array_merge(
                ['slug' => $slug],
                $request->except('page'),
                ['page' => $lastPage]
            ));
        } else if ($page < 1) {
            return redirect()->route('lop-hoc.index', array_merge(
                ['slug' => $slug],
                $request->except('page'),
                ['page' => 1]
            ));
        }

        // dd($listLopHocPhan->toArray());
        return view('modules.lop-hoc.danh-sach', compact(
            'khoa',
            'listKhoa',
            'listBaiGiang',
            'listLopHocPhan',
        ));
    }

    public function lopHocCuaToi(Request $request)
    {
        $idNguoiDung = session('id_nguoi_dung');
        $nguoiDung = $this->nguoiDungService->layTheoId($idNguoiDung);

        $perPage = 6;
        $dsLopHoc = $this->lopHocPhanService->getLopHocCuaToi($idNguoiDung, $perPage);
        $listBaiGiang = $nguoiDung->list_bai_giang;
        $listKhoa = $this->khoaService->layListKhoa();

        // Kiểm tra số trang
        $page = (int) $request->input('page', 1);
        $lastPage = $dsLopHoc->lastPage();

        if ($page > $lastPage && $lastPage > 0) {
            return redirect()->route('lop-hoc.lop-hoc-cua-toi', array_merge(
                $request->except('page'),
                ['page' => $lastPage]
            ));
        } else if ($page < 1) {
            return redirect()->route('lop-hoc.lop-hoc-cua-toi', array_merge(
                $request->except('page'),
                ['page' => 1]
            ));
        }

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
        $listChuong = $lopHocPhan->bai_giang->list_chuong;
        $listChuongTrongLop = $lopHocPhan->list_bai->groupBy('id_chuong');
        // return $listChuongTrongLop[3][0]->pivot->cong_khai;
        // return $listChuongTrongLop[1]->flatten(1);
        // return $listChuongTrongLop;
        // dd($listBanTin->toArray());
        $listKhoa = $this->khoaService->layListKhoa();
        $listBaiGiang = $nguoiDung->list_bai_giang;

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
                'listKhoa',
                'listBaiGiang'
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

        $baiTap = $this->baiTapService->getByBaiGiangId($bai->id);

        $listChuong = $baiGiang->list_chuong;
        $listChuongTrongLop = $lopHocPhan->list_bai->groupBy('id_chuong');

        // dd($listChuongTrongLop->toArray());

        return view('modules.bai.chi-tiet', compact(
            'baiTrongLop',
            'lopHocPhan',
            'listChuong',
            'listChuongTrongLop',
            'baiTap'
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
            if (!$resultInsert['success']) $messageError['bai_trong_lop'] = $resultInsert['message'];

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

    public function modalChiTiet($id)
    {
        $lopHocPhan = $this->lopHocPhanService->layTheoId($id);

        return response()->json([
            'data' => $lopHocPhan
        ]);
    }

    private function handleChinhSua($request, $id)
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
                'ten.required' => 'Vui lòng nhập tên lớp học phần',
                'ten.max' => 'Tên lớp học phần tối đa 100 ký tự',
                'id_khoa.required' => 'Vui lòng chọn khoa',
                'id_khoa.exists' => 'Khoa không tồn tại',
                'id_bai_giang.required' => 'Vui lòng chọn bài giảng',
                'id_bai_giang.exists' => 'Bài giảng không tồn tại',
                'mo_ta_ngan.max' => 'Mô tả tối đa 255 ký tự',
                'hinh_anh.image' => 'Hình ảnh không hợp lệ'
            ]
        );
        $data['hinh_anh'] = NULL;

        if ($request->hasFile('hinh_anh')) {
            $file = $request->file('hinh_anh');

            // Xóa ảnh trong storage (nếu ảnh mặc định thì ko xóa)
            $hinh_anh_goc = $this->lopHocPhanService->layTheoId($id)->hinh_anh;
            if (!Str::contains($hinh_anh_goc, 'no-image.png')) {
                $this->uploadImageHelper->delete($hinh_anh_goc);
            }

            $data['hinh_anh'] = $this->uploadImageHelper->upload($file, 'lop-hoc-phan');
        }

        return $this->lopHocPhanService->chinhSua($id, $data);
    }

    public function modalChinhSua(Request $request, $id)
    {
        $result = $this->handleChinhSua($request, $id);

        if ($result['success']) {
            $lopHocPhanVuaChinhSua = $result['data'];
            $baiGiang = $lopHocPhanVuaChinhSua->bai_giang;
            $messageError = [];

            // Cập nhật lại các bài trong lớp
            if ($result['id_bai_giang_ban_dau'] != $baiGiang->id) {
                $baiGiangBanDau = $this->baiGiangService->layTheoId($result['id_bai_giang_ban_dau']);
                // Xóa các bài cũ trong lớp
                $resultInsert = $this->baiTrongLopService->xoa($id, $baiGiangBanDau->list_chuong);
                if (!$resultInsert['success']) $messageError['bai_trong_lop.xoa'] = $resultInsert['message'];

                // Thêm các bài mới vào lớp
                $resultInsert = $this->baiTrongLopService->them($lopHocPhanVuaChinhSua->id, $baiGiang->list_chuong);
                if (!$resultInsert['success']) $messageError['bai_trong_lop.them'] = $resultInsert['message'];
            }

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

    public function chinhSua(Request $request, $id)
    {
        $result = $this->handleChinhSua($request, $id);

        if ($result['success']) {
            $lopHocPhanVuaChinhSua = $result['data'];
            $baiGiang = $lopHocPhanVuaChinhSua->bai_giang;
            $messageError = [];

            // Cập nhật lại các bài trong lớp
            if ($result['id_bai_giang_ban_dau'] != $baiGiang->id) {
                $baiGiangBanDau = $this->baiGiangService->layTheoId($result['id_bai_giang_ban_dau']);
                // Xóa các bài cũ trong lớp
                $resultInsert = $this->baiTrongLopService->xoa($id, $baiGiangBanDau->list_chuong);
                if (!$resultInsert['success']) $messageError['bai_trong_lop.xoa'] = $resultInsert['message'];

                // Thêm các bài mới vào lớp
                $resultInsert = $this->baiTrongLopService->them($lopHocPhanVuaChinhSua->id, $baiGiang->list_chuong);
                if (!$resultInsert['success']) $messageError['bai_trong_lop.them'] = $resultInsert['message'];
            }

            if (!empty($messageError)) dd($messageError);

            return redirect()->route('lop-hoc.detail', $lopHocPhanVuaChinhSua->slug)->with([
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
