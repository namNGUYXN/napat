<?php

namespace App\Services;

use App\Khoa;

class KhoaService
{
  function layListKhoa()
  {
    return Khoa::all();
  }
}