<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LoaiMenu extends Model
{
    protected $table = 'loai_menu';

    protected $fillable = [
        'ten',
        'slug',
        'thu_tu',
    ];

    function list_menu() {
        return $this->hasMany(Menu::class, 'id_loai_menu');
    }
}
