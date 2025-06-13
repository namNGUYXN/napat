<?php

namespace App;

use App\BaiGiang;
use App\NguoiDung;
use Illuminate\Database\Eloquent\Model;

class MucBaiGiang extends Model
{

    protected $table = 'muc_bai_giang';

    protected $fillable = [
        'ten',
        'slug',
        'mo_ta_ngan',
        'hinh_anh',
        'id_giang_vien',
        'is_delete',
    ];

    public function getNgayTaoAttribute()
    {
        return $this->created_at ? $this->created_at->format('d/m/Y') : null;
    }

    public function baiGiangs()
    {
        return $this->hasMany(BaiGiang::class, 'id_muc_bai_giang');
    }

    public function giangVien()
    {
        return $this->belongsTo(NguoiDung::class, 'id_giang_vien'); // tùy bảng người dùng bạn đặt tên là gì
    }
}
