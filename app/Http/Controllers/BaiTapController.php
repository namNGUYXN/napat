<?php

namespace App\Http\Controllers;

use App\Services\BaiTapService;
use Illuminate\Http\Request;

class BaiTapController extends Controller
{
    protected $baiTapService;

    public function __construct(BaiTapService $baiTapService)
    {
        $this->baiTapService = $baiTapService;
    }

    // Trang chủ phía client - Home
    function lamBai()
    {
        return view('modules.lop-hoc.lam-bai');
    }

    public function themBaiTap(Request $request)
    {
        $this->baiTapService->createExercise($request->all());
        return response()->json(['message' => 'Tạo bài tập thành công']);
    }
}
