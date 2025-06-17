<?php

namespace App\Services;

use App\Khoa;

class KhoaService
{
  function layListKhoa()
  {
    return Khoa::all();
  }
  function layListKhoaWithHocPhans()
  {
    return Khoa::with('list_hoc_phan')->get();
  }
}