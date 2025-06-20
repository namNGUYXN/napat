<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChiTietLamBaiTap extends Model
{
    protected $table = 'chi_tiet_lam_bai_tap';

    public $timestamps = false;

    protected $fillable = [
        'id_thanh_vien_lop',
        'id_cau_hoi',
        'dap_an_chon',
        'chon_dung',
    ];
}
