<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChiTietLamBaiKiemTra extends Model
{
    protected $table = 'chi_tiet_lam_bai_kiem_tra';

    public $timestamps = false;

    protected $fillable = [
        'id_ket_qua',
        'id_cau_hoi',
        'dap_an_chon',
        'chon_dung',
    ];
    public function ket_qua()
    {
        return $this->belongsTo(KetQuaBaiKiemTra::class, 'id_ket_qua');
    }
    public function cau_hoi()
    {
        return $this->belongsTo(CauHoiBaiKiemTra::class, 'id_cau_hoi');
    }
}
