<?php

namespace App;

use App\CauHoiBaiTap;
use Illuminate\Database\Eloquent\Model;

class BaiTap extends Model
{
    protected $table = 'bai_tap';
    public $timestamps = false;
    protected $fillable = ['tieu_de', 'slug', 'diem_toi_da', 'id_bai_giang', 'is_delete'];

    public function cauHoiBaiTaps()          // 1 bài tập có nhiều câu hỏi
    {
        return $this->hasMany(CauHoiBaiTap::class, 'id_bai_tap');
    } 
}
 