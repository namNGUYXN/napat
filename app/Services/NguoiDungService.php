<?php

namespace App\Services;

use App\NguoiDung;

class NguoiDungService
{
  public function layTheoId($id)
  {
    return NguoiDung::find($id);
  }
}