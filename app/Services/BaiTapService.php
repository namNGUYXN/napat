<?php

namespace App\Services;

use App\BaiTap;
use App\CauHoiBaiTap;
use App\ThanhVienLop;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\ChiTietLamBaiTap;
use App\KetQuaBaiTap;
use Carbon\Carbon;

class BaiTapService
{
    // Lấy tất cả bài tập (chưa bị xóa)
    public function getAll()
    {
        return BaiTap::where('is_delete', false)->get();
    }

    // Lấy bài tập theo ID
    public function getById($id)
    {
        return BaiTap::with('list_cau_hoi')
            ->where('id', $id)
            ->where('is_delete', false)->first();
    }

    // Lấy danh sách bài tập theo id bài giảng
    public function getByBaiGiangId($id_bai_giang)
    {
        return BaiTap::with('list_cau_hoi') // eager load quan hệ câu hỏi
            ->where('id_bai', $id_bai_giang)
            ->where('is_delete', false)
            ->get();
    }

    // Tạo mới bài tập
    public function create($data)
    {
        return BaiTap::create([
            'tieu_de' => $data['tieu_de'],
            'slug' => Str::slug($data['tieu_de']) . '-' . uniqid(),
            'diem_toi_da' => $data['diem_toi_da'],
            'id_bai_giang' => $data['id_bai_giang'],
        ]);
    }

    // Cập nhật bài tập
    public function update($id, $data)
    {
        $baiTap = BaiTap::where('id', $id)->where('is_delete', false)->first();
        if (!$baiTap) {
            return null;
        }

        $baiTap->tieu_de = $data['tieu_de'];
        $baiTap->slug = Str::slug($data['tieu_de']) . '-' . uniqid();
        $baiTap->diem_toi_da = $data['diem_toi_da'];
        $baiTap->id_bai_giang = $data['id_bai_giang'];
        $baiTap->save();

        return $baiTap;
    }

    // Xóa mềm bài tập
    public function softDelete($id)
    {
        $baiTap = BaiTap::where('id', $id)->first();
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
            $baiTap = BaiTap::create([
                'tieu_de' => $data['tieuDe'],
                'slug' => Str::slug($data['tieuDe']),
                'diem_toi_da' => $data['diemToiDa'] ?? null,
                'id_bai' => $data['idBaiGiang'],
                'is_delete' => false
            ]);

            foreach ($data['danhSachCauHoi'] as $q) {
                CauHoiBaiTap::create([
                    'id_bai_tap' => $baiTap->id,
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
    
    public function layChiTietTheoVaiTro($idBaiTap, $idLop, $idNguoiDung, $vaiTro)
    {
        if ($vaiTro == "Sinh viên") {
            return $this->layChiTietChoSinhVien($idBaiTap, $idLop, $idNguoiDung);
        }
        if ($vaiTro == "Giảng viên") {
            return $this->layChiTietChoGiangVien($idBaiTap, $idLop);
        }
        throw new \Exception("Vai trò không hợp lệ");
    }

    public function layChiTietChoSinhVien($idBaiTap, $lop, $idNguoiDung)
    {
        $baiTap = BaiTap::with('list_cau_hoi')->findOrFail($idBaiTap);

        $thanhVienLop = ThanhVienLop::where('id_nguoi_dung', $idNguoiDung)
            ->where('id_lop_hoc_phan', $lop)
            ->where('is_accept', true)
            ->first();

        if (!$thanhVienLop) {
            throw new \Exception("Sinh viên không thuộc lớp này");
        }

        // Lấy kết quả bài kiểm tra nếu có
        $ketQua = KetQuaBaiTap::where([
            ['id_bai_tap', $baiTap->id],
            ['id_thanh_vien_lop', $thanhVienLop->id],
        ])->first();

        $chiTiet = null;

        if ($ketQua) {
            $chiTiet = $this->layChiTietLamBai($ketQua->id);
        }

        return [
            'role' => 'sinh_vien',
            'bai_tap' => $baiTap,
            'ket_qua' => $ketQua,
            'chi_tiet' => $chiTiet,
        ];
    }

    public function layChiTietChoGiangVien($idBaiTap, $lop)
    {
        $baiTap = BaiTap::with('list_cau_hoi')->findOrFail($idBaiTap);

        // Lấy danh sách tất cả thành viên lớp trong lớp học phần của bài kiểm tra
        $danhSachThanhVien = ThanhVienLop::with('nguoi_dung')
            ->where('id_lop_hoc_phan', $lop)
            ->where('is_accept', true)
            ->get();

        $danhSachKetQua = [];

        foreach ($danhSachThanhVien as $thanhVien) {
            $ketQua = KetQuaBaiTap::where([
                ['id_bai_tap', $baiTap->id],
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
            'bai_tap' => $baiTap,
            'danh_sach_ket_qua' => $danhSachKetQua
        ];
    }

    private function layChiTietLamBai($idKetQua)
    {
        $dsChiTiet = ChiTietLamBaiTap::with('cau_hoi')
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

    public function kiemTraDaNopBai(int $idBaiTap, int $idThanhVienLop): array
    {
        $daNop = KetQuaBaiTap::where('id_bai_tap', $idBaiTap)
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

    public function nopBai(int $idBaiTap, int $idThanhVienLop, array $answers): array
    {
        try {
            $ketQua = DB::transaction(function () use ($idBaiTap, $idThanhVienLop, $answers) {
                $baiTap = BaiTap::with('list_cau_hoi')->findOrFail($idBaiTap);
                $danhSachCauHoi = $baiTap->list_cau_hoi;
                $soCauDung = 0;

                $ketQua = KetQuaBaiTap::create([
                    'id_thanh_vien_lop' => $idThanhVienLop,
                    'id_bai_tap' => $idBaiTap,
                    'ngay_lam' => Carbon::now(),
                    'so_cau_dung' => 0,
                ]);

                foreach ($danhSachCauHoi as $cauHoi) {
                    $dapAnChon = $answers[$cauHoi->id] ?? null;
                    $chonDung = $dapAnChon && $dapAnChon === $cauHoi->dap_an_dung;

                    if ($chonDung) {
                        $soCauDung++;
                    }

                    ChiTietLamBaiTap::create([
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
}
