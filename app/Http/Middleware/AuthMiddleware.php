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
        if ($request->session()->has('id_nguoi_dung')) {
            return $next($request);
        }

        if ($request->hasCookie('token_remember')) {
            $token_tu_cookie = hash('sha256', $request->cookie('token_remember'));
            $nguoiDung = NguoiDung::where('token_remember', $token_tu_cookie)->first();

            if ($nguoiDung) {
                session([
                    'id_nguoi_dung' => $nguoiDung->id,
                    'ho_ten' => $nguoiDung->ho_ten,
                    'vai_tro' => $nguoiDung->vai_tro
                ]);

                return $next($request);
            }
        }

        return redirect()->route('dang-nhap');
    }
}
