<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Chuong extends Model
{
    protected $table = 'chuong';

    public $timestamps = false;

    // protected $fillable = [];

    function hoc_phan()
    {
        return $this->belongsTo(HocPhan::class, 'id_hoc_phan');
    }

    public function bai_giang_lop()
    {
        return $this->hasMany(BaiGiangLop::class, 'id_chuong');
    }
}
