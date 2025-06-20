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
        return ThanhVienLop::with('nguoi_dung')
            ->where('id_lop_hoc_phan', $idLopHoc)
            ->where(function ($query) {
                $query->where('is_accept', true)
                    ->orWhereNull('is_accept');
            })
            ->get();
    }

    /**
     * Lấy danh sách sinh viên đang chờ duyệt vào lớp
     */
    public function getPendingMembersByLopId($idLopHoc)
    {
        return ThanhVienLop::with('nguoi_dung')
            ->where('id_lop_hoc_phan', $idLopHoc)
            ->where('is_accept', false)
            ->get();
    }

    // public function chapNhanYeuCau(int $id): array
    // {
    //     $thanhVien = ThanhVienLop::find($id);

    //     if (!$thanhVien) {
    //         return [
    //             'status' => false,
    //             'message' => 'Không tìm thấy yêu cầu tham gia.'
    //         ];
    //     }

    //     if ($thanhVien->is_accept) {
    //         return [
    //             'status' => false,
    //             'message' => 'Yêu cầu đã được chấp nhận trước đó.'
    //         ];
    //     }

    //     $thanhVien->is_accept = true;
    //     $thanhVien->save();

    //     return [
    //         'status' => true,
    //         'message' => 'Đã chấp nhận yêu cầu thành công.',
    //         'lop_id' => $thanhVien->id_lop_hoc, 
    //     ];
    // }
    // public function tuChoiYeuCau(int $id): array
    // {
    //     $thanhVien = ThanhVienLop::find($id);

    //     if (!$thanhVien) {
    //         return [
    //             'status' => false,
    //             'message' => 'Không tìm thấy yêu cầu tham gia.'
    //         ];
    //     }

    //     if ($thanhVien->is_accept) {
    //         return [
    //             'status' => false,
    //             'message' => 'Yêu cầu đã được chấp nhận trước đó.'
    //         ];
    //     }

    //     $lopId = $thanhVien->lop_hoc_id;

    //     // Xóa yêu cầu
    //     $thanhVien->delete();

    //     return [
    //         'status' => true,
    //         'message' => 'Đã từ chối yêu cầu thành công.',
    //         'lop_id' => $lopId
    //     ];
    // }
}