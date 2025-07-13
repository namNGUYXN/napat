<?php

namespace App\Services;

use App\BaiTrongLop;
use App\ThanhVienLop;
use App\TienDoHocTap;

class TienDoHocTapService
{
    public function tinhTienDoLopHoc(int $lopHocPhanId, int $nguoiDungId): array
    {
        // Lấy danh sách bài trong lớp kèm bài và chương
        $baiTrongLop = BaiTrongLop::with([
            'bai.chuong' => function ($query) {
                $query->orderBy('thu_tu');
            }
        ])
            ->where('id_lop_hoc_phan', $lopHocPhanId)
            ->where('cong_khai', true)
            ->get()
            ->sortBy(function ($baiTL) {
                return sprintf(
                    '%03d.%03d',
                    optional($baiTL->bai->chuong)->thu_tu ?? 999,
                    optional($baiTL->bai)->thu_tu ?? 999
                );
            })
            ->values();

        $soBaiCongKhai = $baiTrongLop->count();

        // Lấy ID bài trong lớp
        $idBaiTrongLop = $baiTrongLop->pluck('id');

        // Lấy tiến độ từng bài học
        $tienDoTheoBai = TienDoHocTap::whereIn('id_bai_trong_lop', $idBaiTrongLop)
            ->where('id_thanh_vien', $nguoiDungId)
            ->get()
            ->keyBy('id_bai_trong_lop');

        // Xử lý thông tin chi tiết từng bài
        $chiTietBai = $baiTrongLop->map(function ($baiTL) use ($tienDoTheoBai) {
            $tienDo = $tienDoTheoBai->get($baiTL->id);

            return [
                'ten_chuong' => optional($baiTL->bai)->chuong->tieu_de ?? 'Không có tiêu đề',
                'tieu_de' => optional($baiTL->bai)->tieu_de ?? 'Không có tiêu đề',
                'hoan_thanh_phan_tram' => $tienDo->muc_do_hoan_thanh ?? 0,
                'da_hoan_thanh' => $tienDo->da_hoan_thanh ?? false,
            ];
        });

        // Tính số bài đã hoàn thành
        $soBaiHoanThanh = $chiTietBai->where('da_hoan_thanh', true)->count();

        // Tính phần trăm tiến độ tổng thể
        $tiendoPhanTram = $soBaiCongKhai > 0
            ? round(($soBaiHoanThanh / $soBaiCongKhai) * 100)
            : 0;

        // Nhóm chi tiết bài theo chương
        $chiTietTheoChuong = $chiTietBai
            ->groupBy('ten_chuong')
            ->map(function ($items) {
                return $items->values()->toArray(); 
            })
            ->toArray();

        return [
            'so_bai_cong_khai' => $soBaiCongKhai,
            'so_bai_hoan_thanh' => $soBaiHoanThanh,
            'tiendo_phan_tram' => $tiendoPhanTram,
            'chi_tiet' => $chiTietTheoChuong, 
        ];
    }

    public function tinhTienDoTheoBaiTrongLop(int $lopHocPhanId): array
    {
        // Lấy danh sách sinh viên
        $sinhVienIds = ThanhVienLop::where('id_lop_hoc_phan', $lopHocPhanId)
            ->where('is_accept', true)
            ->pluck('id');

        $tongSoSinhVien = $sinhVienIds->count();

        // Lấy danh sách bài trong lớp kèm bài và chương
        $baiTrongLop = BaiTrongLop::with([
            'bai.chuong' => function ($query) {
                $query->orderBy('thu_tu');
            }
        ])
            ->where('id_lop_hoc_phan', $lopHocPhanId)
            ->where('cong_khai', true)
            ->get()
            ->sortBy(function ($baiTL) {
                return sprintf(
                    '%03d.%03d',
                    optional($baiTL->bai->chuong)->thu_tu ?? 999,
                    optional($baiTL->bai)->thu_tu ?? 999
                );
            })
            ->values();

        // Lấy tiến độ học tập
        $tienDoHocTap = TienDoHocTap::whereIn('id_bai_trong_lop', $baiTrongLop->pluck('id'))
            ->whereIn('id_thanh_vien', $sinhVienIds)
            ->where('da_hoan_thanh', true)
            ->get();

        $tienDoTheoBai = $tienDoHocTap->groupBy('id_bai_trong_lop');

        // Thống kê từng bài
        $thongKe = $baiTrongLop->map(function ($baiTL) use ($tienDoTheoBai, $tongSoSinhVien) {
            $tienDo = $tienDoTheoBai->get($baiTL->id);
            $daHoanThanh = $tienDo ? $tienDo->count() : 0;
            $tiLe = $tongSoSinhVien > 0 ? round(($daHoanThanh / $tongSoSinhVien) * 100) : 0;

            return [
                'id' => $baiTL->id,
                'tieu_de' => optional($baiTL->bai)->tieu_de ?? 'Không có tiêu đề',
                'ten_chuong' => optional($baiTL->bai->chuong)->tieu_de ?? 'Chưa phân chương',
                'thu_tu_chuong' => optional($baiTL->bai->chuong)->thu_tu ?? 999,
                'thu_tu_bai' => optional($baiTL->bai)->thu_tu ?? 999,
                'so_sinh_vien_da_hoan_thanh' => $daHoanThanh,
                'tong_so_sinh_vien' => $tongSoSinhVien,
                'ti_le_hoan_thanh' => $tiLe
            ];
        });
        return $thongKe->toArray();
    }

    public function layChiTietSinhVien($id)
    {
        $baiTrongLop = BaiTrongLop::findOrFail($id);

        $lopHocPhanId = $baiTrongLop->id_lop_hoc_phan;

        // Lấy toàn bộ sinh viên trong lớp
        $sinhVienLop = ThanhVienLop::where('id_lop_hoc_phan', $lopHocPhanId)
            ->with('nguoi_dung')
            ->where('is_accept', true)
            ->get();

        $tienDo = TienDoHocTap::where('id_bai_trong_lop', $id)->pluck('id_thanh_vien')->toArray();

        $daHoc = $sinhVienLop->filter(function ($sv) use ($tienDo) {
            return in_array($sv->id, $tienDo);
        })->values();

        $chuaHoc = $sinhVienLop->filter(function ($sv) use ($tienDo) {
            return !in_array($sv->id, $tienDo);
        })->values();


        return [
            'da_hoc' => $daHoc,
            'chua_hoc' => $chuaHoc,
        ];
    }

    public function kiemTraTonTaiTienDoHocTap($idThanhVienLop, $idBaiTrongLop): bool
    {
        return TienDoHocTap::where('id_thanh_vien', $idThanhVienLop)
            ->where('id_bai_trong_lop', $idBaiTrongLop)
            ->exists();
    }

    public function danhDauHoanThanh($idThanhVienLop, $idBaiTrongLop): bool
    {
        $tonTai = TienDoHocTap::where('id_thanh_vien', $idThanhVienLop)
            ->where('id_bai_trong_lop', $idBaiTrongLop)
            ->exists();

        if (!$tonTai) {
            TienDoHocTap::create([
                'id_thanh_vien' => $idThanhVienLop,
                'id_bai_trong_lop' => $idBaiTrongLop,
                'da_hoan_thanh' => true,
                'thoi_gian_hoan_thanh' => now()
            ]);
        }

        return true;
    }
}
