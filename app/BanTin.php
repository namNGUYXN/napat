<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class BanTin extends Model
{
    protected $table = 'ban_tin';

    public $timestamps = false;

    protected $fillable = [
        'noi_dung',
        'id_ban_tin_cha',
        'id_thanh_vien_lop',
        'ngay_tao',
        'is_delete',
    ];

    public function getNgayTaoAttribute($value)
    {
        return $value ? Carbon::parse($value)->format('d/m/Y') : null;
    }

    public function nguoi_dung()
    {
        return $this->belongsTo(NguoiDung::class, 'id_nguoi_dung');
    }

    public function lop_hoc_phan()
    {
        return $this->belongsTo(LopHocPhan::class, 'id_lop_hoc');
    }

    public function ban_tin_cha()
    {
        return $this->belongsTo(BanTin::class, 'id_ban_tin_cha');
    }

    public function list_ban_tin_con()
    {
        return $this->hasMany(BanTin::class, 'id_ban_tin_cha');
    }

    public function thanh_vien_lop()
    {
        return $this->belongsTo(ThanhVienLop::class, 'id_thanh_vien_lop');
    }
}
