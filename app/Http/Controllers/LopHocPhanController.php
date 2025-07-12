<?php

namespace App\Http\Controllers;

use App\Helpers\UploadImageHelper;
use App\Imports\ThanhVienLopImport;
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
use App\Services\BinhLuanService;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

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
    protected $binhLuanService;

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
        BaiTapService $baiTapService,
        BinhLuanService $binhLuanService
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
        $this->baiTapService = $baiTapService;
        $this->binhLuanService = $binhLuanService;
        $this->middleware('lop_hoc_phan')->only('chiTiet', 'modalChiTiet', 'modalChinhSua', 'chinhSua');
        $this->middleware('bai_trong_lop')->only('xemNoiDungBai');
        $this->middleware('bai_giang')->only('them', 'modalChinhSua', 'chinhSua');
    }

    public function lopHocPhanTheoKhoa(Request $request, $slug)
    {
        $idNguoiDung = session('id_nguoi_dung');
        $nguoiDung = $this->nguoiDungService->layTheoId($idNguoiDung);

        $khoa = $this->khoaService->layTheoSlug($slug);
        $listKhoa = $this->khoaService->layListKhoa();
        $listBaiGiang = $nguoiDung->list_bai_giang;
        $listLopHocPhan = $this->lopHocPhanService->layListTheoKhoa($request, $khoa->id);

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

        $dsLopHoc = $this->lopHocPhanService->getLopHocCuaToi($request, $idNguoiDung);
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

    public function xemNoiDungBai(Request $request, $id, $slug)
    {
        $bai = $this->baiService->layTheoSlug($slug);
        $baiGiang = $bai->chuong->bai_giang;
        $lopHocPhan = $this->lopHocPhanService->layTheoId($id);
        $giangVienXem = session('id_nguoi_dung') == $baiGiang->id_giang_vien;

        $baiTrongLop = $this->baiTrongLopService->layBaiTrongLop($lopHocPhan->id, $bai->id, $giangVienXem);

        $baiTap = $this->baiTapService->getByBaiGiangId($bai->id);

        $thanhVienLop = $this->thanhVienService->layTheoLopVaNguoiDung($lopHocPhan->id, session('id_nguoi_dung'));
        $listBinhLuan = $this->binhLuanService->layListTheoBaiTrongLop($baiTrongLop->id);
        // dd($listBinhLuan->toArray());

        $listChuong = $baiGiang->list_chuong;
        $listChuongTrongLop = $lopHocPhan->list_bai->groupBy('id_chuong');

        if ($search = $request->input('search')) {
            if ($search == 'all') $search = '';
            else $search = Str::of($search)->trim();

            $listChuongTrongLop = $lopHocPhan->list_bai()->where([
                ['tieu_de', 'LIKE', '%' . $search . '%']
            ])->get()->groupBy('id_chuong');
            // dd($listChuongTrongLop->toArray());

            $html = view('partials.lop-hoc-phan.noi-dung-bai.list-bai', compact(
                'listChuong',
                'listChuongTrongLop',
                'lopHocPhan'
            ))->render();

            return response()->json([
                'html' => $html
            ]);
        }

        return view('modules.bai.chi-tiet', compact(
            'baiTrongLop',
            'thanhVienLop',
            'lopHocPhan',
            'listChuong',
            'listChuongTrongLop',
            'baiTap',
            'listBinhLuan'
        ));
    }

    public function them(Request $request)
    {
        // dd($request->all());
        $data = $request->validate(
            [
                'ten' => 'required|string|max:100',
                'id_khoa' => 'required|exists:khoa,id',
                'id_bai_giang' => 'required|exists:bai_giang,id',
                'mo_ta_ngan' => 'nullable|string|max:255',
                'hinh_anh' => 'image|max:2048'
            ],
            [
                'ten.required' => 'Vui lòng nhập tên lớp học phần',
                'ten.max' => 'Tên lớp học phần tối đa 100 ký tự',
                'id_khoa.required' => 'Vui lòng chọn khoa',
                'id_khoa.exists' => 'Khoa không tồn tại',
                'id_bai_giang.required' => 'Vui lòng chọn bài giảng',
                'id_bai_giang.exists' => 'Bài giảng không tồn tại',
                'mo_ta_ngan.max' => 'Mô tả tối đa 255 ký tự',
                'hinh_anh.image' => 'Hình ảnh không hợp lệ',
                'hinh_anh.max' => 'Kích thước ảnh tối đa 2MB'
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

            $page = $request->input('page', 1);
            $view = $request->input('view');
            $route = route('lop-hoc.lop-hoc-cua-toi');
            $dsLopHoc = $this->lopHocPhanService->getLopHocCuaToi($request, session('id_nguoi_dung'), $page);

            $html = view('partials.lop-hoc-phan.danh-sach.list', compact('dsLopHoc', 'view', 'route'))->render();

            return response()->json([
                'message' => $result['message'],
                'icon' => 'success',
                'html' => $html
            ]);
        }

        return response()->json([
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
                'hinh_anh' => 'image|max:2048'
            ],
            [
                'ten.required' => 'Vui lòng nhập tên lớp học phần',
                'ten.max' => 'Tên lớp học phần tối đa 100 ký tự',
                'id_khoa.required' => 'Vui lòng chọn khoa',
                'id_khoa.exists' => 'Khoa không tồn tại',
                'id_bai_giang.required' => 'Vui lòng chọn bài giảng',
                'id_bai_giang.exists' => 'Bài giảng không tồn tại',
                'mo_ta_ngan.max' => 'Mô tả tối đa 255 ký tự',
                'hinh_anh.image' => 'Hình ảnh không hợp lệ',
                'hinh_anh.max' => 'Kích thước ảnh tối đa 2MB'
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
        // dd($request->all());
        $lopHocPhan = $this->lopHocPhanService->layTheoId($id);
        $khoa = $lopHocPhan->khoa;

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

            // debug lỗi
            if (!empty($messageError)) dd($messageError);

            $page = $request->input('page', 1);
            $view = $request->input('view');

            if ($view == "lop-hoc-cua-toi") {
                $dsLopHoc = $this->lopHocPhanService->getLopHocCuaToi($request, session('id_nguoi_dung'), $page);
                $route = route('lop-hoc.lop-hoc-cua-toi');
            } else if ($view == 'danh-sach') {
                $dsLopHoc = $this->lopHocPhanService->layListTheoKhoa($request, $khoa->id, $page);
                $route = route('lop-hoc.index', $khoa->slug);
            } else dd("truyen view sai");

            $html = view('partials.lop-hoc-phan.danh-sach.list', compact('dsLopHoc', 'view', 'route'))->render();

            return response()->json([
                'message' => $result['message'],
                'icon' => 'success',
                'html' => $html
            ]);
        }

        return response()->json([
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

    public function xoa(Request $request, $id)
    {
        // dd($request->all());
        $lopHocPhan = $this->lopHocPhanService->layTheoId($id);
        $pathHinhAnh = $lopHocPhan->hinh_anh;
        $khoa = $lopHocPhan->khoa;
        $nguoiDung = $this->nguoiDungService->layTheoId(session('id_nguoi_dung'));

        $result = $this->lopHocPhanService->xoa($lopHocPhan, $nguoiDung);

        if ($result['success']) {
            // Xóa ảnh khỏi hệ thống
            if (!Str::contains($pathHinhAnh, 'no-image.png')) {
                $this->uploadImageHelper->delete($pathHinhAnh);
            }

            $page = $request->input('page', 1);
            $view = $request->input('view');

            if ($view == "lop-hoc-cua-toi") {
                $dsLopHoc = $this->lopHocPhanService->getLopHocCuaToi($request, session('id_nguoi_dung'), $page);
                $route = route('lop-hoc.lop-hoc-cua-toi');
            } else if ($view == 'danh-sach') {
                $dsLopHoc = $this->lopHocPhanService->layListTheoKhoa($request, $khoa->id, $page);
                $route = route('lop-hoc.index', $khoa->slug);
            } else dd("truyen view sai");

            $html = view('partials.lop-hoc-phan.danh-sach.list', compact('dsLopHoc', 'view', 'route'))->render();

            return response()->json([
                'message' => $result['message'],
                'icon' => 'success',
                'html' => $html
            ]);
        }

        return response()->json([
            'message' => $result['message'],
            'icon' => 'error'
        ]);
    }

    public function dangKy(Request $request, $id)
    {
        $lopHocPhan = $this->lopHocPhanService->layTheoId($id);
        $khoa = $lopHocPhan->khoa;

        $result = $this->thanhVienService->them($id);

        if ($result['success']) {
            $page = $request->input('page', 1);
            $view = $request->input('view');

            $dsLopHoc = $this->lopHocPhanService->layListTheoKhoa($request, $khoa->id, $page);
            $route = route('lop-hoc.index', $khoa->slug);

            $html = view('partials.lop-hoc-phan.danh-sach.list', compact('dsLopHoc', 'view', 'route'))->render();

            return response()->json([
                'message' => 'Đã gửi đăng ký lớp học phần',
                'icon' => 'success',
                'html' => $html
            ]);
        }

        return response()->json([
            'message' => $result['message'],
            'icon' => $result['icon']
        ]);
    }

    public function roiKhoi($id)
    {
        $result = $this->thanhVienService->xoa($id, session('id_nguoi_dung'));

        if ($result['success']) {
            return response()->json([
                'message' => 'Rời khỏi lớp học phần thành công',
                'icon' => 'success'
            ]);
        }

        return response()->json([
            'message' => $result['message'],
            'icon' => $result['icon']
        ]);
    }

    public function xoaKhoilop($idLopHocPhan, $idNguoiDung)
    {
        $lopHocPhan = $this->lopHocPhanService->layTheoId($idLopHocPhan);
        $result = $this->thanhVienService->xoa($idLopHocPhan, $idNguoiDung);

        if ($result['success']) {
            $dsThanhVien = $this->thanhVienService->getAcceptedMembersByLopId($idLopHocPhan);
            $dsYeuCau = $this->thanhVienService->getPendingMembersByLopId($idLopHocPhan);

            $html = view('partials._thanh-vien-lop', [
                'thanhVien' => $dsThanhVien,
                'yeuCau' => $dsYeuCau,
                'lopHocPhan' => $lopHocPhan
            ])->render();

            return response()->json([
                'message' => 'Xóa sinh viên khỏi lớp thành công',
                'icon' => 'success',
                'html' => $html
            ]);
        }

        return response()->json([
            'message' => $result['message'],
            'icon' => $result['icon']
        ]);
    }

    public function chapNhan($id)
    {
        $result = $this->thanhVienService->chapNhanYeuCau($id);

        if ($result['status']) {
            $lopId = $result['lop_id'];
            $lopHocPhan = $this->lopHocPhanService->layTheoId($lopId);

            $dsThanhVien = $this->thanhVienService->getAcceptedMembersByLopId($lopId);
            $dsYeuCau = $this->thanhVienService->getPendingMembersByLopId($lopId);

            $html = view('partials._thanh-vien-lop', [
                'thanhVien' => $dsThanhVien,
                'yeuCau' => $dsYeuCau,
                'lopHocPhan' => $lopHocPhan
            ])->render();

            return response()->json([
                'status' => true,
                'message' => $result['message'],
                'html' => $html,
                'tongSoThanhVien' => $dsThanhVien->count()
            ]);
        }

        return response()->json($result);
    }

    public function tuChoi($id)
    {
        $result = $this->thanhVienService->tuChoiYeuCau($id);

        if ($result['status']) {
            $lopId = $result['lop_id'];

            $dsYeuCau = $this->thanhVienService->getPendingMembersByLopId($lopId);

            $html = view('partials._danh-sach-yeu-cau', [
                'yeuCau' => $dsYeuCau,
            ])->render();

            return response()->json([
                'status' => true,
                'message' => $result['message'],
                'html' => $html
            ]);
        }

        return response()->json($result);
    }

    public function themDanhSach(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv',
        ]);

        $idLopHocPhan = $request->input('id_lop_hoc_phan');

        $duocPhep = $this->thanhVienService->daThamGiaLopHocPhan($idLopHocPhan);

        if ($duocPhep) {
            Excel::import(new ThanhVienLopImport($idLopHocPhan), $request->file('file'));

            return back()->with([
                'message' => 'Đã import danh sách sinh viên!',
                'icon' => 'success'
            ]);
        }

        return back()->with([
            'message' => 'Không được phép import danh sách sinh viên vào lớp khác',
            'icon' => 'error'
        ]);
    }
}
