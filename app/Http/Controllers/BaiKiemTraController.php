<?php

namespace App\Http\Controllers;

use App\BaiKiemTra;
use App\Services\BaiKiemTraService;
use App\Services\ThanhVienLopService;
use App\Services\NguoiDungService;
use Facade\FlareClient\Http\Exceptions\NotFound;
use Illuminate\Support\Facades\Validator;
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

    //Trả về giao diện làm bài kiểm tra
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

    //Dữ liệu danh sách bài kiểm tra theo id của lớp học
    public function danhSachBaiKiemTra($id)
    {
        $baiKiemTra = $this->baiKiemTraService->getByLopHocIdWithCauHoi($id);
        return response()->json($baiKiemTra);
    }

    //Dữ liệu chi tiết bài kiểm tra theo id bài kiểm tra
    public function layChiTiet($id)
    {
        $idNguoiDung = session('id_nguoi_dung');
        $vaiTro = session('vai_tro');

        $result = $this->baiKiemTraService->layChiTietTheoVaiTro($id, $idNguoiDung, $vaiTro);

        return response()->json($result);
    }

    //Tạo bài kiểm tra mới
    public function themBaiKiemTra(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'tieuDe' => ['required', 'string'],
                'diemToiDa' => ['required', 'integer', 'between:0,100'],
                'thoiGianBatDau' => ['required', 'date_format:d/m/Y H:i'],
                'thoiGianKetThuc' => ['required', 'date_format:d/m/Y H:i'],
                'choPhepNopTre' => ['required', 'boolean'],
                'idLopHoc' => ['required', 'integer'],
            ],
            [
                'tieuDe.required' => 'Vui lòng nhập tiêu đề cho bài kiểm tra.',
                'tieuDe.string' => 'Tiêu đề phải là chuỗi ký tự.',

                'diemToiDa.required' => 'Vui lòng nhập điểm tối đa.',
                'diemToiDa.integer' => 'Điểm tối đa phải là số nguyên.',
                'diemToiDa.between' => 'Điểm tối đa phải nằm trong khoảng từ 0 đến 100.',

                'thoiGianBatDau.required' => 'Vui lòng chọn thời gian bắt đầu.',
                'thoiGianBatDau.date_format' => 'Thời gian bắt đầu không đúng định dạng (dd/mm/yyyy hh:mm).',

                'thoiGianKetThuc.required' => 'Vui lòng chọn thời gian kết thúc.',
                'thoiGianKetThuc.date_format' => 'Thời gian kết thúc không đúng định dạng (dd/mm/yyyy hh:mm).',

                'choPhepNopTre.required' => 'Vui lòng chọn trạng thái nộp trễ.',
                'choPhepNopTre.boolean' => 'Trường nộp trễ phải là true hoặc false.',

                'idLopHoc.required' => 'Thiếu thông tin lớp học.',
                'idLopHoc.integer' => 'ID lớp học không hợp lệ.',
            ],
        );
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ]);
        }

        $kiemTra = $this->baiKiemTraService->kiemTraTieuDe(
            $request->input('tieuDe'),
            $request->input('idLopHoc'),
        );

        if ($kiemTra['ton_tai']) {
            return response()->json([
                'success' => false,
                'error' => 'tieu_de',
                'message' => 'Lớp học đã có bài kiểm tra với tiêu đề này rồi!',
                'danh_sach_tieu_de' => $kiemTra['danh_sach_tieu_de'],
            ]);
        }

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

    //Lưu lại kết quả làm bài của sinh viên
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

    //Cập nhật thông tin bài kiểm tra
    public function capNhatBaiKiemTra(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:bai_kiem_tra,id',
            'tieu_de' => 'required|string|max:255',
            'diem_toi_da' => 'required|numeric|min:0',
            'ngay_bat_dau' => 'required|date_format:d-m-Y H:i:s',
            'ngay_ket_thuc' => 'required|date_format:d-m-Y H:i:s|after:ngay_bat_dau',
            'cho_phep_nop_qua_han' => 'required|boolean',
        ], [
            'id.required' => 'Thiếu ID bài kiểm tra.',
            'id.exists' => 'Bài kiểm tra không tồn tại.',

            'tieu_de.required' => 'Vui lòng nhập tiêu đề.',
            'tieu_de.string' => 'Tiêu đề không hợp lệ.',
            'tieu_de.max' => 'Tiêu đề không được vượt quá 255 ký tự.',

            'diem_toi_da.required' => 'Vui lòng nhập điểm tối đa.',
            'diem_toi_da.numeric' => 'Điểm tối đa phải là số.',
            'diem_toi_da.min' => 'Điểm tối đa không được âm.',

            'ngay_bat_dau.required' => 'Vui lòng nhập ngày bắt đầu.',
            'ngay_bat_dau.date_format' => 'Ngày bắt đầu phải đúng định dạng dd-mm-YYYY HH:ii:ss.',

            'ngay_ket_thuc.required' => 'Vui lòng nhập ngày kết thúc.',
            'ngay_ket_thuc.date_format' => 'Ngày kết thúc phải đúng định dạng dd-mm-YYYY HH:ii:ss.',
            'ngay_ket_thuc.after' => 'Ngày kết thúc phải sau ngày bắt đầu.',

            'cho_phep_nop_qua_han.required' => 'Vui lòng chọn cho phép nộp quá hạn hay không.',
            'cho_phep_nop_qua_han.boolean' => 'Giá trị cho phép nộp quá hạn không hợp lệ.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(), // <-- gửi mảng lỗi về
            ], 422);
        }

        $baiKiemTraHienTai = $this->baiKiemTraService->getById($request['id']);

        $kiemTra = $this->baiKiemTraService->kiemTraTieuDe(
            $request['tieu_de'],
            $baiKiemTraHienTai->id_lop_hoc_phan,
            $baiKiemTraHienTai->id
        );

        if ($kiemTra['ton_tai']) {
            return response()->json([
                'success' => false,
                'error' => 'tieu_de',
                'message' => 'Lớp học đã có bài kiểm tra với tiêu đề này rồi!',
                'danh_sach_tieu_de' => $kiemTra['danh_sach_tieu_de'],
            ]);
        }

        try {
            $this->baiKiemTraService->capNhatBaiKiemTra($request->all());
            $baiKiemTra = $this->baiKiemTraService->getById($request['id']);
            return response()->json(['success' => true, 'data' => $baiKiemTra]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
