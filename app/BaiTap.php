<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BaiTap extends Model
{
    protected $table = 'bai_tap';

    public $timestamps = false;

    protected $fillable = [
        'tieu_de',
        'slug',
        'diem_toi_da',
        'id_bai',
        'is_delete',
    ];

    public function list_cau_hoi()
    {
        return $this->hasMany(CauHoiBaiTap::class, 'id_bai_tap');
    }

    public function bai_giang()
    {
        return $this->belongsTo(BaiGiang::class, 'id_bai_giang');
    }

    public function list_thanh_vien_lop()
    {
        return $this->belongsToMany(ThanhVienLop::class, 'ket_qua_bai_tap', 'id_bai_tap', 'id_thanh_vien_lop')
            ->withPivot('so_cau_dung', 'ngay_lam');
    }
}
