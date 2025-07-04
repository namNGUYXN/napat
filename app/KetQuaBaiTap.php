<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;


class KetQuaBaiTap extends Model
{
    protected $table = 'ket_qua_bai_tap';

    public $timestamps = false;

    protected $fillable = [
        'id_thanh_vien_lop',
        'id_bai_tap',
        'ngay_lam',
        'so_cau_dung',
    ];

    public function getNgayLamAttribute($value)
    {
        return $value ? Carbon::parse($value)->format('d/m/Y') : null;
    }
    public function bai_tap()
    {
        return $this->belongsTo(BaiTap::class, 'id_bai_tap');
    }
}
