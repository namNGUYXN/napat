<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KetQuaBaiKiemTra extends Model
{
    protected $table = 'ket_qua_bai_kiem_tra';

    public $timestamps = false;

    protected $fillable = [
        'id_thanh_vien_lop',
        'id_bai_kiem_tra',
        'ngay_lam',
        'so_cau_dung',
    ];

    public function getNgayTaoAttribute()
    {
        return $this->ngay_lam ? $this->ngay_lam->format('d/m/Y') : null;
    }
}
