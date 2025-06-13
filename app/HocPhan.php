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
        return $this->belongsTo('App\Khoa', 'id_khoa');
    }
    function lop_hoc()
    {
        return $this->hasMany('App\LopHoc', 'id_hoc_phan');
    }
}
