<?php

namespace App\Services;

use App\Bai;
use App\Services\KeywordAIService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BaiService
{
    public function layTheoSlug($slug)
    {
        return Bai::where('slug', $slug)->firstOrFail();
    }

    public function layListTheoChuong(Request $request, $id, $perPage = -1)
    {
        $listBai = Bai::where('id_chuong', $id)->orderBy('thu_tu')
            ->orderBy('ngay_tao', 'desc');

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

            $thuTuMax = Bai::where('id_chuong', $idChuong)->max('thu_tu');

            $checkExists = Bai::where([
                ['id_chuong', $idChuong],
                ['tieu_de', $data['tieu_de']]
            ])->exists();

            if ($checkExists) {
                throw new \Exception('Tiêu đề bài này đã tồn tại');
            }

            //$keywords = KeywordAIService::extractKeywordsOptimized($data['noi_dung']);

            $bai = Bai::create([
                'tieu_de' => $data['tieu_de'],
                'slug' => '',
                'noi_dung' => $data['noi_dung'],
                //'keyword' => $keywords,
                'id_chuong' => $idChuong,
                'thu_tu' => $thuTuMax + 1
            ]);

            $bai->slug = Str::slug($data['tieu_de']) . '-' . $bai->id;
            $bai->save();

            DB::commit();
            return [
                'success' => true,
                'message' => 'Thêm bài thành công',
                'data' => $bai
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function layTheoId($id)
    {
        return Bai::findOrFail($id);
    }

    function chinhSua($id, array $data)
    {
        try {
            DB::beginTransaction();

            $bai = Bai::findOrFail($id);
            $slug = Str::slug($data['tieu_de']);

            $checkExists = Bai::where([
                ['id_chuong', $bai->id_chuong],
                ['id', '!=', $id],
                ['tieu_de', $data['tieu_de']]
            ])->exists();

            if ($checkExists) {
                throw new \Exception('Tiêu đề bài này đã tồn tại');
            }

            $bai->update([
                'tieu_de' => $data['tieu_de'],
                'slug' => $slug . '-' . $id,
                'noi_dung' => $data['noi_dung']
            ]);

            DB::commit();
            return [
                'success' => true,
                'message' => 'Cập nhật bài thành công',
                'data' => $bai->fresh()
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function xoa($id)
    {
        try {
            DB::beginTransaction();

            $bai = Bai::findOrFail($id);
            $noiDung = $bai->noi_dung;

            $bai->delete();

            DB::commit();
            return [
                'success' => true,
                'message' => 'Xóa bài thành công',
                'data' => $noiDung
            ];
        } catch (ModelNotFoundException $e) {
            return [
                'success' => false,
                'message' => 'Không tìm thấy bài với ID: ' . $id
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'success' => false,
                'message' => 'Lỗi khi xóa bài: ' . $e->getMessage()
            ];
        }
    }

    public function capNhatThuTu(array $data)
    {
        try {
            DB::beginTransaction();

            foreach ($data as $thuTu => $idBai) {
                Bai::where('id', $idBai)->update([
                    'thu_tu' => $thuTu + 1
                ]);
            }

            DB::commit();
            return [
                'success' => true,
                'message' => 'Cập nhật thứ tự bài thành công'
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'success' => false,
                'message' => 'Lỗi khi cập nhật thứ tự bài: ' . $e->getMessage()
            ];
        }
    }

    public function xoaHangLoat(array $listIdBai)
    {
        try {
            DB::beginTransaction();

            $rows = Bai::whereIn('id', $listIdBai)->delete();

            DB::commit();
            return [
                'success' => true,
                'message' => "Xóa {$rows} bài trong chương thành công"
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
