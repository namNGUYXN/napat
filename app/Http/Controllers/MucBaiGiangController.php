<?php

namespace App\Http\Controllers;

use App\Services\BaiGiangService;
use App\Services\MucBaiGiangService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MucBaiGiangController extends Controller
{
    protected $mucBaiGiangService;
    protected $baiGiangService;

    function __construct(MucBaiGiangService $mucBaiGiangService, BaiGiangService $baiGiangService)
    {
        $this->mucBaiGiangService = $mucBaiGiangService;
        $this->baiGiangService = $baiGiangService;
        $this->middleware('muc_bai_giang')->only('chiTiet');
    }

    function giaoDienQuanLy()
    {
        $listMucBaiGiang = $this->mucBaiGiangService->layListTheoGiangVien();

        return view('modules.muc-bai-giang.danh-sach', compact('listMucBaiGiang'));
    }

    public function chiTiet(Request $request, $id)
    {
        $mucBaiGiang = $this->mucBaiGiangService->layTheoId($id);
        $numPerPage = 5;
        $listBaiGiang = $this->baiGiangService->layListBaiGiangTheoMucBaiGiang($request, $id, $numPerPage);

        // Kiểm tra số trang
        $page = (int) $request->input('page', 1);
        $lastPage = $listBaiGiang->lastPage();

        if ($page > $lastPage && $lastPage > 0) {
            return redirect()->route('muc-bai-giang.detail', array_merge(
                ['id' => $id],
                $request->except('page'),
                ['page' => $lastPage]
            ));
        }

        return view(
            'modules.muc-bai-giang.chi-tiet',
            compact('mucBaiGiang', 'listBaiGiang', 'numPerPage')
        );
    }
}
