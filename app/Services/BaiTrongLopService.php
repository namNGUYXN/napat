<?php

namespace App\Services;

use App\BaiTrongLop;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

class BaiTrongLopService
{
  public function congKhaiBaiTrongLop($idLopHocPhan, array $listBaiTrongLop)
  {
    try {
      DB::beginTransaction();

      $data = [];

      foreach ($listBaiTrongLop as $idBai => $congKhai) {
        BaiTrongLop::updateOrInsert(
          [
            'id_lop_hoc_phan' => $idLopHocPhan,
            'id_bai' => $idBai,
          ],
          [
            'cong_khai' => $congKhai ?? false,
          ]
        );
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

  public function layBaiTrongLop($idLopHocPhan, $idBai)
  {
    return BaiTrongLop::where([
      ['id_lop_hoc_phan', $idLopHocPhan],
      ['id_bai', $idBai],
      ['cong_khai', true]
    ])->firstOrFail();
  }

  // public function them(array $listIdBaiGiang, $idLopHoc, $idChuong)
  // {
  //   try {
  //     DB::beginTransaction();

  //     $data = [];

  //     foreach ($listIdBaiGiang as $idBaiGiang) {
  //       $data[] = [
  //         'id_lop_hoc' => $idLopHoc,
  //         'id_bai_giang' => $idBaiGiang,
  //         'id_chuong' => $idChuong,
  //       ];
  //     }

  //     BaiGiangLop::insert($data);

  //     DB::commit();
  //     return [
  //       'success' => true,
  //       'message' => 'Gán bài giảng vào lớp thành công',
  //     ];
  //   } catch (QueryException $e) {
  //     if ($e->getCode() === '23000' || str_contains($e->getMessage(), 'Duplicate entry')) {
  //       return [
  //         'success' => false,
  //         'message' => 'Không được gán trùng bài giảng trong một chương.',
  //       ];
  //     }

  //     // Trả lỗi Query chung
  //     return [
  //       'success' => false,
  //       'message' => 'Lỗi CSDL: ' . $e->getMessage(),
  //     ];
  //   } catch (\Exception $e) {
  //     DB::rollBack();
  //     return [
  //       'success' => false,
  //       'message' => 'Lỗi khi gán bài giảng: ' . $e->getMessage()
  //     ];
  //   }
  // }

  // public function goBaiGiang($idLopHoc, $idChuong, $id)
  // {
  //   try {
  //     DB::beginTransaction();

  //     BaiGiangLop::where([
  //       ['id_lop_hoc', $idLopHoc],
  //       ['id_bai_giang', $id],
  //       ['id_chuong', $idChuong]
  //     ])->delete();

  //     DB::commit();

  //     return [
  //       'success' => true,
  //       'message' => 'Gỡ bài giảng lớp thành công',
  //     ];
  //   } catch (\Exception $e) {
  //     DB::rollBack();
  //     return [
  //       'success' => false,
  //       'message' => 'Lỗi khi gỡ bài giảng: ' . $e->getMessage()
  //     ];
  //   }
  // }
}
