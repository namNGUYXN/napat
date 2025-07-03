<?php

namespace App\Http\Controllers;

use App\Services\KhoaService;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    protected $khoaService;

    public function __construct(KhoaService $khoaService)
    {
        $this->khoaService = $khoaService;
    }
    // Trang chủ phía client - Home
    function home()
    {
        $dsKhoa = $this->khoaService->layListKhoa();
        return view('home', compact('dsKhoa'));
    }

    // Trang chủ phía admin - Dashboard
    function dashboard()
    {
        return view('admin.dashboard');
    }
}
