<?php

namespace App\Http\Middleware;

use App\Services\LopHocPhanService;
use App\Services\NguoiDungService;
use App\Services\ThanhVienLopService;
use Closure;

class LopHocPhanMiddleware
{
    protected $lopHocPhanService;
    protected $thanhVienLopService;
    protected $nguoiDungService;

    public function __construct(
        LopHocPhanService $lopHocPhanService,
        ThanhVienLopService $thanhVienLopService,
        NguoiDungService $nguoiDungService
    ) {
        $this->lopHocPhanService = $lopHocPhanService;
        $this->thanhVienLopService = $thanhVienLopService;
        $this->nguoiDungService = $nguoiDungService;
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
        if (isset($request->id)) {
            $lopHocPhan = $this->lopHocPhanService->layTheoId($request->id);
        } else {
            $lopHocPhan = $this->lopHocPhanService->layTheoSlug($request->slug);
        }

        $daThamGiaLopHoc = $this->thanhVienLopService->daThamGiaLopHocPhan($lopHocPhan->id);

        if ($daThamGiaLopHoc) return $next($request);

        abort(403, 'Bạn không có quyền truy cập lớp này');
    }
}
