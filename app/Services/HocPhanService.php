<?php

namespace App\Services;

use App\HocPhan;

class HocPhanService
{
  function layListHocPhan()
  {
    return HocPhan::all();
  }
}