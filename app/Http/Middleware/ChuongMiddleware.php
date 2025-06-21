<?php

namespace App\Http\Middleware;

use App\Services\ChuongService;
use Closure;

class ChuongMiddleware
{
    protected $chuongService;
    
    function __construct(ChuongService $chuongService)
    {
        $this->chuongService = $chuongService;
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
        $chuong = $this->chuongService->layTheoId($request->id);
        $baiGiang = $chuong->bai_giang;

        if ($baiGiang->id_giang_vien == session('id_nguoi_dung')) {
            return $next($request);
        }

        abort(403, 'Bạn không có quyền truy cập.');
    }
}
