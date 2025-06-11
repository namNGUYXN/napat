<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class KetQuaBaiTap extends Model
{
    protected $table = 'ket_qua_bai_tap';
    public $timestamps = false;
    protected $fillable = ['id_sinh_vien', 'id_bai_tap', 'ngay_lam', 'so_cau_dung'];
}
