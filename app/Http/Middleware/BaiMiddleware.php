<?php

namespace App\Http\Middleware;

use App\Services\BaiService;
use Closure;
use Illuminate\Database\Eloquent\ModelNotFoundException;

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
        $inputThuTuBai = $request->input('listThuTuBai');

        if (isset($inputThuTuBai) && is_array($inputThuTuBai)) {
            $listThuTuBai = array_map('intval', $inputThuTuBai);
            // dd($request->listThuTuBai->toArray());

            foreach ($listThuTuBai as $idBai) {
                try {
                    $bai = $this->baiService->layTheoId($idBai);
                    $baiGiang = $bai->chuong->bai_giang;

                    if ($baiGiang->id_giang_vien != session('id_nguoi_dung')) {
                        abort(403, 'Bạn không có quyền truy cập.');
                    }
                } catch (ModelNotFoundException $e) {
                    abort(404);
                }
            }

            return $next($request);
        }

        $inputListIdBai = $request->input('list_id_bai');

        if (isset($inputListIdBai) && is_array($inputListIdBai)) {
            $listIdBai = array_map('intval', $inputListIdBai);
            // dd($request->listThuTuBai->toArray());

            foreach ($listIdBai as $idBai) {
                try {
                    $bai = $this->baiService->layTheoId($idBai);
                    $baiGiang = $bai->chuong->bai_giang;

                    if ($baiGiang->id_giang_vien != session('id_nguoi_dung')) {
                        abort(403, 'Bạn không có quyền truy cập.');
                    }
                } catch (ModelNotFoundException $e) {
                    abort(404);
                }
            }

            return $next($request);
        }

        $bai = $this->baiService->layTheoId($request->id);
        $baiGiang = $bai->chuong->bai_giang;

        if ($baiGiang->id_giang_vien == session('id_nguoi_dung')) {
            return $next($request);
        }

        abort(403, 'Bạn không có quyền truy cập.');
    }
}
