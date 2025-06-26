<?php

namespace App\Http\Middleware;

use App\Services\BaiService;
use App\Services\LopHocPhanService;
use App\Services\NguoiDungService;
use App\Services\ThanhVienLopService;
use Closure;

class BaiTrongLopMiddleware
{
    protected $nguoiDungService;
    protected $lopHocPhanService;
    protected $baiService;
    protected $thanhVienLopService;

    public function __construct(
        NguoiDungService $nguoiDungService,
        BaiService $baiService,
        LopHocPhanService $lopHocPhanService,
        ThanhVienLopService $thanhVienLopService
    ) {
        $this->nguoiDungService = $nguoiDungService;
        $this->baiService = $baiService;
        $this->lopHocPhanService = $lopHocPhanService;
        $this->thanhVienLopService = $thanhVienLopService;
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

        // $nguoiDungDangNhap = $this->nguoiDungService->layTheoId(session('id_nguoi_dung'));
        // $listLopHocPhan = $nguoiDungDangNhap->list_lop_hoc_phan;

        // foreach ($listLopHocPhan as $lopHocPhan) {
        //     if ($lopHocPhan->id == $request->id) {
        //         return $next($request);
        //     }
        // }

        $daThamGiaLopHoc = $this->thanhVienLopService->daThamGiaLopHocPhan($request->id);

        if ($daThamGiaLopHoc) return $next($request);

        abort(404);
    }
}
