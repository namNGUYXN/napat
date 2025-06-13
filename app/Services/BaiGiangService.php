<?php

namespace App\Services;

use App\BaiGiang;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BaiGiangService
{
    public function layChiTietBaiGiang($id)
    {
        return BaiGiang::with([
            'baiTaps' => function ($query) {
                $query->where('is_delete', false)
                    ->with(['cauHoiBaiTaps']);
            }
        ])
            ->where('id', $id)
            ->where('is_delete', false)
            ->firstOrFail();
    }

    function them(array $data)
    {
        try {
            DB::beginTransaction();

            $slug = Str::slug($data['tieu_de']) . '-' . Str::random(5);

            $baiGiang = BaiGiang::create([
                'tieu_de' => $data['tieu_de'],
                'slug' => $slug,
                'noi_dung' => $data['noi_dung'],
                'id_muc_bai_giang' => $data['id_muc_bai_giang'] ?? '',
                'is_delete' => false,
            ]);

            DB::commit();
            return [
                'success' => true,
                'message' => 'Thêm bài giảng thành công',
                'id_muc_bai_giang' => $data['id_muc_bai_giang']
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'success' => false,
                'message' => 'Lỗi khi thêm bài giảng: ' . $e->getMessage()
            ];
        }
    }

    function layTheoId($id)
    {
        return BaiGiang::find($id);
    }

    function chinhSua($id, array $data)
    {
        try {
            DB::beginTransaction();

            $baiGiang = BaiGiang::findOrFail($id);
            $slug = null;
            if ($baiGiang->tieu_de != $data['tieu_de']) {
                $slug = Str::slug($data['tieu_de']) . '-' . Str::random(5);
            }

            $baiGiang->update([
                'tieu_de' => $data['tieu_de'] ?? $baiGiang->tieu_de,
                'slug' => $slug ?? $baiGiang->slug,
                'noi_dung' => $data['noi_dung'] ?? $baiGiang->noi_dung
            ]);

            DB::commit();
            return [
                'success' => true,
                'message' => 'Cập nhật bài giảng thành công',
                'data' => $baiGiang->fresh()
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'success' => false,
                'message' => 'Lỗi khi cập nhật bài giảng: ' . $e->getMessage()
            ];
        }
    }
}
