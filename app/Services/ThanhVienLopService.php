<?php

namespace App\Services;

use App\ThanhVienLop;

class ThanhVienLopService
{
    /**
     * Lấy danh sách sinh viên đã được duyệt vào lớp
     */
    public function getAcceptedMembersByLopId($idLopHoc)
    {
        return ThanhVienLop::with('sinh_vien')
            ->where('id_lop_hoc', $idLopHoc)
            ->where('is_accept', true)
            ->get();
    }

    /**
     * Lấy danh sách sinh viên đang chờ duyệt vào lớp
     */
    public function getPendingMembersByLopId($idLopHoc)
    {
        return ThanhVienLop::with('sinh_vien')
            ->where('id_lop_hoc', $idLopHoc)
            ->where('is_accept', false)
            ->get();
    }
}
