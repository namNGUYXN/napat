<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class BinhLuan extends Model
{
    protected $table = 'binh_luan';

    public $timestamps = false;

    protected $fillable = [
        'noi_dung',
        'id_binh_luan_cha',
        'id_thanh_vien_lop',
        'id_bai_trong_lop',
        'ngay_tao',
        'is_delete',
    ];

    public function getNgayTaoAttribute($value)
    {
        return $value ? Carbon::parse($value)->format('d/m/Y') : null;
    }

    public function binh_luan_cha()
    {
        return $this->belongsTo(BinhLuan::class, 'id_binh_luan_cha');
    }

    public function list_binh_luan_con()
    {
        return $this->hasMany(BinhLuan::class, 'id_binh_luan_cha');
    }

    public function nguoi_dung()
    {
        return $this->belongsTo(NguoiDung::class, 'id_nguoi_dung');
    }

    public function lop_hoc()
    {
        return $this->belongsTo(LopHocPhan::class, 'id_lop_hoc');
    }

    public function bai_giang()
    {
        return $this->belongsTo(BaiGiang::class, 'id_bai_giang');
    }

    public function thanh_vien_lop()
    {
        return $this->belongsTo(ThanhVienLop::class, 'id_thanh_vien_lop');
    }
}
