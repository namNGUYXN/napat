<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CauHoiBaiKiemTra extends Model
{
    protected $table = 'cau_hoi_bai_kiem_tra';

    public $timestamps = false;

    protected $fillable = [
        'tieu_de',
        'dap_an_a',
        'dap_an_b',
        'dap_an_c',
        'dap_an_d',
        'dap_an_dung',
        'id_bai_kiem_tra',
    ];

    public function bai_kiem_tra()
    {
        return $this->belongsTo(BaiKiemTra::class, 'id_bai_kiem_tra');
    }

    public function list_thanh_vien_lop()
    {
        return $this->belongsToMany(ThanhVienLop::class, 'chi_tiet_lam_bai_kiem_tra', 'id_cau_hoi', 'id_thanh_vien_lop');
    }
}
