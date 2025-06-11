<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table = 'menu';

    function loai_menu() {
        return $this->belongsTo('App\LoaiMenu', 'id_loai_menu');
    }
}
