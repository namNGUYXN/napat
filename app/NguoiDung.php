<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class NguoiDung extends Model
{
    protected $table = 'nguoi_dung';
    public $timestamps = false;
    protected $fillable = ['ho_ten', 'email', 'sdt', 'hinh_anh', 'mat_khau', 'vai_tro', 'is_active'];

    function setMatKhauAttribute($value)
    {
        $this->attributes['mat_khau'] = Hash::make($value);
    }

    function list_muc_bai_giang()
    {
        return $this->hasMany(MucBaiGiang::class, 'id_giang_vien');
    }

    public function list_lop_hoc_sv()
    {
        return $this->belongsToMany(LopHoc::class, 'thanh_vien_lop', 'id_sinh_vien', 'id_lop_hoc');
    }

    public function list_lop_hoc_gv()
    {
        return $this->hasMany(LopHoc::class, 'id_giang_vien');
    }

    public function list_bai_kiem_tra()
    {
        return $this->belongsToMany(BaiKiemTra::class, 'ket_qua_bai_kiem_tra', 'id_sinh_vien', 'id_bai_kiem_tra');
    }

    public function list_bai_tap()
    {
        return $this->belongsToMany(BaiTap::class, 'ket_qua_bai_tap', 'id_sinh_vien', 'id_bai_tap');
    }
}
