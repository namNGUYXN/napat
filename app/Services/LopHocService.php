<?php

namespace App\Services;

use App\BaiGiangLop;
use App\LopHocPhan;
use App\ThanhVienLop;

class LopHocService
{
    public function getLopHocCuaToi($nguoiDung)
    {
        if ($nguoiDung != null) {
            $idLopHoc = ThanhVienLop::where('id_nguoi_dung', $nguoiDung->id)
                ->pluck('id_lop_hoc_phan');
            return LopHocPhan::with(['hoc_phan', 'giang_vien'])
                ->whereIn('id', $idLopHoc)
                ->where('is_delete', false)
                ->get();
        }
        return collect();
    }
    public function layChiTietLopHoc($slug)
    {
        return LopHocPhan::with([
            'hoc_phan',
            'giang_vien',
        ])
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
