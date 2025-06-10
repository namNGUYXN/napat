<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Khoa extends Model
{
    protected $table = 'khoa';
    public $timestamps = false;
    // protected $fillable = [];

    function hoc_phan()
    {
        return $this->hasMany('App\HocPhan', 'id_khoa');
    }
}
