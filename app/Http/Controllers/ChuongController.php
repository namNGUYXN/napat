<?php

namespace App\Http\Controllers;

use App\Services\ChuongService;
use Illuminate\Http\Request;

class ChuongController extends Controller
{
    protected $chuongService;

    public function __construct(ChuongService $chuongService)
    {
        $this->chuongService = $chuongService;
        $this->middleware('bai_giang')->only('them');
        $this->middleware('chuong')->only('giaoDienChinhSua', 'chinhSua', 'xoa');
    }

    public function them(Request $request, $id)
    {
        $data = $request->validate(
            [
                'tieu_de' => 'required|string|max:100',
                'mo_ta_ngan' => 'nullable|string|max:255',
            ],
            [
                'tieu_de.required' => 'Vui lòng nhập tiêu đề',
                'tieu_de.max' => 'Tiêu đề tối đa 100 ký tự',
                'mo_ta_ngan.max' => 'Mô tả tối đa 255 ký tự',
            ]
        );

        $result = $this->chuongService->them($id, $data);

        if ($result['success']) {
            return redirect()->route('bai-giang.detail', $id)
                ->with([
                    'message' => $result['message'],
                    'status' => 'success'
                ]);
        }

        return redirect()->back()->with([
            'message' => $result['message'],
            'status' => 'danger'
        ]);
    }

    public function giaoDienChinhSua($id)
    {
        $chuong = $this->chuongService->layTheoId($id);

        return view('modules.chuong.chinh-sua', compact('chuong'));
    }

    public function chinhSua(Request $request, $id)
    {
        $data = $request->validate(
            [
                'tieu_de' => 'sometimes|required|string|max:100',
                'mo_ta_ngan' => 'sometimes|nullable|string|max:255'
            ],
            [
                'tieu_de.required' => 'Vui lòng nhập tiêu đề',
                'tieu_de.max' => 'Tiêu đề tối đa 100 ký tự',
                'mo_ta_ngan.max' => 'Mô tả tối đa 255 ký tự'
            ]
        );

        $result = $this->chuongService->chinhSua($id, $data);

        if ($result['success']) {
            $baiGiang = $result['data']->bai_giang;
            return redirect()->route('bai-giang.detail', $baiGiang->id)->with([
                'message' => $result['message'],
                'status' => 'success'
            ]);
        }

        return redirect()->back()->with([
            'message' => $result['message'],
            'status' => 'danger'
        ]);
    }

    public function xoa($id)
    {
        $baiGiang = $this->chuongService->layTheoId($id)->bai_giang;
        
        $result = $this->chuongService->xoa($id);
        
        if ($result['success']) {
            return redirect()->route('bai-giang.detail', $baiGiang->id)->with([
                'message' => $result['message'],
                'status' => 'success'
            ]);
        }

        return redirect()->route('bai-giang.detail', $baiGiang->id)->with([
            'message' => $result['message'],
            'status' => 'danger'
        ]);
    }
}
