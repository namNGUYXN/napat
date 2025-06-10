<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LoaiMenu extends Model
{
    protected $table = 'loai_menu';

    function menu() {
        return $this->hasMany('App\Menu', 'id_loai_menu');
    }
}
