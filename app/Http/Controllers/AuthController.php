<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected $authService;

    function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    function giaoDienDangNhap()
    {
        if (session()->has('id_nguoi_dung')) {
            if (session('vai_tro') == 'Admin') {
                return redirect()->route('dashboard');
            }
            else return redirect()->route('home');
        }
        
        return view('auth.dang-nhap');
    }

    function dangNhap(Request $request)
    {
        $request->validate([
            'email' => 'required|string',
            'mat_khau' => 'required|string|min:6',
            'ghi_nho_dang_nhap' => 'nullable'
        ]);

        $result = $this->authService->dangNhap([
            'email' => $request->email,
            'mat_khau' => $request->mat_khau,
        ], $request->has('ghi_nho_dang_nhap'));

        if ($result['success']) {
            if (session('vai_tro') == 'Admin') {
                return redirect()->route('dashboard')->with('success', $result['message']);
            }
            return redirect()->route('home')->with('success', $result['message']);
        }

        return back()->withErrors(['email' => $result['message']]);
    }

    function dangXuat()
    {
        $result = $this->authService->dangXuat();
        return redirect()->route('dang-nhap')->with('success', $result['message']);
    }
}
