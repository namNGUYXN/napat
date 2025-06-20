<?php

namespace App\Services;

use App\Bai;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BaiService
{
    // public function layChiTietBai($id)
    // {
    //     return Bai::with([
    //         'list_bai_tap' => function ($query) {
    //             $query->where('is_delete', false);
    //         }
    //     ])
    //         ->where('id', $id)
    //         ->where('is_delete', false)
    //         ->firstOrFail();
    // }

    public function layListTheoChuong(Request $request, $id, $perPage = -1)
    {
        $listBai = Bai::where('id_chuong', $id)->orderBy('ngay_tao', 'desc');

        if ($search = $request->input('search')) {
            $listBai->where('tieu_de', 'like', '%' . $search . '%');
        }
        
        if ($perPage > 0)
            return $listBai->paginate($perPage);

        return $listBai->get();
    }

    public function them($idChuong, array $data)
    {
        try {
            DB::beginTransaction();

            $slug = Str::slug($data['tieu_de']) . '-' . Str::random(5);

            $bai= Bai::create([
                'tieu_de' => $data['tieu_de'],
                'slug' => $slug,
                'noi_dung' => $data['noi_dung'],
                'id_chuong' => $idChuong
            ]);

            DB::commit();
            return [
                'success' => true,
                'message' => 'Thêm bài thành công',
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'success' => false,
                'message' => 'Lỗi khi thêm bài: ' . $e->getMessage()
            ];
        }
    }

    function layTheoId($id)
    {
        return Bai::findOrFail($id);
    }

    // function chinhSua($id, array $data)
    // {
    //     try {
    //         DB::beginTransaction();

    //         $bai= Bai::findOrFail($id);
    //         $slug = null;
    //         if ($bai->tieu_de != $data['tieu_de']) {
    //             $slug = Str::slug($data['tieu_de']) . '-' . Str::random(5);
    //         }

    //         $bai->update([
    //             'tieu_de' => $data['tieu_de'] ?? $bai->tieu_de,
    //             'slug' => $slug ?? $bai->slug,
    //             'noi_dung' => $data['noi_dung'] ?? $bai->noi_dung
    //         ]);

    //         DB::commit();
    //         return [
    //             'success' => true,
    //             'message' => 'Cập nhật bài thành công',
    //             'data' => $bai->fresh()
    //         ];
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return [
    //             'success' => false,
    //             'message' => 'Lỗi khi cập nhật bài: ' . $e->getMessage()
    //         ];
    //     }
    // }

    // function xoa($id)
    // {
    //     try {
    //         DB::beginTransaction();

    //         $bai= Bai::findOrFail($id);

    //         // Kiểm tra bài giảng có lớp học liên kết
    //         // if (false) {
    //         //     throw new \Exception('Không thể xóa menu vì có menu con phụ thuộc.');
    //         // }

    //         $bai->delete();

    //         DB::commit();
    //         return [
    //             'success' => true,
    //             'message' => 'Xóa bài giảng thành công'
    //         ];
    //     } catch (ModelNotFoundException $e) {
    //         return [
    //             'success' => false,
    //             'message' => 'Không tìm thấy bài giảng với ID: ' . $id
    //         ];
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return [
    //             'success' => false,
    //             'message' => 'Lỗi khi xóa bài giảng: ' . $e->getMessage()
    //         ];
    //     }
    // }
}
