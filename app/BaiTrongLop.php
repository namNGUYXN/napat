<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BaiTrongLop extends Model
{
    protected $table = 'bai_trong_lop';

    public $timestamps = false;

    protected $fillable = [
        'id_lop_hoc_phan',
        'id_bai',
        'cong_khai'
    ];

    public function bai()
    {
        return $this->belongsTo(Bai::class, 'id_bai');
    }

    public function lop()
    {
        return $this->belongsTo(LopHocPhan::class, 'id_lop_hoc_phan');
    }
}
