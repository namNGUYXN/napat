<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    // Trang chủ phía client - Home
    function home()
    {
        return view('home');
    }

    // Trang chủ phía admin - Dashboard
    function dashboard()
    {
        return view('admin.dashboard');
    }
}
