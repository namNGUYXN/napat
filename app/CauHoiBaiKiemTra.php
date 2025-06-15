<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CauHoiBaiKiemTra extends Model
{
    protected $table = 'cau_hoi_bai_tap';

    public $timestamps = false;
    
    protected $fillable = [
        'tieu_de',
        'dap_an_a',
        'dap_an_b',
        'dap_an_c',
        'dap_an_d',
        'dap_an_dung',
        'id_bai_kiem_tra'
    ];

    public function bai_kiem_tra()
    {
        return $this->belongsTo(BaiKiemTra::class, 'id_bai_kiem_tra');
    }
}
