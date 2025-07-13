<?php

namespace App\Http\Controllers;

use App\Services\BaiTapService;
use Illuminate\Http\Request;
use App\Services\ThanhVienLopService;
use App\Services\LopHocPhanService;
use App\Services\TienDoHocTapService;
use App\Services\BaiTrongLopService;

class BaiTapController extends Controller
{
    protected $baiTapService;
    protected $thanhVienLopService;
    protected $lopHocPhanService;
    protected $tienDoHocTapService;
    protected $baiTrongLopService;

    public function __construct(BaiTapService $baiTapService, ThanhVienLopService $thanhVienService, LopHocPhanService $lopHocPhanService, TienDoHocTapService $tienDoHocTapService, BaiTrongLopService $baiTrongLopService)
    {
        $this->baiTapService = $baiTapService;
        $this->thanhVienLopService = $thanhVienService;
        $this->lopHocPhanService = $lopHocPhanService;
        $this->tienDoHocTapService = $tienDoHocTapService;
        $this->baiTrongLopService = $baiTrongLopService;
    }

    public function danhSachBaiTap($id)
    {
        $baiTaps = $this->baiTapService->getByBaiGiangId($id);
        return response()->json($baiTaps);
    }

    public function themBaiTap(Request $request)
    {
        try {
            // Gọi hàm tạo bài tập — nếu lỗi sẽ bị bắt ở catch
            $this->baiTapService->createExercise($request->all());

            // Chỉ đến đây khi thêm thành công
            $baiGiangId = $request->input('idBaiGiang');
            $baiTaps = $this->baiTapService->getByBaiGiangId($baiGiangId);

            return response()->json([
                'success' => true,
                'message' => 'Tạo bài tập thành công',
                'data' => $baiTaps
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Tạo bài tập thất bại: ' . $e->getMessage()
            ], 500);
        }
    }

    //Dữ liệu chi tiết bài kiểm tra theo id bài kiểm tra
    public function layChiTiet($lop, $id)
    {
        $idNguoiDung = session('id_nguoi_dung');
        $vaiTro = session('vai_tro');

        $result = $this->baiTapService->layChiTietTheoVaiTro($id, $lop, $idNguoiDung, $vaiTro);

        return response()->json($result);
    }

    //Trả về giao diện làm bài tập
    function lamBai($id_lop_hoc_phan, $id)
    {
        $idNguoiDung = session('id_nguoi_dung');
        $baiTap = $this->baiTapService->getById($id);
        $thanhVienLop = $this->thanhVienLopService->layTheoLopVaNguoiDung(
            $id_lop_hoc_phan,
            $idNguoiDung
        );

        $lopHocPhan = $this->lopHocPhanService->layTheoId($id_lop_hoc_phan);

        if ($thanhVienLop === null) {
            return view('modules.lop-hoc.thong-bao-nop-bai', [
                'thanhCong' => false,
                'noiDung' => "Không thể làm bài tập này",
                'thongBao' => "Bạn không có quyền thực hiện yêu cầu này!!!",
                'lop' => $lopHocPhan
            ]);
        }
        return view('modules.lop-hoc.lam-bai-tap', compact('baiTap', 'lopHocPhan'));
    }

    //Lưu lại kết quả làm bài của sinh viên
    public function nopBai(Request $request)
    {
        $request->validate([
            'id_bai_tap' => 'required|exists:bai_tap,id',
        ]);

        $idBaiTap = $request->input('id_bai_tap');
        $answers = $request->input('answers') ?? [];
        $id_lop_hoc_phan = $request->input('id_lop_hoc_phan');

        $baiTap = $this->baiTapService->getByIdWithBai($idBaiTap);

        $idNguoiDung = session('id_nguoi_dung');

        $thanhVienLop = $this->thanhVienLopService->layTheoLopVaNguoiDung(
            $id_lop_hoc_phan,
            $idNguoiDung
        );
        $lopHocPhan = $this->lopHocPhanService->layTheoId($id_lop_hoc_phan);
        if ($thanhVienLop === null) {
            return view('modules.lop-hoc.thong-bao', [
                'thanhCong' => false,
                'thongBao' => "Bạn không có quyền thực hiện yêu cầu này!!!",
                'lop' => $lopHocPhan
            ]);
        }

        // Kiểm tra trước khi cho nộp bài
        $kiemTra = $this->baiTapService->kiemTraDaNopBai($idBaiTap, $thanhVienLop->id);

        if (!$kiemTra['success']) {
            return view('modules.lop-hoc.thong-bao', [
                'thanhCong' => false,
                'noiDung' => "Không thể nộp bài",
                'thongBao' => $kiemTra['message'],
                'lop' => $lopHocPhan
            ]);
        }

        $ketQua = $this->baiTapService->nopBai($idBaiTap, $thanhVienLop->id, $answers);

        if (!$ketQua['success']) {
            return view('modules.lop-hoc.thong-bao', [
                'thanhCong' => false,
                'noiDung' => "Không thể nộp bài",
                'thongBao' => $ketQua['message'],
                'lop' => $lopHocPhan
            ]);
        }

        $baiTrongLop = $this->baiTrongLopService->layBaiTrongLop($id_lop_hoc_phan, $baiTap->bai->id);
        if ($baiTrongLop != null && $baiTrongLop->hoan_thanh_khi) {
            $this->tienDoHocTapService->danhDauHoanThanh($thanhVienLop->id, $baiTrongLop->id);
        }

        return view('modules.lop-hoc.thong-bao', [
            'thanhCong' => true,
            'soCauDung' => $ketQua['data']->so_cau_dung,
            'lop' => $lopHocPhan
        ]);
    }
}
