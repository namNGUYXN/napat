<?php

namespace App\Services;

use App\MucBaiGiang;
use App\NguoiDung;

class MucBaiGiangService
{
    public function getAll()
    {
        return MucBaiGiang::where('is_delete', false)
            ->withCount('list_bai_giang')
            ->orderBy('ten')
            ->get();
    }

    public function getByGiangVienId($idGiangVien)
    {
        return MucBaiGiang::where('id_giang_vien', $idGiangVien)
            ->where('is_delete', false)
            ->withCount('list_bai_giang')
            ->orderBy('ten')
            ->get();
    }

    public function getBySlugWithBaiGiangs(string $slug)
    {
        return MucBaiGiang::where('slug', $slug)
            ->with('list_bai_giang')
            ->firstOrFail();
    }

    /**
     * Lấy mục bài giảng theo ID
     */
    public function getById($id)
    {
        return MucBaiGiang::where('id', $id)
            ->where('is_delete', false)
            ->with('list_bai_giang')
            ->firstOrFail();
    }
    
    public function layChiTietVaDanhSachBaiGiang($idMuc)
    {
        return MucBaiGiang::with([
            'list_bai_giang' => function ($query) {
                $query->where('is_delete', false);
            }
        ])
        ->withCount(['list_bai_giang as so_luong_bai_giang' => function ($query) {
            $query->where('is_delete', false);
        }])
        ->where('id', $idMuc)
        ->where('is_delete', false)
        ->first();
    }

    function layListTheoGiangVien()
    {
        $idNguoiDungHienTai = session('id_nguoi_dung');
        $nguoiDung = NguoiDung::find($idNguoiDungHienTai);

        $nguoiDung->load(['list_muc_bai_giang' => function ($query) {
            $query->where('is_delete', false)
                  ->withCount(['list_bai_giang as so_bai_giang']);
        }]);

        return $nguoiDung->list_muc_bai_giang;
    }

    function layTheoId($id)
    {
        return MucBaiGiang::where('id', $id)
            ->where('is_delete', false)
            ->withCount(['list_bai_giang as so_bai_giang'])
            ->with('list_bai_giang')
            ->firstOrFail();
    }
}
