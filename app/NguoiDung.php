<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class NguoiDung extends Model
{
    protected $table = 'nguoi_dung';
    public $timestamps = false;
    protected $fillable = ['ho_ten', 'email', 'sdt', 'hinh_anh', 'mat_khau', 'vai_tro', 'is_active'];

    function setMatKhauAttribute($value)
    {
        $this->attributes['mat_khau'] = Hash::make($value);
    }

    function listMucBaiGiang()
    {
        return $this->hasMany('App\MucBaiGiang', 'id_giang_vien');
    }
}
