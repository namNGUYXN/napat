<?php

namespace App\Http\Controllers;

use App\Services\BaiGiangService;
use Illuminate\Http\Request;

class BaiGiangController extends Controller
{
    protected $baiGiangService;

    public function __construct(BaiGiangService $baiGiangService)
    {
        $this->baiGiangService = $baiGiangService;
        $this->middleware('muc_bai_giang')->only('giaoDienThem');
        $this->middleware('bai_giang')->only('giaoDienChinhSua');
    }

    // Hiển thị form chỉnh sửa bài giảng
    // public function chinhSua($id)
    // {
    //     $baiGiang = $this->baiGiangService->layChiTietBaiGiang($id);

    //     return view('modules.bai-giang.chinh-sua-bai-giang', compact('baiGiang'));
    // }

    // Trang chủ phía admin - Dashboard
    function danhSach()
    {
        return view('modules.bai-giang.danh-sach-bai-giang');
    }



    // Nam
    function giaoDienThem($id)
    {
        $idMucBaiGiang = $id;
        return view('modules.bai-giang.them', compact('idMucBaiGiang'));
    }

    function them(Request $request)
    {
        $data = $request->validate(
            [
                'tieu_de' => 'required|string|max:255',
                'noi_dung' => 'required|string',
                'id_muc_bai_giang' => 'required|exists:muc_bai_giang,id',
                'is_delete' => 'nullable|boolean'
            ],
            [
                'tieu_de.required' => 'Vui lòng nhập tiêu đề',
                'tieu_de.max' => 'Tiêu đề tối đa 255 kí tự',
                'noi_dung.required' => 'Vui lòng nhập nội dung',
                'id_muc_bai_giang.exists' => 'Không tồn tại mục bài giảng này'
            ]
        );

        $result = $this->baiGiangService->them($data);

        if ($result['success']) {
            return redirect()->route('muc-bai-giang.detail', $request['id_muc_bai_giang'])
                ->with([
                    'message' => $result['message'],
                    'status' => 'success'
                ]);
        }

        return redirect()->back()
            ->with([
                'message' => $result['message'],
                'status' => 'danger'
            ])->withInput();
    }

    function giaoDienChinhSua($id)
    {
        $baiGiang = $this->baiGiangService->layTheoId($id);

        return view('modules.bai-giang.chinh-sua', compact('baiGiang'));
    }

    function chinhSua_nam(Request $request, $id)
    {
        $data = $request->validate(
            [
                'tieu_de' => 'required|string|max:255',
                'noi_dung' => 'required|string',
            ],
            [
                'tieu_de.required' => 'Vui lòng nhập tiêu đề',
                'tieu_de.max' => 'Tiêu đề tối đa 255 kí tự',
                'noi_dung.required' => 'Vui lòng nhập nội dung',
            ]
        );

        $result = $this->baiGiangService->chinhSua($id, $data);

        if ($result['success']) {
            return redirect()->route('muc-bai-giang.detail', $result['data']->id_muc_bai_giang)
                ->with([
                    'message' => $result['message'],
                    'status' => 'success'
                ]);
        }

        return redirect()->back()
            ->with([
                'message' => $result['message'],
                'status' => 'danger'
            ])->withInput();
    }

    function chiTiet($id)
    {
        $baiGiang = $this->baiGiangService->layTheoId($id);

        return response()->json([
            'data' => $baiGiang
        ]);
    }

    function xoa(Request $request, $id) {
        $idMucBaiGiang = $request->id_muc_bai_giang;
        $result = $this->baiGiangService->xoa($id);

        if ($result['success']) {
            return redirect()->route('muc-bai-giang.detail', $idMucBaiGiang)->with([
            'message' => $result['message'],
            'status' => 'success'
        ]);
        }
        
        return redirect()->route('muc-bai-giang.detail', $idMucBaiGiang)->with([
            'message' => $result['message'],
            'status' => 'danger'
        ]);
    }

    public function layListTheoMucBaiGiang(Request $request, $idMucBaiGiang)
    {
        $data = $this->baiGiangService->layListBaiGiangTheoMucBaiGiang($request, $idMucBaiGiang);
        
        return response()->json([
            'data' => $data
        ]);
    }

    function chiTietBaiGiang()
    {
        return view('modules.bai-giang.chi-tiet');
    }

}
