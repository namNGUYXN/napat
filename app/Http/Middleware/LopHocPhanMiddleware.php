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
        $lopHocPhanHienTai = $this->lopHocPhanService->layTheoSlug($request->slug);
        $nguoiDung = $this->nguoiDungService->layTheoId(session('id_nguoi_dung'));
        $listLopHocPhanCuaNguoiDung = $nguoiDung->list_lop_hoc_phan;

        // dd($lopHocPhanHienTai->toArray(), $listLopHocPhanCuaNguoiDung->toArray());
        
        foreach ($listLopHocPhanCuaNguoiDung as $lopHocPhan) {
            if ($lopHocPhanHienTai->id == $lopHocPhan->id) {
                return $next($request);
            }
        }

        abort(403, 'Bạn không có quyền truy cập lớp này');
    }
}
