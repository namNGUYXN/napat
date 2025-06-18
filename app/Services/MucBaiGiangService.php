<?php

namespace App\Services;

use App\MucBaiGiang;
use App\NguoiDung;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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

    function layListTheoGiangVien($perPage = -1)
    {
        $idNguoiDungHienTai = session('id_nguoi_dung');
        $listMucBaiGiang = MucBaiGiang::where([
            ['id_giang_vien', $idNguoiDungHienTai],
            ['is_delete', false]
        ])->withCount(['list_bai_giang as so_bai_giang'])
          ->orderBy('created_at', 'desc');

        if ($perPage > 0) {
            return $listMucBaiGiang->paginate($perPage);
        }

        return $listMucBaiGiang->get();
    }

    function layTheoId($id)
    {
        return MucBaiGiang::where('id', $id)
            ->where('is_delete', false)
            ->withCount(['list_bai_giang as so_bai_giang'])
            ->firstOrFail();
    }

    public function them(array $data)
    {
        try {
            DB::beginTransaction();

            $slug = Str::slug($data['ten']) . '-' . Str::random(5);

            $mucBaiGiang = MucBaiGiang::create([
                'ten' => $data['ten'],
                'slug' => $slug,
                'mo_ta_ngan' => $data['mo_ta_ngan'],
                'hinh_anh' => $data['hinh_anh'] ?? 'images/muc-bai-giang/no-image.png',
                'id_giang_vien' => session('id_nguoi_dung')
            ]);

            DB::commit();

            return [
                'success' => true,
                'message' => 'Thêm mục bài giảng thành công'
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'success' => false,
                'message' => 'Lỗi khi thêm mục bài giảng: ' . $e->getMessage()
            ];
        }
    }

    public function chinhSua($id, array $data)
    {
        try {
            DB::beginTransaction();

            $mucBaiGiang = MucBaiGiang::findOrFail($id);
            $slug = null;
            if ($mucBaiGiang->ten != $data['ten']) {
                $slug = Str::slug($data['ten']) . '-' . Str::random(5);
            }

            $mucBaiGiang->update([
                'ten' => $data['ten'] ?? $mucBaiGiang->ten,
                'slug' => $slug ?? $mucBaiGiang->slug,
                'mo_ta_ngan' => $data['mo_ta_ngan'] ?? $mucBaiGiang->mo_ta_ngan,
                'hinh_anh' => $data['hinh_anh'] ?? $mucBaiGiang->hinh_anh
            ]);

            DB::commit();
            return [
                'success' => true,
                'message' => 'Cập nhật mục bài giảng thành công',
                'data' => $mucBaiGiang->fresh()
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'success' => false,
                'message' => 'Lỗi khi cập nhật mục bài giảng: ' . $e->getMessage()
            ];
        }
    }

    function xoa($id)
    {
        try {
            DB::beginTransaction();

            $mucBaiGiang = MucBaiGiang::findOrFail($id);

            $soBaiGiang = $mucBaiGiang->list_bai_giang()->count();

            // Kiểm tra mục bài giảng có bài giảng không
            if ($soBaiGiang > 0) {
                throw new \Exception('Không thể xóa vì có bài giảng trong mục');
            }

            $mucBaiGiang->delete();

            DB::commit();
            return [
                'success' => true,
                'message' => 'Xóa mục bài giảng thành công'
            ];
        } catch (ModelNotFoundException $e) {
            return [
                'success' => false,
                'message' => 'Không tìm thấy mục bài giảng để xóa'
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'success' => false,
                'message' => 'Lỗi khi xóa mục bài giảng: ' . $e->getMessage()
            ];
        }
    }
}
