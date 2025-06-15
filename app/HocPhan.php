<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HocPhan extends Model
{
    protected $table = 'hoc_phan';
    public $timestamps = false;
    // protected $fillable = [];

    function khoa()
    {
        return $this->belongsTo(Khoa::class, 'id_khoa');
    }

    function list_chuong()
    {
        return $this->hasMany(Chuong::class, 'id_hoc_phan');
    }

    function list_lop_hoc()
    {
        return $this->hasMany(LopHoc::class, 'id_hoc_phan');
    }
}
