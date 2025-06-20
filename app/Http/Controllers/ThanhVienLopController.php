<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ThanhVienLopService;

class ThanhVienLopController extends Controller
{
    // protected $thanhVienService;
    // public function __construct(ThanhVienLopService $thanhVienService)
    // {
    //     $this->thanhVienService = $thanhVienService;
    // }
    // public function chapNhan($id)
    // {
    //     $result = $this->thanhVienService->chapNhanYeuCau($id);

    //     if ($result['status']) {
    //         $lopId = $result['lop_id']; 

    //         $dsThanhVien = $this->thanhVienService->getAcceptedMembersByLopId($lopId);
    //         $dsYeuCau = $this->thanhVienService->getPendingMembersByLopId($lopId);

    //         $html = view('partials._thanh-vien-lop', [
    //             'thanhVien' => $dsThanhVien,
    //             'yeuCau' => $dsYeuCau,
    //         ])->render();

    //         return response()->json([
    //             'status' => true,
    //             'message' => $result['message'],
    //             'html' => $html
    //         ]);
    //     }

    //     return response()->json($result);
    // }

    // public function tuChoi($id)
    // {
    //     $result = $this->thanhVienService->tuChoiYeuCau($id);

    //     if ($result['status']) {
    //         $lopId = $result['lop_id']; 

    //         $dsYeuCau = $this->thanhVienService->getPendingMembersByLopId($lopId);

    //         $html = view('partials._danh-sach-yeu-cau', [
    //             'yeuCau' => $dsYeuCau,
    //         ])->render();

    //         return response()->json([
    //             'status' => true,
    //             'message' => $result['message'],
    //             'html' => $html
    //         ]);
    //     }

    //     return response()->json($result);
    // }
}
