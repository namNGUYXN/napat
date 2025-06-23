<?php

namespace App\Http\Controllers;

use App\Services\BaiService;
use App\Services\ChuongService;
use Illuminate\Http\Request;

class BaiController extends Controller
{
    protected $baiService;
    protected $chuongService;

    public function __construct(BaiService $baiService, ChuongService $chuongService)
    {
        $this->baiService = $baiService;
        $this->chuongService = $chuongService;
        $this->middleware('chuong')->only('giaoDienThem');
        $this->middleware('bai')->only('chinhSua', 'xoa');
    }

    public function giaoDienThem($id)
    {
        $chuong = $this->chuongService->layTheoId($id);

        return view('modules.bai.them', compact('chuong'));
    }

    public function them(Request $request, $id)
    {
        $data = $request->validate(
            [
                'tieu_de' => 'required|string|max:255',
                'noi_dung' => 'required|string',
                'is_delete' => 'nullable|boolean'
            ],
            [
                'tieu_de.required' => 'Vui lòng nhập tiêu đề',
                'tieu_de.max' => 'Tiêu đề tối đa 255 kí tự',
                'noi_dung.required' => 'Vui lòng nhập nội dung',
            ]
        );

        $result = $this->baiService->them($id, $data);

        if ($result['success']) {
            return redirect()->route('chuong.edit', $id)
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

    public function giaoDienChinhSua($id)
    {
        $bai = $this->baiService->layTheoId($id);

        return view('modules.bai.chinh-sua', compact('bai'));
    }

    public function chinhSua(Request $request, $id)
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

        $result = $this->baiService->chinhSua($id, $data);

        if ($result['success']) {
            return redirect()->route('chuong.edit', $result['data']->id_chuong)
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

    public function chiTiet($id)
    {
        $bai = $this->baiService->layTheoId($id);

        return response()->json([
            'data' => $bai
        ]);
    }

    function xoa(Request $request, $id) {
        $idChuong = $this->baiService->layTheoId($id)->chuong->id;
        $result = $this->baiService->xoa($id);

        if ($result['success']) {
            return redirect()->route('chuong.edit', $idChuong)->with([
            'message' => $result['message'],
            'status' => 'success'
        ]);
        }
        
        return redirect()->route('chuong.edit', $idChuong)->with([
            'message' => $result['message'],
            'status' => 'danger'
        ]);
    }

    public function layListTheoChuong(Request $request, $idChuong)
    {
        $data = $this->baiService->layListTheoChuong($request, $idChuong);
        
        return response()->json([
            'data' => $data
        ]);
    }
}
