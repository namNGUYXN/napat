<?php

namespace App\Http\Middleware;

use App\Services\BaiGiangService;
use Closure;

class BaiGiangMiddleware
{
    protected $baiGiangService;

    function __construct(BaiGiangService $baiGiangService)
    {
        $this->baiGiangService = $baiGiangService;
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
        $baiGiang = $this->baiGiangService->layTheoId($request->id);
        $mucBaiGiang = $baiGiang->muc_bai_giang;

        if ($mucBaiGiang->id_giang_vien == session('id_nguoi_dung')) {
            return $next($request);
        }

        abort(403, 'Bạn không có quyền truy cập.');
    }
}
