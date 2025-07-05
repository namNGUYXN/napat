<?php

namespace App\Services;

use App\Chuong;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChuongService
{
    public function layTheoId($id)
    {
        return Chuong::findOrFail($id);
    }

    public function layListTheoBaiGiang(Request $request, $id, $perPage = -1)
    {
        $listChuong = Chuong::where('id_bai_giang', $id)->orderBy('thu_tu')->orderBy('id', 'desc');


        if ($search = $request->input('search')) {
            $listChuong->where('tieu_de', 'like', '%' . $search . '%')
                ->orWhere([
                    ['mo_ta_ngan', 'like', '%' . $search . '%'],
                    ['id_bai_giang', $id]
                ]);
        }

        if ($perPage > 0)
            return $listChuong->paginate($perPage);

        return $listChuong->get();
    }

    public function them($idBaiGiang, array $data)
    {
        try {
            DB::beginTransaction();

            $thuTuMax = Chuong::where('id_bai_giang', $idBaiGiang)->max('thu_tu');
            $checkExists = Chuong::where([
                ['id_bai_giang', $idBaiGiang],
                ['tieu_de', $data['tieu_de']]
            ])->exists();

            if ($checkExists) {
                throw new \Exception('Tiêu đề chương đã tồn tại');
            }

            $chuong = Chuong::create([
                'tieu_de' => $data['tieu_de'],
                'mo_ta_ngan' => $data['mo_ta_ngan'],
                'id_bai_giang' => $idBaiGiang,
                'thu_tu' => $thuTuMax + 1
            ]);

            DB::commit();

            return [
                'success' => true,
                'message' => 'Thêm chương thành công'
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function chinhSua($id, $data)
    {
        try {
            DB::beginTransaction();

            $chuong = Chuong::findOrFail($id);

            $checkExists = Chuong::where([
                ['id', '!=', $id],
                ['id_bai_giang', $chuong->id_bai_giang],
                ['tieu_de', $data['tieu_de']]
            ])->exists();

            if ($checkExists) {
                throw new \Exception('Tiêu đề chương đã tồn tại');
            }

            $chuong->update([
                'tieu_de' => $data['tieu_de'],
                'mo_ta_ngan' => $data['mo_ta_ngan'],
            ]);

            DB::commit();
            return [
                'success' => true,
                'message' => 'Cập nhật chương thành công',
                'data' => $chuong->fresh()
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

            $chuong = Chuong::findOrFail($id);

            $soBai = $chuong->list_bai()->count();

            // Kiểm tra chương có bài không
            if ($soBai > 0) {
                throw new \Exception('Không thể xóa vì có bài trong chương');
            }

            $chuong->delete();

            DB::commit();
            return [
                'success' => true,
                'message' => 'Xóa chương thành công'
            ];
        } catch (ModelNotFoundException $e) {
            return [
                'success' => false,
                'message' => 'Không tìm thấy chương để xóa'
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'success' => false,
                'message' => 'Lỗi khi xóa chương: ' . $e->getMessage()
            ];
        }
    }

    public function capNhatThuTu(array $data)
    {
        try {
            DB::beginTransaction();

            foreach ($data as $thuTu => $idChuong) {
                Chuong::where('id', $idChuong)->update([
                    'thu_tu' => $thuTu + 1
                ]);
            }

            DB::commit();
            return [
                'success' => true,
                'message' => 'Cập nhật thứ tự chương thành công'
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'success' => false,
                'message' => 'Lỗi khi cập nhật thứ tự chương: ' . $e->getMessage()
            ];
        }
    }

    public function xoaHangLoat(array $listIdChuong)
    {
        try {
            DB::beginTransaction();

            $rows = Chuong::whereIn('id', $listIdChuong)->delete();

            DB::commit();
            return [
                'success' => true,
                'message' => "Xóa {$rows} chương trong bài giảng thành công"
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
