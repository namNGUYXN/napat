<?php

namespace App\Services;

use App\Khoa;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\KhoaImport;

class KhoaService
{
  public function layListKhoa()
  {
    return Khoa::all();
  }

  public function layTheoId($id)
  {
    return Khoa::where('is_delete', false)->findOrFail($id);
  }

  public function layTheoSlug($slug)
  {
    return Khoa::where('slug', $slug)->firstOrFail();
  }

  //Danh sách + lọc
  public function danhSachKhoa($keyword = null, $perPage = 5)
  {
    $query = Khoa::query()->where('is_delete', false);

    if ($keyword) {
      $query->where(function ($q) use ($keyword) {
        $q->where('ten', 'like', "%$keyword%")
          ->orWhere('ma', 'like', "%$keyword%")
          ->orWhere('email', 'like', "%$keyword%");
      });
    }

    return $query->orderByDesc('ngay_tao')->paginate($perPage);
  }

  public function themKhoa($data)
  {
    return Khoa::create([
      'ma' => $data['ma'],
      'ten' => $data['ten'],
      'slug' => Str::slug($data['ten']),
      'mo_ta_ngan' => $data['mo_ta_ngan'] ?? null,
      'ngay_tao' => Carbon::now(),
    ]);
  }

  public function importTuExcel($file)
  {
    $import = new KhoaImport();
    Excel::import($import, $file);

    if ($import->failures()->isNotEmpty()) {
      return [
        'success' => false,
        'failures' => $import->failures(),
      ];
    }

    foreach ($import->getValidRows() as $row) {
      Khoa::create($row);
    }

    return ['success' => true];
  }

  public function capNhat($id, array $data)
  {
    $khoa = $this->layTheoId($id);
    if (!$khoa) {
      throw new \Exception('Không tìm thấy khoa.');
    }

    try {
      $khoa->update($data);
    } catch (\Exception $e) {
      throw new \Exception('Lỗi khi cập nhật: ' . $e->getMessage());
    }
  }

  //Xóa
  public function xoaKhoa($id)
  {
    $khoa = $this->layTheoId($id);
    $khoa->update(['is_delete' => true]);
    return true;
  }
}
