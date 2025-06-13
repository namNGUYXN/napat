<?php

namespace App\Helpers;

use UniSharp\LaravelFilemanager\Lfm;

class CustomLfmHelper extends Lfm
{
  public function userField()
  {
    return session('id_nguoi_dung') ?? 'guest';
  }
}
