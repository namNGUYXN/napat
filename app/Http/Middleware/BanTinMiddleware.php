<?php

namespace App\Http\Middleware;

use App\Services\BanTinService;
use Closure;

class BanTinMiddleware
{
    protected $banTinService;

    public function __construct(BanTinService $banTinService)
    {
        $this->banTinService = $banTinService;
    }
    
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $banTin = $this->banTinService->layTheoId($request->id);

        if (session('id_nguoi_dung') == $banTin->thanh_vien_lop->id_nguoi_dung)
            return $next($request);

        abort(403, 'Bạn không có quyền truy cập.');
    }
}
