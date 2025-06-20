<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class KetQuaBaiTap extends Model
{
    protected $table = 'ket_qua_bai_tap';

    public $timestamps = false;

    protected $fillable = [
        'id_thanh_vien_lop',
        'id_bai_tap',
        'so_cau_dung',
        'ngay_lam',
    ];

    public function getNgayTaoAttribute()
    {
        return $this->ngay_lam ? $this->ngay_lam->format('d/m/Y') : null;
    }
}
