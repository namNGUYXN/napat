<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DatLaiMatKhau extends Model
{
    protected $table = 'dat_lai_mat_khau';
    protected $primaryKey = null;
    public $incrementing = false;
    public $timestamps = false;
    protected $fillable = ['email', 'token', 'created_at'];
}
