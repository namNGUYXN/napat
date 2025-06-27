<?php

namespace App\Http\Controllers;

use App\BaiKiemTra;
use App\Services\BaiKiemTraService;
use App\Services\ThanhVienLopService;
use App\Services\NguoiDungService;
use Facade\FlareClient\Http\Exceptions\NotFound;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BaiKiemTraController extends Controller
{
    protected $baiKiemTraService;
    protected $nguoiDungService;
    protected $thanhVienLopService;
    public function __construct(
        BaiKiemTraService $baiKiemTraService,
        NguoiDungService $nguoiDungService,
        ThanhVienLopService $thanhVienLopService
    ) {
        $this->baiKiemTraService = $baiKiemTraService;
        $this->nguoiDungService = $nguoiDungService;
        $this->thanhVienLopService = $thanhVienLopService;
    }
    function lamBai($id)
    {
        $idNguoiDung = session('id_nguoi_dung');
        $baiKiemTra = $this->baiKiemTraService->getById($id);
        $thanhVienLop = $this->thanhVienLopService->layTheoLopVaNguoiDung(
            $baiKiemTra->id_lop_hoc_phan,
            $idNguoiDung
        );

        if ($thanhVienLop === null) {
            return view('modules.lop-hoc.thong-bao-nop-bai', [
                'thanhCong' => false,
                'thongBao' => "Bạn không có quyền thực hiện yêu cầu này!!!",
                'lop' => $baiKiemTra->lop_hoc_phan
            ]);
        }
        return view('modules.lop-hoc.lam-bai', compact('baiKiemTra'));
    }

    public function danhSachBaiKiemTra($id)
    {
        $baiKiemTra = $this->baiKiemTraService->getByLopHocIdWithCauHoi($id);
        return response()->json($baiKiemTra);
    }

    public function layChiTiet($id)
    {
        $idNguoiDung = session('id_nguoi_dung');
        $vaiTro = session('vai_tro');

        $result = $this->baiKiemTraService->layChiTietTheoVaiTro($id, $idNguoiDung, $vaiTro);

        return response()->json($result);
    }
    public function themBaiKiemTra(Request $request)
    {
        
        try {
            // Gọi hàm tạo bài kiểm tra — nếu lỗi sẽ bị bắt ở catch
            $this->baiKiemTraService->createExercise($request->all());

            // Chỉ đến đây khi thêm thành công
            $lopHocId = $request->input('idLopHoc');
            $baiKiemTra = $this->baiKiemTraService->getByLopHocId($lopHocId);

            return response()->json([
                'success' => true,
                'message' => 'Tạo bài kiểm tra thành công',
                'data' => $baiKiemTra
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Tạo bài tập thất bại: ' . $e->getMessage()
            ], 500);
        }
    }

    public function nopBai(Request $request)
    {
        $request->validate([
            'id_bai_kiem_tra' => 'required|exists:bai_kiem_tra,id',
            'answers' => 'required|array',
        ]);

        $idBaiKiemTra = $request->input('id_bai_kiem_tra');
        $answers = $request->input('answers');

        $baiKiemTra = $this->baiKiemTraService->getById($idBaiKiemTra);

        $nguoiDung = $this->nguoiDungService->layTheoId(session('id_nguoi_dung'));

        $thanhVienLop = $this->thanhVienLopService->layTheoLopVaNguoiDung(
            $baiKiemTra->id_lop_hoc_phan,
            $nguoiDung->id
        );

        if ($thanhVienLop === null) {
            return view('modules.lop-hoc.thong-bao-nop-bai', [
                'thanhCong' => false,
                'thongBao' => "Bạn không có quyền thực hiện yêu cầu này!!!",
                'lop' => $baiKiemTra->lop_hoc_phan
            ]);
        }

        // Kiểm tra trước khi cho nộp bài
        $kiemTra = $this->baiKiemTraService->kiemTraDaNopBai($idBaiKiemTra, $thanhVienLop->id);

        if (!$kiemTra['success']) {
            return view('modules.lop-hoc.thong-bao-nop-bai', [
                'thanhCong' => false,
                'thongBao' => $kiemTra['message'],
                'lop' => $baiKiemTra->lop_hoc_phan
            ]);
        }

        $thoiGian = Carbon::now();
        if ($thoiGian > $baiKiemTra->ngay_ket_thuc) {
            if (!$this->baiKiemTraService->kiemTraNopQuaHan($baiKiemTra->id)) {
                return view('modules.lop-hoc.thong-bao-nop-bai', [
                    'thanhCong' => false,
                    'thongBao' => "Không thể nộp bài do đã quá hạn nộp!!!",
                    'lop' => $baiKiemTra->lop_hoc_phan
                ]);
            }
        }

        $ketQua = $this->baiKiemTraService->nopBai($idBaiKiemTra, $thanhVienLop->id, $answers);

        if (!$ketQua['success']) {
            return view('modules.lop-hoc.thong-bao-nop-bai', [
                'thanhCong' => false,
                'thongBao' => $ketQua['message'],
                'lop' => $baiKiemTra->lop_hoc_phan
            ]);
        }

        return view('modules.lop-hoc.thong-bao-nop-bai', [
            'thanhCong' => true,
            'soCauDung' => $ketQua['data']->so_cau_dung,
            'lop' => $baiKiemTra->lop_hoc_phan
        ]);
    }

    public function capNhatBaiKiemTra(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|exists:bai_kiem_tra,id',
            'tieu_de' => 'required|string|max:255',
            'diem_toi_da' => 'required|numeric|min:0',
            'cau_hoi_xoa' => 'array',
            'cau_hoi_cap_nhat' => 'array',
            'cau_hoi_moi' => 'array',
        ]);

        try {
            $this->baiKiemTraService->capNhatBaiKiemTra($validated);
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
