<?php

namespace App\Http\Middleware;

use App\Services\BaiService;
use Closure;

class BaiMiddleware
{
    protected $baiService;

    function __construct(BaiService $baiService)
    {
        $this->baiService = $baiService;
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
        $bai = $this->baiService->layTheoId($request->id);
        $baiGiang = $bai->chuong->bai_giang;

        if ($baiGiang->id_giang_vien == session('id_nguoi_dung')) {
            return $next($request);
        }

        abort(403, 'Bạn không có quyền truy cập.');
    }
}
