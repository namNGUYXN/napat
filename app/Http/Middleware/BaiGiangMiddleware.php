<?php

namespace App\Http\Middleware;

use App\Services\BaiGiangService;
use App\Services\ChuongService;
use Closure;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class BaiGiangMiddleware
{
    protected $baiGiangService;
    protected $chuongService;

    function __construct(BaiGiangService $baiGiangService, ChuongService $chuongService)
    {
        $this->baiGiangService = $baiGiangService;
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
        $baiGiang = $this->baiGiangService->layTheoId($request->id);

        if ($baiGiang->id_giang_vien == session('id_nguoi_dung')) {
            return $next($request);
        }

        abort(403, 'Bạn không có quyền truy cập.');
    }
}
