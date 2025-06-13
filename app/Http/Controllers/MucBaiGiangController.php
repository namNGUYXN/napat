<?php

namespace App\Http\Controllers;

use App\Services\MucBaiGiangService;
use Illuminate\Http\Request;

class MucBaiGiangController extends Controller
{
    protected $mucBaiGiangService;

    function __construct(MucBaiGiangService $mucBaiGiangService)
    {
        $this->mucBaiGiangService = $mucBaiGiangService;
        $this->middleware('muc_bai_giang')->only('chiTiet');
    }

    function giaoDienQuanLy()
    {
        $listMucBaiGiang = $this->mucBaiGiangService->layListTheoGiangVien();

        return view('modules.muc-bai-giang.danh-sach', compact('listMucBaiGiang'));
    }

    function chiTiet($id)
    {
        $mucBaiGiang = $this->mucBaiGiangService->layTheoId($id);

        return view('modules.muc-bai-giang.chi-tiet', compact('mucBaiGiang'));
    }
}
