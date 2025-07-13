<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Bai extends Model
{
    protected $table = 'bai';

    public $timestamps = false;

    protected $fillable = [
        'tieu_de',
        'slug',
        'noi_dung',
        'keyword',
        'id_chuong',
        'ngay_tao',
        'thu_tu',
    ];
    protected $casts = [
        'keyword' => 'array',
    ];

    public function getNgayTaoAttribute($value)
    {
        return $value ? Carbon::parse($value)->format('d/m/Y') : null;
    }

    public function chuong()
    {
        return $this->belongsTo(Chuong::class, 'id_chuong');
    }

    public function list_bai_tap()
    {
        return $this->hasMany(BaiTap::class, 'id_bai');
    }

    public function list_lop_hoc_phan()
    {
        return $this->belongsToMany(LopHocPhan::class, 'bai_trong_lop', 'id_bai', 'id_lop_hoc_phan');
    }

    public function list_lop()
    {
        return $this->belongsToMany(LopHocPhan::class, 'bai_trong_lop', 'id_bai', 'id_lop_hoc_phan')
            ->withPivot('cong_khai');
    }
}
