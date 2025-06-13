<?php

namespace App;

use App\MucBaiGiang;
use App\BaiTap;
use Illuminate\Database\Eloquent\Model;


class BaiGiang extends Model
{

    protected $table = 'bai_giang';

    protected $fillable = [
        'tieu_de',
        'slug',
        'noi_dung',
        'id_muc_bai_giang',
        'is_delete',
    ];

    public function getNgayTaoAttribute()
    {
        return $this->created_at ? $this->created_at->format('d/m/Y') : null;
    }

    public function mucBaiGiang()
    {
        return $this->belongsTo(MucBaiGiang::class, 'id_muc_bai_giang');
    }
    
    public function baiTaps()          
    {
        return $this->hasMany(BaiTap::class, 'id_bai_giang');
    }
}

