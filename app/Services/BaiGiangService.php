<?php
namespace App\Services;

use App\BaiGiang;

class BaiGiangService
{
    public function layChiTietBaiGiang($id)
    {
        return BaiGiang::with([
            'baiTaps' => function ($query) {
                $query->where('is_delete', false);
            }
        ])
        ->where('id', $id)
        ->where('is_delete', false)
        ->firstOrFail();
    }
}