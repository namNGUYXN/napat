<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class BaiGiang extends Model
{

    protected $table = 'bai_giang';

    public $timestamps = false;

    protected $fillable = [
        'ten',
        'slug',
        'mo_ta_ngan',
        'hinh_anh',
        'id_giang_vien',
        'id_hoc_phan',
        'ngay_tao',
        'is_delete',
    ];

    public function getNgayTaoAttribute($value)
    {
        return $value ? Carbon::parse($value)->format('d/m/Y') : null;
    }

    public function getTongSoBaiAttribute()
    {
        return $this->list_chuong->sum(function ($chuong) {
            return $chuong->list_bai->count();
        });
    }

    public function hoc_phan()
    {
        return $this->belongsTo(HocPhan::class, 'id_hoc_phan');
    }

    public function list_chuong()
    {
        return $this->hasMany(Chuong::class, 'id_bai_giang');
    }

    public function giang_vien()
    {
        return $this->belongsTo(NguoiDung::class, 'id_giang_vien');
    }
}
