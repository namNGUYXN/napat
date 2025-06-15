<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ThanhVienLop extends Model
{
    protected $table = 'thanh_vien_lop';

    protected $fillable = [
        'id_lop_hoc',
        'id_sinh_vien',
        'is_accept',
    ];

    public function lop_hoc()
    {
        return $this->belongsTo(LopHoc::class, 'id_lop_hoc');
    }

    public function sinh_vien()
    {
        return $this->belongsTo(NguoiDung::class, 'id_sinh_vien');
    }
}
