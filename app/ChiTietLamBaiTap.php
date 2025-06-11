<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChiTietLamBaiTap extends Model
{
    protected $table = 'chi_tiet_lam_bai_tap';
    public $timestamps = false;
    protected $fillable = ['id_cau_hoi', 'id_ket_qua_bai_tap', 'dap_an_chon', 'chon_dung'];
}
