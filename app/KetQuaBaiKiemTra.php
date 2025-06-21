<?php

namespace App;

use Carbon\Carbon;
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

    public function getNgayLamAttribute($value)
    {
        return $value ? Carbon::parse($value)->format('d/m/Y') : null;
    }
}
