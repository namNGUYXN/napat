<?php

namespace App\Services;

use App\Chuong;
use Illuminate\Http\Request;

class ChuongService
{
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
}
