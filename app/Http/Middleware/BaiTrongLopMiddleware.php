<?php

namespace App\Http\Middleware;

use App\Services\BaiService;
use App\Services\LopHocPhanService;
use App\Services\NguoiDungService;
use Closure;

class BaiTrongLopMiddleware
{
    protected $nguoiDungService;
    protected $lopHocPhanService;
    protected $baiService;

    public function __construct(NguoiDungService $nguoiDungService, BaiService $baiService, LopHocPhanService $lopHocPhanService)
    {
        $this->nguoiDungService = $nguoiDungService;
        $this->baiService = $baiService;
        $this->lopHocPhanService = $lopHocPhanService;
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
        // $lopHocPhan = $this->lopHocPhanService->layTheoId($request->id);
        
        $nguoiDungDangNhap = $this->nguoiDungService->layTheoId(session('id_nguoi_dung'));
        $listLopHocPhan = $nguoiDungDangNhap->list_lop_hoc_phan;

        foreach ($listLopHocPhan as $lopHocPhan) {
            if ($lopHocPhan->id == $request->id) {
                return $next($request);
            }
        }
        
        // dd($listLopHocPhan->toArray());

        abort(403, 'Bạn không có quyền truy cập.');
    }
}
