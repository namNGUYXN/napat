<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChiTietLamBaiKiemTra extends Model
{
    protected $table = 'chi_tiet_lam_bai_kiem_tra';

    public $timestamps = false;

    protected $fillable = [
        'id_thanh_vien_lop',
        'id_cau_hoi',
        'dap_an_chon',
        'chon_dung',
    ];
}
