<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TienDoHocTap extends Model
{
    protected $table = 'tien_do_hoc_tap';

    protected $fillable = [
        'id_thanh_vien',
        'id_bai_trong_lop',
        'da_hoan_thanh',
        'thoi_gian_hoan_thanh',
    ];

    public function baiGiang()
    {
        return $this->belongsTo(BaiTrongLop::class, 'id_bai_trong_lop');
    }

    public function thanhVien()
    {
        return $this->belongsTo(ThanhVienLop::class, 'id_thanh_vien');
    }
}
