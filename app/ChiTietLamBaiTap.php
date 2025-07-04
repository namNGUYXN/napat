<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChiTietLamBaiTap extends Model
{
    protected $table = 'chi_tiet_lam_bai_tap';

    public $timestamps = false;

    protected $fillable = [
        'id_ket_qua',
        'id_cau_hoi',
        'dap_an_chon',
        'chon_dung',
    ];

    public function ket_qua()
    {
        return $this->belongsTo(KetQuaBaiTap::class, 'id_ket_qua');
    }
    public function cau_hoi()
    {
        return $this->belongsTo(CauHoiBaiTap::class, 'id_cau_hoi');
    }
}
