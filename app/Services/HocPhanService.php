<?php

namespace App\Services;

use App\HocPhan;

class HocPhanService
{
  function layListHocPhan()
  {
    return HocPhan::all();
  }

  function layList()
  {
    return HocPhan::all();
  }

  public function layTheoId($id)
  {
    return HocPhan::findOrFail($id);
  }

  public function layTheoSlug($slug)
  {
    return HocPhan::where('slug', $slug)->firstOrFail();
  }
}