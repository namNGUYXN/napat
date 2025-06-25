<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ThanhVienLop extends Model
{
    protected $table = 'thanh_vien_lop';

    public $timestamps = false;

    protected $fillable = [
        'id_lop_hoc_phan',
        'id_nguoi_dung',
        'is_accept',
    ];

    public function lop_hoc_phan()
    {
        return $this->belongsTo(LopHocPhan::class, 'id_lop_hoc_phan');
    }

    public function nguoi_dung()
    {
        return $this->belongsTo(NguoiDung::class, 'id_nguoi_dung');
    }

    public function list_bai_tap()
    {
        return $this->belongsToMany(BaiTap::class, 'ket_qua_bai_tap', 'id_thanh_vien_lop', 'id_bai_tap')
                ->withPivot('so_cau_dung', 'ngay_lam');
    }

    public function list_bai_kiem_tra()
    {
        return $this->belongsToMany(BaiKiemTra::class, 'ket_qua_bai_kiem_tra', 'id_thanh_vien_lop', 'id_bai_kiem_tra')
                ->withPivot('so_cau_dung', 'ngay_lam');
    }

    public function list_ban_tin()
    {
        return $this->hasMany(BanTin::class, 'id_thanh_vien_lop');
    }

    public function list_binh_luan()
    {
        return $this->hasMany(BinhLuan::class, 'id_thanh_vien_lop');
    }

    public function list_cau_hoi_bai_tap()
    {
        return $this->belongsToMany(CauHoiBaiTap::class, 'chi_tiet_lam_bai_tap', 'id_thanh_vien_lop', 'id_cau_hoi');
    }

    public function list_cau_hoi_bai_kiem_tra()
    {
        return $this->belongsToMany(CauHoiBaiKiemTra::class, 'chi_tiet_lam_bai_kiem_tra', 'id_thanh_vien_lop', 'id_cau_hoi');
    }
}