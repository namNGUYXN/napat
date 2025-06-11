<?php

namespace App\Services;

use App\MucBaiGiang;

class MucBaiGiangService
{
    public function getAll()
    {
        return MucBaiGiang::where('is_delete', false)
            ->withCount('baiGiangs')
            ->orderBy('ten')
            ->get();
    }

    public function getByGiangVienId($idGiangVien)
    {
        return MucBaiGiang::where('id_giang_vien', $idGiangVien)
            ->where('is_delete', false)
            ->withCount('baiGiangs')
            ->orderBy('ten')
            ->get();
    }

    public function getBySlugWithBaiGiangs(string $slug)
    {
        return MucBaiGiang::where('slug', $slug)
            ->with('baiGiangs')
            ->firstOrFail();
    }

    /**
     * Lấy mục bài giảng theo ID
     */
    public function getById($id)
    {
        return MucBaiGiang::where('id', $id)
            ->where('is_delete', false)
            ->with('baiGiangs')
            ->firstOrFail();
    }
    
    public function layChiTietVaDanhSachBaiGiang($idMuc)
    {
        return MucBaiGiang::with([
            'baiGiangs' => function ($query) {
                $query->where('is_delete', false);
            }
        ])
        ->withCount(['baiGiangs as so_luong_bai_giang' => function ($query) {
            $query->where('is_delete', false);
        }])
        ->where('id', $idMuc)
        ->where('is_delete', false)
        ->first();
    }
}
