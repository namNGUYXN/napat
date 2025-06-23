<?php

namespace App\Http\Controllers;

use App\Services\BaiService;
use App\Services\BaiTrongLopService;
use App\Services\ChuongService;
use Illuminate\Http\Request;

class BaiController extends Controller
{
    protected $baiService;
    protected $chuongService;
    protected $baiTrongLopService;

    public function __construct(
        BaiService $baiService,
        ChuongService $chuongService,
        BaiTrongLopService $baiTrongLopService
    ) {
        $this->baiService = $baiService;
        $this->chuongService = $chuongService;
        $this->baiTrongLopService = $baiTrongLopService;
        $this->middleware('chuong')->only('giaoDienThem');
        $this->middleware('bai')->only('chinhSua', 'xoa', 'capNhatThuTu');
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
            // Chèn bài vào các lớp thuộc bài giảng của bài
            $baiVuaThem = $result['data'];
            $listLopHocPhan = $baiVuaThem->chuong->bai_giang->list_lop_hoc_phan;
            // dd($listLopHocPhan);
            $resultInsert = $this->baiTrongLopService->capNhatLaiListBai($listLopHocPhan, $baiVuaThem->id);

            if (!$resultInsert['success']) {
                echo $resultInsert['message'];
                exit();
            }

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

    function xoa(Request $request, $id)
    {
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

    public function capNhatThuTu(Request $request, $id)
    {
        if ($request->action != 'cap-nhat') {
            return redirect()->back();
        }

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

        $listThuTuCuaBai = array_map('intval', $data['thu_tu']);

        $result = $this->baiService->capNhatThuTu($listThuTuCuaBai);

        if ($result['success']) {
            return redirect()->route('chuong.edit', $id)->with([
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
