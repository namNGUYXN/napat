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
        // Check cập nhật thứ tự bài
        if (isset($request->thu_tu) && is_array($request->thu_tu)) {
            // dd(count($request->thu_tu));

            $listThuTuCuaBai = array_map('intval', $request->thu_tu);

            foreach ($listThuTuCuaBai as $idBai => $thuTu) {
                try {
                    $bai = $this->baiService->layTheoId($idBai);
                    $baiGiang = $bai->chuong->bai_giang;

                    if ($baiGiang->id_giang_vien != session('id_nguoi_dung')) {
                        abort(403, 'Bạn không có quyền truy cập.');
                    }
                } catch (ModelNotFoundException $e) {
                    // $request->id là id của chương
                    return redirect()->route('chuong.edit', $request->id)->with([
                        'message' => 'Không tìm thấy bài cần cập nhật thứ tự',
                        'status' => 'danger'
                    ]);
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
