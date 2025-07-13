<?php

namespace App\Services;

use App\ThanhVienLop;
use App\BaiKiemTra;
use App\CauHoiBaiKiemTra;
use App\KetQuaBaiKiemTra;
use App\ChiTietLamBaiKiemTra;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BaiKiemTraService
{
    // Lấy tất cả bài tập (chưa bị xóa)
    public function getAll()
    {
        return BaiKiemTra::where('is_delete', false)->get();
    }

    // Lấy bài kiểm tra theo ID
    public function getById($id)
    {
        return BaiKiemTra::with('list_cau_hoi')
            ->with('lop_hoc_phan')
            ->where('id', $id)
            ->where('is_delete', false)->first();
    }

    // Lấy danh sách bài tập theo id bài giảng
    public function getByLopHocIdWithCauHoi($id_lop_hoc_phan)
    {
        return BaiKiemTra::with('list_cau_hoi') // eager load quan hệ câu hỏi
            ->where('id_lop_hoc_phan', $id_lop_hoc_phan)
            ->where('is_delete', false)
            ->get();
    }

    // Lấy danh sách bài tập theo id bài giảng
    public function getByLopHocId($id_lop_hoc_phan)
    {
        return BaiKiemTra::where('id_lop_hoc_phan', $id_lop_hoc_phan)
            ->where('is_delete', false)
            ->get();
    }


    // Xóa mềm bài tập
    public function softDelete($id)
    {
        $baiTap = BaiKiemTra::where('id', $id)->first();
        if (!$baiTap) {
            return false;
        }

        $baiTap->is_delete = true;
        return $baiTap->save();
    }

    public function createExercise(array $data)
    {
        DB::beginTransaction();
        try {
            $baiKiemTra = BaiKiemTra::create([
                'tieu_de' => $data['tieuDe'],
                'slug' => '',
                'diem_toi_da' => $data['diemToiDa'] ?? null,
                'id_lop_hoc_phan' => $data['idLopHoc'],
                'ngay_bat_dau' => Carbon::createFromFormat('d/m/Y H:i', $data['thoiGianBatDau']),
                'ngay_ket_thuc' => Carbon::createFromFormat('d/m/Y H:i', $data['thoiGianKetThuc']),
                'cho_phep_nop_qua_han' => filter_var($data['choPhepNopTre'], FILTER_VALIDATE_BOOLEAN),
                'ngay_tao' => Carbon::now(),
                'is_delete' => false
            ]);

            $baiKiemTra->slug = Str::slug($data['tieuDe']) . '-' . $baiKiemTra->id;
            $baiKiemTra->save();

            foreach ($data['danhSachCauHoi'] as $q) {
                CauHoiBaiKiemTra::create([
                    'id_bai_kiem_tra' => $baiKiemTra->id,
                    'tieu_de' => $q['cauHoi'],
                    'dap_an_a' => $q['danhSachDapAn'][0] ?? '',
                    'dap_an_b' => $q['danhSachDapAn'][1] ?? '',
                    'dap_an_c' => $q['danhSachDapAn'][2] ?? '',
                    'dap_an_d' => $q['danhSachDapAn'][3] ?? '',
                    'dap_an_dung' => ['A', 'B', 'C', 'D'][$q['dapAnDuocChon']],
                ]);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e; // Để controller xử lý trả lỗi JSON
        }
    }

    public function kiemTraDaNopBai(int $idBaiKiemTra, int $idThanhVienLop): array
    {
        $daNop = KetQuaBaiKiemTra::where('id_bai_kiem_tra', $idBaiKiemTra)
            ->where('id_thanh_vien_lop', $idThanhVienLop)
            ->exists();

        if ($daNop) {
            return [
                'success' => false,
                'message' => 'Bạn đã nộp bài kiểm tra này trước đó.',
            ];
        }

        return [
            'success' => true,
        ];
    }

    public function nopBai(int $idBaiKiemTra, int $idThanhVienLop, array $answers): array
    {
        try {
            $ketQua = DB::transaction(function () use ($idBaiKiemTra, $idThanhVienLop, $answers) {
                $baiKiemTra = BaiKiemTra::with('list_cau_hoi')->findOrFail($idBaiKiemTra);
                $danhSachCauHoi = $baiKiemTra->list_cau_hoi;
                $soCauDung = 0;

                $ketQua = KetQuaBaiKiemTra::create([
                    'id_thanh_vien_lop' => $idThanhVienLop,
                    'id_bai_kiem_tra' => $idBaiKiemTra,
                    'ngay_lam' => Carbon::now(),
                    'nop_qua_han' => Carbon::now()->gt($baiKiemTra->ngay_ket_thuc),
                    'so_cau_dung' => 0,
                ]);

                foreach ($danhSachCauHoi as $cauHoi) {
                    $dapAnChon = $answers[$cauHoi->id] ?? null;
                    $chonDung = $dapAnChon && $dapAnChon === $cauHoi->dap_an_dung;

                    if ($chonDung) {
                        $soCauDung++;
                    }

                    ChiTietLamBaiKiemTra::create([
                        'id_ket_qua' => $ketQua->id,
                        'id_cau_hoi' => $cauHoi->id,
                        'dap_an_chon' => $dapAnChon ?? '',
                        'chon_dung' => $chonDung,
                    ]);
                }

                $ketQua->update(['so_cau_dung' => $soCauDung]);

                return $ketQua;
            });

            return [
                'success' => true,
                'message' => 'Nộp bài thành công.',
                'data' => $ketQua,
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Lỗi khi nộp bài: ' . $e->getMessage(),
                'error' => $e,
            ];
        }
    }

    public function layChiTietTheoVaiTro($idBaiKiemTra, $idNguoiDung, $vaiTro)
    {
        if ($vaiTro == "Sinh viên") {
            return $this->layChiTietChoSinhVien($idBaiKiemTra, $idNguoiDung);
        }
        if ($vaiTro == "Giảng viên") {
            return $this->layChiTietChoGiangVien($idBaiKiemTra);
        }
        throw new \Exception("Vai trò không hợp lệ");
    }


    public function layChiTietChoSinhVien($idBaiKiemTra, $idNguoiDung)
    {
        $baiKiemTra = BaiKiemTra::with('list_cau_hoi')->findOrFail($idBaiKiemTra);

        $thanhVienLop = ThanhVienLop::where('id_nguoi_dung', $idNguoiDung)
            ->where('id_lop_hoc_phan', $baiKiemTra->id_lop_hoc_phan)
            ->where('is_accept', true)
            ->first();

        if (!$thanhVienLop) {
            throw new \Exception("Sinh viên không thuộc lớp này");
        }

        // Lấy kết quả bài kiểm tra nếu có
        $ketQua = KetQuaBaiKiemTra::where([
            ['id_bai_kiem_tra', $baiKiemTra->id],
            ['id_thanh_vien_lop', $thanhVienLop->id],
        ])->first();

        $chiTiet = null;

        if ($ketQua) {
            $chiTiet = $this->layChiTietLamBai($ketQua->id);
        }

        return [
            'role' => 'sinh_vien',
            'bai_kiem_tra' => $baiKiemTra,
            'ket_qua' => $ketQua,
            'chi_tiet' => $chiTiet,
            'duoc_xem_ket_qua' => $baiKiemTra->cong_khai && $chiTiet != null,
        ];
    }

    public function layChiTietChoGiangVienTemp($idBaiKiemTra)
    {
        $baiKiemTra = BaiKiemTra::with('list_cau_hoi')->findOrFail($idBaiKiemTra);

        // Lấy danh sách tất cả thành viên lớp trong lớp học phần của bài kiểm tra
        $danhSachThanhVien = ThanhVienLop::with('nguoi_dung')
            ->where('id_lop_hoc_phan', $baiKiemTra->id_lop_hoc_phan)
            ->where('is_accept', true)
            ->get();

        $danhSachKetQua = [];

        foreach ($danhSachThanhVien as $thanhVien) {
            $ketQua = KetQuaBaiKiemTra::where([
                ['id_bai_kiem_tra', $baiKiemTra->id],
                ['id_thanh_vien_lop', $thanhVien->id],
            ])->first();

            $chiTiet = null;
            $diem = null;

            if ($ketQua) {
                $chiTiet = $this->layChiTietLamBai($ketQua->id);

                // Chỉ tính điểm nếu có chi tiết làm bài (đã chọn đáp án)
                if (!empty($chiTiet['cauHoiVaDapAn'])) {
                    $tongCau = count($chiTiet['cauHoiVaDapAn']);
                    $soCauDung = 0;

                    foreach ($chiTiet['cauHoiVaDapAn'] as $item) {
                        foreach ($item['danh_sach_dap_an'] as $dapAn) {
                            if ($dapAn['la_dap_an_dung'] && $dapAn['duoc_chon']) {
                                $soCauDung++;
                            }
                        }
                    }

                    $diem = $tongCau > 0 ? round(($soCauDung / $tongCau) * 10, 2) : null;
                }
            }

            $danhSachKetQua[] = [
                'sinh_vien' => [
                    'id' => $thanhVien->nguoi_dung->id,
                    'ten' => $thanhVien->nguoi_dung->ho_ten,
                    'email' => $thanhVien->nguoi_dung->email,
                ],
                'ket_qua' => $ketQua,
                'diem' => $diem,
                'chi_tiet' => $chiTiet
            ];
        }


        return [
            'role' => 'giang_vien',
            'bai_kiem_tra' => $baiKiemTra,
            'danh_sach_ket_qua' => $danhSachKetQua
        ];
    }

    public function layChiTietChoGiangVien($idBaiKiemTra)
    {
        $baiKiemTra = BaiKiemTra::with('list_cau_hoi')->findOrFail($idBaiKiemTra);

        $danhSachThanhVien = ThanhVienLop::with('nguoi_dung')
            ->where('id_lop_hoc_phan', $baiKiemTra->id_lop_hoc_phan)
            ->where('is_accept', true)
            ->get();

        $danhSachKetQua = [];
        $thongKeCauHoi = []; // <-- thêm mảng thống kê

        foreach ($danhSachThanhVien as $thanhVien) {
            $ketQua = KetQuaBaiKiemTra::where([
                ['id_bai_kiem_tra', $baiKiemTra->id],
                ['id_thanh_vien_lop', $thanhVien->id],
            ])->first();

            $chiTiet = null;
            $diem = null;

            if ($ketQua) {
                $chiTiet = $this->layChiTietLamBai($ketQua->id);

                if (!empty($chiTiet['cauHoiVaDapAn'])) {
                    $tongCau = count($chiTiet['cauHoiVaDapAn']);
                    $soCauDung = 0;

                    foreach ($chiTiet['cauHoiVaDapAn'] as $index => $item) {
                        $idCauHoi = $item['id'];
                        $daTraLoi = false;
                        $dung = false;

                        foreach ($item['danh_sach_dap_an'] as $dapAn) {
                            if ($dapAn['duoc_chon']) {
                                $daTraLoi = true;
                            }

                            if ($dapAn['la_dap_an_dung'] && $dapAn['duoc_chon']) {
                                $soCauDung++;
                                $dung = true;
                            }
                        }

                        // Khởi tạo mảng thống kê nếu chưa có
                        if (!isset($thongKeCauHoi[$idCauHoi])) {
                            $thongKeCauHoi[$idCauHoi] = [
                                'id' => $item['id'],
                                'cau_hoi' => $item['noi_dung'],
                                'so_nguoi_dung' => 0,
                                'so_dung' => 0,
                                'so_sai' => 0,
                                'so_khong_tra_loi' => 0,
                            ];
                        }

                        $thongKeCauHoi[$idCauHoi]['so_nguoi_dung']++;

                        if (!$daTraLoi) {
                            $thongKeCauHoi[$idCauHoi]['so_khong_tra_loi']++;
                        } elseif ($dung) {
                            $thongKeCauHoi[$idCauHoi]['so_dung']++;
                        } else {
                            $thongKeCauHoi[$idCauHoi]['so_sai']++;
                        }
                    }

                    $diem = $tongCau > 0 ? round(($soCauDung / $tongCau) * 10, 2) : null;
                }
            }

            $danhSachKetQua[] = [
                'sinh_vien' => [
                    'id' => $thanhVien->nguoi_dung->id,
                    'ten' => $thanhVien->nguoi_dung->ho_ten,
                    'email' => $thanhVien->nguoi_dung->email,
                ],
                'ket_qua' => $ketQua,
                'diem' => $diem,
                'chi_tiet' => $chiTiet
            ];
        }

        // Tính tỉ lệ %
        foreach ($thongKeCauHoi as $id => &$item) {
            $tong = $item['so_nguoi_dung'];
            if ($tong > 0) {
                $item['ti_le_dung'] = round($item['so_dung'] / $tong * 100, 1);
                $item['ti_le_sai'] = round($item['so_sai'] / $tong * 100, 1);
                $item['ti_le_khong_tra_loi'] = round($item['so_khong_tra_loi'] / $tong * 100, 1);
            } else {
                $item['ti_le_dung'] = 0;
                $item['ti_le_sai'] = 0;
                $item['ti_le_khong_tra_loi'] = 0;
            }
        }

        return [
            'role' => 'giang_vien',
            'bai_kiem_tra' => $baiKiemTra,
            'danh_sach_ket_qua' => $danhSachKetQua,
            'thong_ke_cau_hoi' => array_values($thongKeCauHoi) // Để trả về dạng danh sách
        ];
    }

    private function layChiTietLamBai($idKetQua)
    {
        $dsChiTiet = ChiTietLamBaiKiemTra::with('cau_hoi')
            ->where('id_ket_qua', $idKetQua)
            ->get();

        $result = [];

        foreach ($dsChiTiet as $item) {
            $cauHoi = $item->cau_hoi;

            $result[] = [
                'id' => $cauHoi->id,
                'noi_dung' => $cauHoi->tieu_de,
                'cau_hoi' => $cauHoi->tieu_de,
                'danh_sach_dap_an' => [
                    ['ma' => 'A', 'noi_dung' => $cauHoi->dap_an_a, 'la_dap_an_dung' => $cauHoi->dap_an_dung === 'A', 'duoc_chon' => $item->dap_an_chon === 'A'],
                    ['ma' => 'B', 'noi_dung' => $cauHoi->dap_an_b, 'la_dap_an_dung' => $cauHoi->dap_an_dung === 'B', 'duoc_chon' => $item->dap_an_chon === 'B'],
                    ['ma' => 'C', 'noi_dung' => $cauHoi->dap_an_c, 'la_dap_an_dung' => $cauHoi->dap_an_dung === 'C', 'duoc_chon' => $item->dap_an_chon === 'C'],
                    ['ma' => 'D', 'noi_dung' => $cauHoi->dap_an_d, 'la_dap_an_dung' => $cauHoi->dap_an_dung === 'D', 'duoc_chon' => $item->dap_an_chon === 'D'],
                ]
            ];
        }

        return ['cauHoiVaDapAn' => $result];
    }


    private function layChiTietLamBaiTemp($idKetQua)
    {
        $dsChiTiet = ChiTietLamBaiKiemTra::with('cau_hoi')
            ->where('id_ket_qua', $idKetQua)
            ->get();

        $result = [];

        foreach ($dsChiTiet as $item) {
            $cauHoi = $item->cau_hoi;

            $result[] = [
                'cau_hoi' => $cauHoi->tieu_de,
                'danh_sach_dap_an' => [
                    ['ma' => 'A', 'noi_dung' => $cauHoi->dap_an_a, 'la_dap_an_dung' => $cauHoi->dap_an_dung === 'A', 'duoc_chon' => $item->dap_an_chon === 'A'],
                    ['ma' => 'B', 'noi_dung' => $cauHoi->dap_an_b, 'la_dap_an_dung' => $cauHoi->dap_an_dung === 'B', 'duoc_chon' => $item->dap_an_chon === 'B'],
                    ['ma' => 'C', 'noi_dung' => $cauHoi->dap_an_c, 'la_dap_an_dung' => $cauHoi->dap_an_dung === 'C', 'duoc_chon' => $item->dap_an_chon === 'C'],
                    ['ma' => 'D', 'noi_dung' => $cauHoi->dap_an_d, 'la_dap_an_dung' => $cauHoi->dap_an_dung === 'D', 'duoc_chon' => $item->dap_an_chon === 'D'],
                ]
            ];
        }

        return ['cauHoiVaDapAn' => $result];
    }

    public function capNhatBaiKiemTra($data)
    {
        DB::transaction(function () use ($data) {
            // Cập nhật bài kiểm tra
            $bai = BaiKiemTra::findOrFail($data['id']);
            // Trường hợp chỉ cho phép cập nhật ngày kết thúc và cho phép nộp trễ
            if (!empty($data['__cap_nhat_gioi_han__'])) {
                $bai->update([
                    'ngay_ket_thuc' => Carbon::createFromFormat('d-m-Y H:i', $data['ngay_ket_thuc']),
                    'cho_phep_nop_qua_han' => $data['cho_phep_nop_qua_han'],
                ]);
                return;
            }

            $bai->update([
                'tieu_de' => $data['tieu_de'],
                'diem_toi_da' => $data['diem_toi_da'],
                'slug' => Str::slug($data['tieu_de']) . '-' . $bai->id,
                'ngay_bat_dau' => Carbon::createFromFormat('d-m-Y H:i', $data['ngay_bat_dau']),
                'ngay_ket_thuc' => Carbon::createFromFormat('d-m-Y H:i', $data['ngay_ket_thuc']),
                'cho_phep_nop_qua_han' => $data['cho_phep_nop_qua_han'],
            ]);

            // Xóa câu hỏi cũ nếu câu hỏi bị xóa trên giao diện 
            if (!empty($data['cau_hoi_xoa'])) {
                CauHoiBaiKiemTra::whereIn('id', $data['cau_hoi_xoa'])->delete();
            }

            // Cập nhật câu hỏi cũ
            if (!empty($data['cau_hoi_cap_nhat'])) {
                foreach ($data['cau_hoi_cap_nhat'] as $q) {
                    $cauHoi = CauHoiBaiKiemTra::find($q['id']);
                    if ($cauHoi) {
                        $cauHoi->update([
                            'tieu_de' => $q['tieu_de'],
                            'dap_an_a' => $q['dap_an_a'],
                            'dap_an_b' => $q['dap_an_b'],
                            'dap_an_c' => $q['dap_an_c'],
                            'dap_an_d' => $q['dap_an_d'],
                            'dap_an_dung' => $q['dap_an_dung'],
                        ]);
                    }
                }
            }

            // Thêm câu hỏi mới nếu có
            if (!empty($data['cau_hoi_moi'])) {
                foreach ($data['cau_hoi_moi'] as $q) {
                    CauHoiBaiKiemTra::create([
                        'id_bai_kiem_tra' => $bai->id,
                        'tieu_de' => $q['tieu_de'],
                        'dap_an_a' => $q['dap_an_a'],
                        'dap_an_b' => $q['dap_an_b'],
                        'dap_an_c' => $q['dap_an_c'],
                        'dap_an_d' => $q['dap_an_d'],
                        'dap_an_dung' => $q['dap_an_dung'],
                    ]);
                }
            }
        });
    }

    public function kiemTraNopQuaHan($idBaiKiemTra)
    {
        $baiKiemTra = BaiKiemTra::with('list_cau_hoi')->findOrFail($idBaiKiemTra);

        if ($baiKiemTra->cho_phep_nop_qua_han == true) {
            return true;
        }
        return false;
    }

    public function kiemTraTieuDe(string $tieuDe, int $lopHocID, ?int $baiKiemTraId = null): array
    {
        $query = BaiKiemTra::where('tieu_de', $tieuDe)
            ->where('id_lop_hoc_phan', $lopHocID)
            ->where('is_delete', 0);

        if ($baiKiemTraId) {
            $query->where('id', '!=', $baiKiemTraId);
        }

        $tonTai = $query->exists();

        // Trả về danh sách tiêu đề của lớp học (không bị xóa)
        $danhSachTieuDe = BaiKiemTra::where('id_lop_hoc_phan', $lopHocID)
            ->where('is_delete', 0)
            ->pluck('tieu_de')
            ->toArray();

        return [
            'ton_tai' => $tonTai,
            'danh_sach_tieu_de' => $danhSachTieuDe,
        ];
    }

    public function congKhaiKetQua($id)
    {
        $baiKiemTra = BaiKiemTra::findOrFail($id);
        $baiKiemTra->cong_khai = true;
        $baiKiemTra->save();

        return $baiKiemTra;
    }
}
