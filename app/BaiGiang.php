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
        'ngay_tao',
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

    public function list_chuong()
    {
        return $this->hasMany(Chuong::class, 'id_bai_giang')->orderBy('thu_tu')->orderBy('id', 'desc');
    }

    public function giang_vien()
    {
        return $this->belongsTo(NguoiDung::class, 'id_giang_vien');
    }

    public function list_lop_hoc_phan()
    {
        return $this->hasMany(LopHocPhan::class, 'id_bai_giang');
    }
}
