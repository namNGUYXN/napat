<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Khoa extends Model
{
    protected $table = 'khoa';

    public $timestamps = false;
    
    protected $fillable = [
        'ma',
        'ten',
        'slug',
        'mo_ta_ngan',
        'email',
        'ngay_tao',
        'is_delete',
    ];

    public function getNgayTaoAttribute($value)
    {
        return $value ? Carbon::parse($value)->format('d/m/Y') : null;
    }

    function list_lop_hoc_phan()
    {
        return $this->hasMany(LopHocPhan::class, 'id_khoa');
    }
}
