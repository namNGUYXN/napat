<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BaiGiangLop extends Model
{
    protected $table = 'bai_giang_lop';
    public $timestamps = false;
    protected $fillable = ['id_lop_hoc', 'id_bai_giang', 'id_chuong'];

    public function lop_hoc()
    {
        return $this->belongsTo(LopHoc::class, 'id_lop_hoc');
    }

    public function bai_giang()
    {
        return $this->belongsTo(BaiGiang::class, 'id_bai_giang');
    }

    public function chuong()
    {
        return $this->belongsTo(Chuong::class, 'id_chuong');
    }
}
