<?php
namespace App\Services;

use App\LopHoc;

class LopHocService
{
    public function getLopHocCuaToi($nguoiDung)
    {
        if ($nguoiDung->vai_tro === 'Giảng viên') {
            return LopHoc::with(['hoc_phan', 'giang_vien'])
                        ->where('id_giang_vien', $nguoiDung->id)
                        ->where('is_delete', false)
                        ->get();
        }

        if ($nguoiDung->vai_tro === 'Sinh viên') {
            $idLopHoc = SinhVienLop::where('id_sinh_vien', $nguoiDung->id)
                                    ->pluck('id_lop_hoc');

            return LopHoc::with(['hoc_phan', 'giang_vien'])
                        ->whereIn('id', $idLopHoc)
                        ->where('is_delete', false)
                        ->get();
        }

        return collect();
    }
    public function layChiTietLopHoc($slug)
    {
        return LopHoc::with([
                'hoc_phan',
                'giang_vien',
            ])
            ->where('slug', $slug)
            ->where('is_delete', false)
            ->firstOrFail();
    }
}