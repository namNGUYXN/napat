<?php

namespace App\Http\Controllers;

use App\Services\HocPhanService;
use Illuminate\Http\Request;

class HocPhanController extends Controller
{
    protected $hocPhanService;

    public function __construct(HocPhanService $hocPhanService)
    {
        $this->hocPhanService = $hocPhanService;
    }
}
