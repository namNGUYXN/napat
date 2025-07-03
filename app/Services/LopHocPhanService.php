<?php

namespace App\Services;

use App\BaiGiangLop;
use App\LopHocPhan;
use App\ThanhVienLop;

class LopHocPhanService
{
    public function layTheoId($id)
    {
        return LopHocPhan::findOrFail($id);
    }

    public function layTheoSlug($slug)
    {
        return LopHocPhan::where('slug', $slug)->firstOrFail();
    }

    public function getLopHocCuaToi($idNguoiDung)
    {
        if ($idNguoiDung != null) {
            $idLopHoc = ThanhVienLop::where('id_nguoi_dung', $idNguoiDung)
                ->where(function ($query) {
                    $query->where('is_accept', true)
                        ->orWhereNull('is_accept');
                })
                ->pluck('id_lop_hoc_phan');

            return LopHocPhan::with(['giang_vien'])
                ->whereIn('id', $idLopHoc)
                ->where('is_delete', false)
                ->get();
        }
        return collect();
    }

    public function layChiTietLopHoc($slug)
    {
        return LopHocPhan::with(['giang_vien'])
            ->where('slug', $slug)
            ->where('is_delete', false)
            ->firstOrFail();
    }

    // public function layListBaiGiangTrongLop($id)
    // {
    //     $lopHoc = LopHoc::with('bai_giang_lop.bai_giang', 'bai_giang_lop.chuong')->find($id);

    //     return $lopHoc;
    // }

    // public function layListBaiGiangTheoChuongTrongLop($idLopHoc, $idChuong)
    // {
    //     // $lopHoc = LopHoc::with('bai_giang_lop.bai_giang', 'bai_giang_lop.chuong')->find($id);
    //     $listBaiGiang = BaiGiangLop::where([
    //         ['id_lop_hoc', $idLopHoc],
    //         ['id_chuong', $idChuong]
    //     ])->get();

    //     return $listBaiGiang;
    // }

    // public function layLopHocTheoHocPhan($id)
    // {
    //     return LopHoc::with([
    //             'hoc_phan',
    //             'giang_vien',
    //         ])
    //         ->where('id_hoc_phan', $id)
    //         ->where('is_delete', false)
    //         ->get();
    // }

}
