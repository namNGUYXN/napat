<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CauHoiBaiTap extends Model
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
        'id_bai_tap',
    ];

    public function bai_tap()
    {
        return $this->belongsTo(BaiTap::class);
    }

    public function list_thanh_vien_lop()
    {
        return $this->belongsToMany(ThanhVienLop::class, 'chi_tiet_lam_bai_tap', 'id_cau_hoi', 'id_thanh_vien_lop');
    }
}
