<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HocPhan extends Model
{
    protected $table = 'hoc_phan';

    public $timestamps = false;

    protected $fillable = [
        'ten',
        'slug',
        'mo_ta_ngan',
        'so_tin_chi',
        'id_khoa',
        'ngay_tao',
        'is_delete',
    ];

    public function getNgayTaoAttribute()
    {
        return $this->ngay_tao ? $this->ngay_tao->format('d/m/Y') : null;
    }

    function khoa()
    {
        return $this->belongsTo(Khoa::class, 'id_khoa');
    }

    function list_bai_giang()
    {
        return $this->hasMany(BaiGiang::class, 'id_hoc_phan');
    }

    function list_lop_hoc_phan()
    {
        return $this->hasMany(LopHocPhan::class, 'id_hoc_phan');
    }
}
