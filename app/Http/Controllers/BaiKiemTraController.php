<?php

namespace App\Http\Controllers;

use App\Services\BaiKiemTraService;
use Facade\FlareClient\Http\Exceptions\NotFound;
use Illuminate\Http\Request;

class BaiKiemTraController extends Controller
{
    protected $baiKiemTraService;

    public function __construct(BaiKiemTraService $baiKiemTraService)
    {
        $this->baiKiemTraService = $baiKiemTraService;
    }
    function lamBai($id)
    {
        $baiKiemTra = $this->baiKiemTraService->getById($id);
        return view('modules.lop-hoc.lam-bai', compact('baiKiemTra'));
    }

    public function danhSachBaiKiemTra($id)
    {
        $baiKiemTra = $this->baiKiemTraService->getByLopHocIdWithCauHoi($id);
        return response()->json($baiKiemTra);
    }
    public function themBaiKiemTra(Request $request)
    {
        try {
            // Gọi hàm tạo bài kiểm tra — nếu lỗi sẽ bị bắt ở catch
            $this->baiKiemTraService->createExercise($request->all());

            // Chỉ đến đây khi thêm thành công
            $lopHocId = $request->input('idLopHoc');
            $baiKiemTra = $this->baiKiemTraService->getByLopHocId($lopHocId);

            return response()->json([
                'success' => true,
                'message' => 'Tạo bài kiểm tra thành công',
                'data' => $baiKiemTra
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Tạo bài tập thất bại: ' . $e->getMessage()
            ], 500);
        }
    }
}
