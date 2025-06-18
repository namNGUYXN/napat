<?php

namespace App\Services;

use App\HocPhan;

class HocPhanService
{
  function layListHocPhan()
  {
    return HocPhan::all();
  }

  public function layListChuong($id)
  {
    $hocPhan = HocPhan::find($id);

    return $hocPhan->list_chuong;
  }
}