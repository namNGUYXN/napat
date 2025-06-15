<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BaiKiemTra extends Model
{
    protected $table = 'bai_kiem_tra';

    public $timestamps = false;

    protected $fillable = [
        'tieu_de',
        'slug',
        'ngay_bat_dau',
        'ngay_ket_thuc',
        'id_lop_hoc',
        'ngay_tao',
        'is_delete'
    ];

    public function lop_hoc()
    {
        return $this->belongsTo(LopHoc::class, 'id_lop_hoc');
    }

    public function list_cau_hoi()
    {
        return $this->hasMany(CauHoiBaiKiemTra::class, 'id_bai_kiem_tra');
    }

    public function list_sinh_vien()
    {
        return $this->belongsToMany(NguoiDung::class, 'ket_qua_bai_kiem_tra', 'id_bai_kiem_tra', 'id_sinh_vien');
    }
}
