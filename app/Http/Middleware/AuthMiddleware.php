<?php

namespace App\Http\Middleware;

use App\NguoiDung;
use Closure;

class AuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Kiểm tra session
        if ($request->session()->has('id_nguoi_dung')) {
            if (session('is_logged') === 1) {
                return $next($request);
            }
            return redirect()->route('dang-nhap-lan-dau')->with([
                'message' => 'Đăng nhập lần đầu vào hệ thống hãy đặt mật khẩu cho mình.',
                'status' => 'danger'
            ]);
        }

        // Kiểm tra cookie token_remember
        if ($request->hasCookie('token_remember')) {
            $token_tu_cookie = hash('sha256', $request->cookie('token_remember'));
            $nguoiDung = NguoiDung::where('token_remember', $token_tu_cookie)->first();

            if ($nguoiDung) {
                session([
                    'id_nguoi_dung' => $nguoiDung->id,
                    'ho_ten' => $nguoiDung->ho_ten,
                    'vai_tro' => $nguoiDung->vai_tro,
                    'is_logged' => $nguoiDung->is_logged,
                ]);
                if (session('is_logged') === 1) {
                    return $next($request);
                }
                return redirect()->route('dang-nhap-lan-dau')->with([
                    'message' => 'Đăng nhập lần đầu vào hệ thống hãy đặt mật khẩu cho mình.',
                    'status' => 'danger'
                ]);
            }
        }

        return redirect()->route('dang-nhap')->with([
            'message' => 'Vui lòng đăng nhập.',
            'status' => 'danger'
        ]);
    }
}
