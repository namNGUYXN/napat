<?php

namespace App\Http\Controllers;

use App\Services\BaiService;
use App\Services\BaiTrongLopService;
use App\Services\ChuongService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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
        $this->middleware('chuong')->only('giaoDienThem', 'giaoDienQuanLy', 'xoaHangLoat');
        $this->middleware('bai')->only('giaoDienChinhSua', 'chinhSua', 'chiTiet', 'xoa', 'capNhatThuTu', 'xoaHangLoat');
    }

    public function giaoDienQuanLy(Request $request, $idChuong)
    {
        $listBai = $this->baiService->layListTheoChuong($request, $idChuong);
        $chuong = $this->chuongService->layTheoId($idChuong);

        return view('modules.bai.danh-sach', compact('listBai', 'chuong'));
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

            return response()->json([
                'success' => true,
                'message' => $result['message'],
                'icon' => 'success',
                'redirect_url' => route('bai.index', $id)
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => $result['message'],
            'icon' => 'error',
        ]);
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
            return response()->json([
                'success' => true,
                'message' => $result['message'],
                'icon' => 'success',
                'redirect_url' => route('bai.index', $result['data']->id_chuong)
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => $result['message'],
            'icon' => 'error'
        ]);
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
            return redirect()->route('bai.index', $idChuong)->with([
                'message' => $result['message'],
                'icon' => 'success'
            ]);
        }

        return redirect()->route('bai.index', $idChuong)->with([
            'message' => $result['message'],
            'icon' => 'error'
        ]);
    }

    public function layListTheoChuong(Request $request, $idChuong)
    {
        $data = $this->baiService->layListTheoChuong($request, $idChuong);

        return response()->json([
            'data' => $data
        ]);
    }

    public function capNhatThuTu(Request $request)
    {
        $inputThuTuBai = $request->input('listThuTuBai');

        $listThuTuBai = array_map('intval', $inputThuTuBai);

        $result = $this->baiService->capNhatThuTu($listThuTuBai);

        return response()->json([
            'success' => $result['success'],
            'message' => $result['message']
        ]);
    }

    public function privateUploadImage(Request $request)
    {
        if ($request->hasFile('image')) {
            $idNguoiDung = session('id_nguoi_dung');
            // $tieuDeBai = Str::slug($request->input('tieu_de_bai'));
            $pathToSave = "photos/{$idNguoiDung}/import";
            $path = $request->file('image')->store($pathToSave, 'lfm_private');
            // dd($path);
            return response()->json([
                'url' => asset('storage/' . $path)
            ]);
        }

        return response()->json(['error' => 'Không có ảnh'], 400);
    }

    public function xoaHangLoat(Request $request) {
        if ($request->action == 'xoa') {
            $listIdBai = array_map('intval', $request->list_id_bai);
            // dd($listIdBai);

            $result = $this->baiService->xoaHangLoat($listIdBai);

            if ($result['success']) {
                return redirect()->route('bai.index', $request->id_chuong)->with([
                    'message' => $result['message'],
                    'icon' => 'success'
                ]);
            }

            return redirect()->route('bai.index', $request->id_chuong)->with([
                'message' => $result['message'],
                'icon' => 'error'
            ]);
        }
    }
}
