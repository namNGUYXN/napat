<?php

namespace App\Http\Middleware;

use App\Services\BinhLuanService;
use Closure;

class BinhLuanMiddleware
{
    protected $binhLuanService;

    public function __construct(BinhLuanService $binhLuanService)
    {
        $this->binhLuanService = $binhLuanService;
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
        $idBinhLuan = $request->binhluan;
        
        if (isset($idBinhLuan)) {
            $binhLuan = $this->binhLuanService->layTheoId($idBinhLuan);
            $idNguoiDung = $binhLuan->thanh_vien_lop->id_nguoi_dung;

            if ($idNguoiDung == session('id_nguoi_dung')) {
                return $next($request);
            }
        }
        
        abort(403);
    }
}
