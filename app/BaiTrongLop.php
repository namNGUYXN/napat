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
    ];
}
