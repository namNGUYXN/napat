<?php

namespace App\Services;

use App\Khoa;

class KhoaService
{
  public function layListKhoa()
  {
    return Khoa::all();
  }

  public function layTheoId($id)
  {
    return Khoa::findOrFail($id);
  }

  public function layTheoSlug($slug)
  {
    return Khoa::where('slug', $slug)->firstOrFail();
  }
}