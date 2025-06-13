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

    function lamBai()
    {
        return view('modules.lop-hoc.lam-bai');
    }

    public function danhSachBaiTap($id)
    {
        $baiTaps = $this->baiTapService->getByBaiGiangId($id);
        return response()->json($baiTaps);
    }

    public function themBaiTap(Request $request)
    {
        try {
            // Gọi hàm tạo bài tập — nếu lỗi sẽ bị bắt ở catch
            $this->baiTapService->createExercise($request->all());

            // Chỉ đến đây khi thêm thành công
            $baiGiangId = $request->input('idBaiGiang');
            $baiTaps = $this->baiTapService->getByBaiGiangId($baiGiangId);

            return response()->json([
                'success' => true,
                'message' => 'Tạo bài tập thành công',
                'data' => $baiTaps
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Tạo bài tập thất bại: ' . $e->getMessage()
            ], 500);
        }
    }
}
