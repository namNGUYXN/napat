<?php

namespace App;

use App\HocPhan;
use App\NguoiDung;
use Illuminate\Database\Eloquent\Model;

class LopHoc extends Model
{
    protected $table = 'lop_hoc';

    public $timestamps = false;

    protected $fillable = [
        'ma',
        'ten',
        'slug',
        'mo_ta_ngan',
        'hinh_anh',
        'id_hoc_phan',
        'id_giang_vien',
        'ngay_tao',
        'is_delete',
    ];


    public function hoc_phan()
    {
        return $this->belongsTo('App\HocPhan', 'id_hoc_phan');
    }

    public function giang_vien()
    {
        return $this->belongsTo('App\NguoiDung', 'id_giang_vien');
    }
    public function ban_tins()
    {
        return $this->hasMany('App\BanTin', 'id_lop_hoc')->where('is_delete', false)->orderByDesc('ngay_dang');
    }

}
