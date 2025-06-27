<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class BaiKiemTra extends Model
{
    protected $table = 'bai_kiem_tra';

    public $timestamps = false;

    protected $fillable = [
        'tieu_de',
        'slug',
        'diem_toi_da',
        'ngay_bat_dau',
        'ngay_ket_thuc',
        'cho_phep_nop_qua_han',
        'id_lop_hoc_phan',
        'ngay_tao',
        'is_delete',
    ];

    public function getNgayTaoAttribute($value)
    {
        return $value ? Carbon::parse($value)->format('d/m/Y') : null;
    }

    public function lop_hoc_phan()
    {
        return $this->belongsTo(LopHocPhan::class, 'id_lop_hoc_phan');
    }

    public function list_cau_hoi()
    {
        return $this->hasMany(CauHoiBaiKiemTra::class, 'id_bai_kiem_tra');
    }

    public function list_ket_qua()
    {
        return $this->hasMany(KetQuaBaiKiemTra::class, 'id_bai_kiem_tra');
    }
    public function list_thanh_vien_lop()
    {
        return $this->belongsToMany(ThanhVienLop::class, 'ket_qua_bai_kiem_tra', 'id_bai_kiem_tra', 'id_thanh_vien_lop')
            ->withPivot('so_cau_dung', 'ngay_lam');
    }
}
