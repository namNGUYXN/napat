<?php

namespace App\Services;

use App\BinhLuan;
use Illuminate\Support\Facades\DB;

class BinhLuanService
{
    public function layTheoId($id)
    {
        return BinhLuan::FindOrFail($id);
    }
    
    public function layListTheoBaiTrongLop($idBaiTrongLop)
    {
        $listBinhLuan = BinhLuan::where([
            ['id_bai_trong_lop', $idBaiTrongLop],
            ['id_binh_luan_cha', null]
        ])->with('thanh_vien_lop.nguoi_dung', 'list_binh_luan_con')
        ->orderByDesc('ngay_tao')->get();

        return $listBinhLuan;
    }

    public function them(array $data)
    {
        try {
            DB::beginTransaction();
            
            $type = $data['id_binh_luan_cha'] ? "Phản hồi bình luận" : "Bình luận bài học";
            
            $binhLuan = BinhLuan::create([
                'noi_dung' => $data['noi_dung'],
                'id_thanh_vien_lop' => $data['id_thanh_vien_lop'],
                'id_bai_trong_lop' => $data['id_bai_trong_lop'],
                'id_binh_luan_cha' => $data['id_binh_luan_cha'],
                'ngay_tao' => now()
            ]);

            DB::commit();
            return [
                'success' => true,
                'message' => "{$type} thành công",
                'data' => $binhLuan
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
    
    public function chinhSua($idBinhLuan, array $data)
    {
        try {
            DB::beginTransaction();

            $binhLuan = BinhLuan::findOrFail($idBinhLuan);
            $binhLuan->noi_dung = $data['noi_dung'];
            $binhLuan->save();

            $type = $binhLuan->id_binh_luan_cha ? "phản hồi" : "bình luận";

            DB::commit();
            return [
                'success' => true,
                'message' => "Cập nhật {$type} thành công",
                'data' => $binhLuan->fresh()
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function xoa($idBinhLuan)
    {
        try {
            DB::beginTransaction();

            $binhLuan = BinhLuan::findOrFail($idBinhLuan);
            $binhLuan->delete();

            $type = $binhLuan->id_binh_luan_cha ? "phản hồi" : "bình luận";

            DB::commit();
            return [
                'success' => true,
                'message' => "Xóa {$type} thành công"
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
}
