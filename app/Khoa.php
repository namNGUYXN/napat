<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Khoa extends Model
{
    protected $table = 'khoa';

    public $timestamps = false;
    
    // protected $fillable = [];

    function list_hoc_phan()
    {
        return $this->hasMany(HocPhan::class, 'id_khoa');
    }
}
