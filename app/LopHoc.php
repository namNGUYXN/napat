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
        return $this->belongsTo(HocPhan::class, 'id_hoc_phan');
    }

    public function giang_vien()
    {
        return $this->belongsTo(NguoiDung::class, 'id_giang_vien');
    }

    public function list_ban_tin()
    {
        return $this->hasMany(BanTin::class, 'id_lop_hoc')->where('is_delete', false)->orderByDesc('ngay_dang');
    }

    public function list_thanh_vien()
    {
        return $this->belongsToMany(NguoiDung::class, 'thanh_vien_lop', 'id_lop_hoc', 'id_sinh_vien');
    }

    public function bai_giang_lop()
    {
        return $this->hasMany(BaiGiangLop::class, 'id_lop_hoc');
    }
}
