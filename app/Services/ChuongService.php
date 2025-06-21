<?php

namespace App\Services;

use App\Chuong;
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
    $listChuong = Chuong::where('id_bai_giang', $id);

    // if ($search = $request->input('search')) {
    //   $listChuong->where('tieu_de', 'like', '%' . $search . '%');
    // }

    if ($perPage > 0)
      return $listChuong->paginate($perPage);

    return $listChuong->get();
  }

  public function them($idBaiGiang, array $data)
  {
    try {
      DB::beginTransaction();

      $baiGiang = Chuong::create([
        'tieu_de' => $data['tieu_de'],
        'mo_ta_ngan' => $data['mo_ta_ngan'],
        'id_bai_giang' => $idBaiGiang
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
        'message' => 'Lỗi khi thêm chương: ' . $e->getMessage()
      ];
    }
  }

  public function chinhSua($id, $data)
  {
    try {
      DB::beginTransaction();

      $chuong = Chuong::findOrFail($id);

      $chuong->update([
        'tieu_de' => $data['tieu_de'] ?? $chuong->tieu_de,
        'mo_ta_ngan' => $data['mo_ta_ngan'] ?? $chuong->mo_ta_ngan,
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
        'message' => 'Lỗi khi cập nhật chương: ' . $e->getMessage()
      ];
    }
  }
}
