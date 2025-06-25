<?php

namespace App\Http\Middleware;

use App\NguoiDung;
use App\Services\BaiService;
use App\Services\NguoiDungService;
use App\Services\ThanhVienLopService;
use App\ThanhVienLop;
use Closure;

class SecureFileMiddleware
{
    protected $nguoiDungService;
    protected $thanhVienLopService;

    public function __construct(NguoiDungService $nguoiDungService, ThanhVienLopService $thanhVienLopService)
    {
        $this->nguoiDungService = $nguoiDungService;
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
        $idGiangVien = $request->id_nguoi_dung;

        $duocPhep = $this->thanhVienLopService->duocPhepTruyCapFile($idGiangVien);

        if ($duocPhep) {
            return $next($request);
        }

        abort(403, 'Bạn không có quyền truy cập.');
    }
}
