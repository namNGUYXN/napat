<?php
namespace App\Services;

use App\BanTin;

class BanTinService
{
    public function layBanTinLopHoc($idLopHoc)
    {
        return BanTin::with([
            'nguoi_dung',
            'list_ban_tin_con.nguoi_dung' // load người dùng của từng bình luận
        ])
        ->whereNull('id_ban_tin_cha') // chỉ bản tin cha
        ->where('id_lop_hoc', $idLopHoc)
        ->where('is_delete', false) // nếu có cờ xóa
        ->orderByDesc('ngay_dang')
        ->get();
    }
}