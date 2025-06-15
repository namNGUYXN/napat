<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BinhLuan extends Model
{
    protected $table = 'binh_luan';

    public $timestamps = false;

    protected $fillable = [
        'noi_dung',
        'ngay_dang',
        'id_nguoi_dung',
        'id_bai_giang',
        'id_lop_hoc',
        'id_binh_luan_cha',
        'is_delete'
    ];

    public function binh_luan_cha()
    {
        return $this->belongsTo(BinhLuan::class, 'id_binh_luan_cha');
    }

    public function list_binh_luan_con()
    {
        return $this->hasMany(BinhLuan::class, 'id_binh_luan_cha');
    }

    public function nguoi_dung()
    {
        return $this->belongsTo(NguoiDung::class, 'id_nguoi_dung');
    }

    public function lop_hoc()
    {
        return $this->belongsTo(LopHoc::class, 'id_lop_hoc');
    }

    public function bai_giang()
    {
        return $this->belongsTo(BaiGiang::class, 'id_bai_giang');
    }
}
