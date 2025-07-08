<?php

namespace App\Services;

use App\BaiTrongLop;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

class BaiTrongLopService
{
  public function congKhaiBai($idLopHocPhan, array $listBaiTrongLop)
  {
    try {
      DB::beginTransaction();

      foreach ($listBaiTrongLop as $idBai => $congKhai) {
        // BaiTrongLop::updateOrInsert(
        //   [
        //     'id_lop_hoc_phan' => $idLopHocPhan,
        //     'id_bai' => $idBai,
        //   ],
        //   [
        //     'cong_khai' => $congKhai ?? false,
        //   ]
        // );

        BaiTrongLop::where([
          ['id_lop_hoc_phan', $idLopHocPhan],
          ['id_bai', $idBai]
        ])->update(['cong_khai' => $congKhai ?? false]);
      }

      DB::commit();
      return [
        'success' => true,
        'message' => 'Công khai bài học trong lớp thành công',
      ];
    } catch (\Exception $e) {
      DB::rollBack();
      return [
        'success' => false,
        'message' => 'Lỗi khi công khai bài học: ' . $e->getMessage()
      ];
    }
  }

  public function layBaiTrongLop($idLopHocPhan, $idBai, $giangVienXem = false)
  {
    $query = [
      ['id_lop_hoc_phan', $idLopHocPhan],
      ['id_bai', $idBai],
    ];

    if (!$giangVienXem) $query[] = ['cong_khai', true];

    return BaiTrongLop::where($query)->with('lop', 'bai')->firstOrFail();
  }

  public function capNhatLaiListBai($listLopHocPhan, $idBai)
  {
    try {
      DB::beginTransaction();

      foreach ($listLopHocPhan as $lopHocPhan) {
        BaiTrongLop::insert([
          'id_lop_hoc_phan' => $lopHocPhan->id,
          'id_bai' => $idBai
        ]);
      }

      DB::commit();
      return [
        'success' => true
      ];
    } catch (\Exception $e) {
      DB::rollBack();
      return [
        'success' => false,
        'message' => $e->getMessage()
      ];
    }
  }

  public function them($idLopHocPhan, $listChuong)
  {
    try {
      DB::beginTransaction();

      $listIdBai = [];
      $data = collect();

      foreach ($listChuong as $chuong) {
        $data[] = $chuong->list_bai->pluck('id');
      }

      $listIdBai = $data->flatten()->toArray();

      foreach ($listIdBai as $idBai) {
        BaiTrongLop::insert([
          'id_lop_hoc_phan' => $idLopHocPhan,
          'id_bai' => $idBai
        ]);
      }

      DB::commit();
      return [
        'success' => true
      ];
    } catch (\Exception $e) {
      DB::rollBack();
      return [
        'success' => false,
        'message' => $e->getMessage()
      ];
    }
  }

  public function xoa($idLopHocPhan, $listChuong)
  {
    try {
      DB::beginTransaction();

      $listIdBai = [];
      $data = collect();

      foreach ($listChuong as $chuong) {
        $data[] = $chuong->list_bai->pluck('id');
      }

      $listIdBai = $data->flatten()->toArray();

      BaiTrongLop::where('id_lop_hoc_phan', $idLopHocPhan)
        ->whereIn('id_bai', $listIdBai)
        ->delete();

      DB::commit();
      return [
        'success' => true
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
