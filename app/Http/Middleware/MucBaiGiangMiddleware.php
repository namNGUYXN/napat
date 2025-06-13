<?php

namespace App\Http\Middleware;

use App\Services\MucBaiGiangService;
use Closure;

class MucBaiGiangMiddleware
{
    protected $mucBaiGiangService;
    
    function __construct(MucBaiGiangService $mucBaiGiangService)
    {
        $this->mucBaiGiangService = $mucBaiGiangService;
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
        $mucBaiGiang = $this->mucBaiGiangService->layTheoId($request->id);

        if ($mucBaiGiang->id_giang_vien == session('id_nguoi_dung')) {
            return $next($request);
        }

        abort(403, 'Bạn không có quyền truy cập.');
    }
}
