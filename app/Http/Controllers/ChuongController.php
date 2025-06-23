<?php

namespace App\Http\Controllers;

use App\Services\BaiService;
use App\Services\ChuongService;
use Illuminate\Http\Request;

class ChuongController extends Controller
{
    protected $chuongService;
    protected $baiService;

    public function __construct(ChuongService $chuongService, BaiService $baiService)
    {
        $this->chuongService = $chuongService;
        $this->baiService = $baiService;
        $this->middleware('bai_giang')->only('them');
        $this->middleware('chuong')->only('giaoDienChinhSua', 'chinhSua', 'xoa', 'capNhatThuTu');
    }

    public function layListTheoBaiGiang(Request $request, $id)
    {
        $listChuong = $this->chuongService->layListTheoBaiGiang($request, $id);

        return response()->json([
            'data' => $listChuong
        ]);
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

    public function giaoDienChinhSua(Request $request, $id)
    {
        $chuong = $this->chuongService->layTheoId($id);
        $listBai = $this->baiService->layListTheoChuong($request, $id);

        return view('modules.chuong.chinh-sua', compact('chuong', 'listBai'));
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

    public function capNhatThuTu(Request $request, $id)
    {
        if ($request->action != 'cap-nhat') {
            return redirect()->back();
        }

        // dd($request->thu_tu);

        $data = $request->validate(
            [
                'thu_tu' => 'required|array',
                'thu_tu.*' => 'required|integer|min:1|max:1000'
            ],
            [
                'thu_tu.required' => 'Danh sách thứ tự bị bỏ qua',
                'thu_tu.array' => 'Danh sách thứ tự có kiểu không hợp lệ',
                'thu_tu.*.required' => 'Giá trị thứ tự bị bỏ qua',
                'thu_tu.*.integer' => 'Giá trị thứ tự phải là 1 số nguyên',
                'thu_tu.*.min' => 'Thứ tự tối thiểu là 1',
                'thu_tu.*.max' => 'Thứ tự tối đa là 1000'
            ]
        );
        
        $listThuTuCuaChuong = array_map('intval', $data['thu_tu']);

        $result = $this->chuongService->capNhatThuTu($listThuTuCuaChuong);

        if ($result['success']) {
            return redirect()->route('bai-giang.detail', $id)->with([
                'message' => $result['message'],
                'status' => 'success'
            ]);
        }

        return redirect()->back()->with([
            'message' => $result['message'],
            'status' => 'danger'
        ]);
    }
}
