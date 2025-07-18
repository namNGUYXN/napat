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
        $daThamGiaLopHoc = $this->thanhVienLopService->daThamGiaLopHocPhan($request->id);

        if ($daThamGiaLopHoc) return $next($request);

        abort(403, 'Bạn chưa tham gia lớp học phần này');
    }
}
