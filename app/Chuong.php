<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Chuong extends Model
{
    protected $table = 'chuong';

    public $timestamps = false;

    protected $fillable = [
        'tieu_de',
        'mo_ta_ngan',
        'id_bai_giang',
        'thu_tu',
    ];

    public function bai_giang()
    {
        return $this->belongsTo(BaiGiang::class, 'id_bai_giang');
    }

    public function list_bai()
    {
        return $this->hasMany(Bai::class, 'id_chuong')->orderBy('thu_tu')->orderBy('ngay_tao', 'desc');
    }
}
