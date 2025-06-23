<?php

namespace App\Http\Middleware;

use App\Services\ChuongService;
use Closure;
use Illuminate\Database\Eloquent\ModelNotFoundException;

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
        $inputThuTuChuong = $request->input('listThuTuChuong');

        if (isset($inputThuTuChuong) && is_array($inputThuTuChuong)) {
            $listThuTuChuong = array_map('intval', $inputThuTuChuong);
            // dd($request->listThuTuChuong->toArray());

            foreach ($listThuTuChuong as $idChuong) {
                try {
                    $chuong = $this->chuongService->layTheoId($idChuong);
                    $baiGiang = $chuong->bai_giang;

                    if ($baiGiang->id_giang_vien != session('id_nguoi_dung')) {
                        abort(403, 'Bạn không có quyền truy cập.');
                    }
                } catch (ModelNotFoundException $e) {
                    abort(404);
                }
            }

            return $next($request);
        }

        $chuong = $this->chuongService->layTheoId($request->id);
        $baiGiang = $chuong->bai_giang;

        if ($baiGiang->id_giang_vien == session('id_nguoi_dung')) {
            return $next($request);
        }

        abort(403, 'Bạn không có quyền truy cập.');
    }
}
