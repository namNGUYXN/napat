<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bai extends Model
{
    protected $table = 'bai';

    public $timestamps = false;

    protected $fillable = [
        'tieu_de',
        'slug',
        'noi_dung',
        'id_chuong',
        'ngay_tao',
        'is_delete',
    ];

    public function getNgayTaoAttribute()
    {
        return $this->ngay_tao ? $this->ngay_tao->format('d/m/Y') : null;
    }

    public function chuong()
    {
        return $this->belongsTo(Chuong::class, 'id_chuong');
    }
    
    public function list_bai_tap()          
    {
        return $this->hasMany(BaiTap::class, 'id_bai_giang');
    }

    public function list_lop_hoc_phan()
    {
        return $this->belongsToMany(LopHocPhan::class, 'bai_trong_lop', 'id_bai', 'id_lop_hoc_phan');
    }
}

