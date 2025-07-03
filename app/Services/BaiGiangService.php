<?php

namespace App\Services;

use App\BaiGiang;
use App\NguoiDung;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BaiGiangService
{
    public function layListTheoGiangVien($perPage = -1)
    {
        $idNguoiDungHienTai = session('id_nguoi_dung');
        $listBaiGiang = BaiGiang::where([
            ['id_giang_vien', $idNguoiDungHienTai],
            ['is_delete', false]
        ])->withCount(['list_chuong as so_chuong'])
            ->orderBy('ngay_tao', 'desc');

        if ($perPage > 0) {
            return $listBaiGiang->paginate($perPage);
        }

        return $listBaiGiang->get();
    }

    public function layTheoId($id)
    {
        return BaiGiang::where('id', $id)
            ->where('is_delete', false)
            ->withCount(['list_chuong as so_chuong'])
            ->firstOrFail();
    }

    public function them(array $data)
    {
        try {
            DB::beginTransaction();

            $slug = Str::slug($data['ten']) . '-' . Str::random(5);

            $baiGiang = BaiGiang::create([
                'ten' => $data['ten'],
                'slug' => $slug,
                'mo_ta_ngan' => $data['mo_ta_ngan'],
                'hinh_anh' => $data['hinh_anh'] ?? 'images/bai-giang/no-image.png',
                'id_giang_vien' => session('id_nguoi_dung'),
            ]);

            DB::commit();

            return [
                'success' => true,
                'message' => 'Thêm bài giảng thành công'
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'success' => false,
                'message' => 'Lỗi khi thêm bài giảng: ' . $e->getMessage()
            ];
        }
    }

    public function chinhSua($id, array $data)
    {
        try {
            DB::beginTransaction();

            $baiGiang = BaiGiang::findOrFail($id);
            $slug = null;
            if ($baiGiang->ten != $data['ten']) {
                $slug = Str::slug($data['ten']) . '-' . Str::random(5);
            }

            $baiGiang->update([
                'ten' => $data['ten'] ?? $baiGiang->ten,
                'slug' => $slug ?? $baiGiang->slug,
                'mo_ta_ngan' => $data['mo_ta_ngan'] ?? $baiGiang->mo_ta_ngan,
                'hinh_anh' => $data['hinh_anh'] ?? $baiGiang->hinh_anh,
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

    public function xoa($id)
    {
        try {
            DB::beginTransaction();

            $baiGiang = BaiGiang::findOrFail($id);

            $soChuong = $baiGiang->list_chuong()->count();

            // Kiểm tra bài giảng có chương không
            if ($soChuong > 0) {
                throw new \Exception('Không thể xóa vì có chương trong bài giảng');
            }

            $baiGiang->delete();

            DB::commit();
            return [
                'success' => true,
                'message' => 'Xóa bài giảng thành công'
            ];
        } catch (ModelNotFoundException $e) {
            return [
                'success' => false,
                'message' => 'Không tìm thấy bài giảng để xóa'
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'success' => false,
                'message' => 'Lỗi khi xóa bài giảng: ' . $e->getMessage()
            ];
        }
    }
}
