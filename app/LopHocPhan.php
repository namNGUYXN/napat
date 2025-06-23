<?php

namespace App;

use App\HocPhan;
use App\NguoiDung;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class LopHocPhan extends Model
{
    protected $table = 'lop_hoc_phan';

    public $timestamps = false;

    protected $fillable = [
        'ma',
        'ten',
        'slug',
        'mo_ta_ngan',
        'hinh_anh',
        'id_hoc_phan',
        'id_giang_vien',
        'id_bai_giang',
        'ngay_tao',
        'is_delete',
    ];

    public function getNgayTaoAttribute($value)
    {
        return $value ? Carbon::parse($value)->format('d/m/Y') : null;
    }

    public function hoc_phan()
    {
        return $this->belongsTo(HocPhan::class, 'id_hoc_phan');
    }

    public function giang_vien()
    {
        return $this->belongsTo(NguoiDung::class, 'id_giang_vien');
    }

    public function list_thanh_vien()
    {
        return $this->belongsToMany(NguoiDung::class, 'thanh_vien_lop', 'id_lop_hoc_phan', 'id_nguoi_dung')
                ->withPivot('is_accept');
    }

    public function list_ban_tin()
    {
        return $this->hasMany(BanTin::class, 'id_lop_hoc_phan')->where('is_delete', false)->orderByDesc('ngay_dang');
    }

    public function bai_giang()
    {
        return $this->belongsTo(BaiGiang::class, 'id_bai_giang');
    }

    public function list_bai()
    {
        return $this->belongsToMany(Bai::class, 'bai_trong_lop', 'id_lop_hoc_phan', 'id_bai')
                ->withPivot('cong_khai')->orderBy('thu_tu')->orderBy('ngay_tao', 'desc');
    }
}
