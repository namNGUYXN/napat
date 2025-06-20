<?php

namespace App;

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

    public function getNgayTaoAttribute()
    {
        return $this->ngay_tao ? $this->ngay_tao->format('d/m/Y') : null;
    }

    function list_hoc_phan()
    {
        return $this->hasMany(HocPhan::class, 'id_khoa');
    }
}
