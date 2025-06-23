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
        // Check cập nhật thứ tự chương
        if (isset($request->thu_tu) && is_array($request->thu_tu)) {
            // dd(count($request->thu_tu));

            $listThuTuCuaChuong = array_map('intval', $request->thu_tu);

            foreach ($listThuTuCuaChuong as $idChuong => $thuTu) {
                try {
                    $chuong = $this->chuongService->layTheoId($idChuong);
                    $baiGiang = $chuong->bai_giang;

                    if ($baiGiang->id_giang_vien != session('id_nguoi_dung')) {
                        abort(403, 'Bạn không có quyền truy cập.');
                    }
                } catch (ModelNotFoundException $e) {
                    // $request->id là id của bài giảng
                    return redirect()->route('bai-giang.detail', $request->id)->with([
                        'message' => 'Không tìm thấy chương cần cập nhật thứ tự',
                        'status' => 'danger'
                    ]);
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
