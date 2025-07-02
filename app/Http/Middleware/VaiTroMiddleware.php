<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Str;

class VaiTroMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $vaiTro)
    {
        // Lấy vai trò của người login
        $vaiTroHienTai = Str::slug(session('vai_tro'));
        // chuỗi -> mảng
        $vaiTroChoPhep = explode('+', $vaiTro);

        if (in_array($vaiTroHienTai, $vaiTroChoPhep)) {
            return $next($request);
        }

        // abort(403, 'Bạn không có quyền truy cập.');

        if ($vaiTroHienTai == "admin") {
            return redirect()->route('dashboard');
        } else {
            return redirect('/');
        }
    }
}
