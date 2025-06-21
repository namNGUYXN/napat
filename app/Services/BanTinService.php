<?php

namespace App\Services;

use App\BanTin;

class BanTinService
{
    public function layBanTinLopHoc($idLopHoc)
    {
        return BanTin::with([
            'thanh_vien_lop.nguoi_dung',
            'list_ban_tin_con.thanh_vien_lop.nguoi_dung' // load người dùng của từng bình luận
        ])
            ->whereNull('id_ban_tin_cha') // chỉ bản tin cha
            ->where('id_lop_hoc_phan', $idLopHoc)
            ->where('is_delete', false) // nếu có cờ xóa
            ->orderByDesc('ngay_tao')
            ->get();
    }
}