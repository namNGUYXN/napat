<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class NguoiDung extends Model
{
    protected $table = 'nguoi_dung';

    public $timestamps = false;

    protected $fillable = [
        'ho_ten',
        'email',
        'sdt',
        'hinh_anh',
        'mat_khau',
        'vai_tro',
        'is_active',
        'is_logged',
        'ngay_tao'
    ];

    public function getNgayTaoAttribute($value)
    {
        return $value ? Carbon::parse($value)->format('d/m/Y') : null;
    }

    function setMatKhauAttribute($value)
    {
        $this->attributes['mat_khau'] = Hash::make($value);
    }

    function list_bai_giang()
    {
        return $this->hasMany(BaiGiang::class, 'id_giang_vien');
    }

    public function list_lop_hoc_phan()
    {
        return $this->belongsToMany(LopHocPhan::class, 'thanh_vien_lop', 'id_nguoi_dung', 'id_lop_hoc_phan')
            ->withPivot('is_accept')->orderByDesc('ngay_tao');
    }

    public function list_lop_hoc_phan_gv()
    {
        return $this->hasMany(LopHocPhan::class, 'id_giang_vien');
    }
}
