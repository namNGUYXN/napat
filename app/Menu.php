<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table = 'menu';
    public $timestamps = false;
    protected $fillable = ['ten', 'id_loai_menu', 'id_menu_cha', 'gia_tri', 'thu_tu'];

    function loai_menu() {
        return $this->belongsTo('App\LoaiMenu', 'id_loai_menu');
    }

    function menu_cha() {
        return $this->belongsTo('App\Menu', 'id_menu_cha');
    }

    function list_menu_con() {
        return $this->hasMany('App\Menu', 'id_menu_cha');
    }
}
