<?php

namespace App;

use App\NguoiDung;
use App\LopHoc;
use App\BanTin;

use Illuminate\Database\Eloquent\Model;

class BanTin extends Model
{
    protected $table = 'ban_tin';

    public $timestamps = false;

    protected $fillable = [
        'noi_dung',
        'ngay_dang',
        'id_nguoi_dung',
        'id_lop_hoc',
        'id_ban_tin_cha',
        'is_delete'
    ];

    // Quan hệ
    public function nguoi_dung()
    {
        return $this->belongsTo(NguoiDung::class, 'id_nguoi_dung');
    }

    public function lop_hoc()
    {
        return $this->belongsTo(LopHoc::class, 'id_lop_hoc');
    }

    public function ban_tin_cha()
    {
        return $this->belongsTo(BanTin::class, 'id_ban_tin_cha');
    }

    public function binh_luan()
    {
        return $this->hasMany(BanTin::class, 'id_ban_tin_cha');
    }
}
