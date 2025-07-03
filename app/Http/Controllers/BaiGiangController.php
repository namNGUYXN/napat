<?php

namespace App\Http\Controllers;

use App\Helpers\UploadImageHelper;
use App\Services\BaiService;
use App\Services\BaiGiangService;
use App\Services\ChuongService;
use App\Services\HocPhanService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BaiGiangController extends Controller
{
    protected $baiGiangService;
    protected $baiService;
    protected $uploadImageHelper;
    protected $chuongService;

    function __construct(
        BaiGiangService $baiGiangService,
        BaiService $baiService,
        UploadImageHelper $uploadImageHelper,
        ChuongService $chuongService
    ) {
        $this->baiGiangService = $baiGiangService;
        $this->baiService = $baiService;
        $this->uploadImageHelper = $uploadImageHelper;
        $this->chuongService = $chuongService;
        $this->middleware('bai_giang')->only('modalChiTiet', 'chiTiet', 'modalChinhSua', 'chinhSua', 'xoa');
    }

    function giaoDienQuanLy(Request $request)
    {
        $numPerPage = 3;
        $listBaiGiang = $this->baiGiangService->layListTheoGiangVien($numPerPage);

        // Kiểm tra số trang
        $page = (int) $request->input('page', 1);
        $lastPage = $listBaiGiang->lastPage();

        if ($page > $lastPage && $lastPage > 0) {
            return redirect()->route('bai-giang.index', array_merge(
                $request->except('page'),
                ['page' => $lastPage]
            ));
        }

        return view('modules.bai-giang.danh-sach', compact('listBaiGiang'));
    }

    public function chiTiet(Request $request, $id)
    {
        $baiGiang = $this->baiGiangService->layTheoId($id);
        $numPerPage = 5;
        $listChuong = $this->chuongService->layListTheoBaiGiang($request, $id, $numPerPage);

        // Kiểm tra số trang
        $page = (int) $request->input('page', 1);
        $lastPage = $listChuong->lastPage();

        if ($page > $lastPage && $lastPage > 0) {
            return redirect()->route('bai-giang.detail', array_merge(
                ['id' => $id],
                $request->except('page'),
                ['page' => $lastPage]
            ));
        }

        return view(
            'modules.bai-giang.chi-tiet',
            compact(
                'baiGiang',
                'listChuong',
                'numPerPage',
            )
        );
    }

    public function them(Request $request)
    {
        $data = $request->validate(
            [
                'ten' => 'required|string|max:100',
                'mo_ta_ngan' => 'nullable|string|max:255',
                'hinh_anh' => 'image'
            ],
            [
                'ten.required' => 'Vui lòng nhập tên bài giảng',
                'ten.max' => 'Tên bài giảng tối đa 100 ký tự',
                'mo_ta_ngan.max' => 'Mô tả tối đa 255 ký tự',
                'hinh_anh.image' => 'Hình ảnh không hợp lệ'
            ]
        );
        $data['hinh_anh'] = NULL;

        // var_dump($request->file('hinh_anh'));
        if ($request->hasFile('hinh_anh')) {
            $file = $request->file('hinh_anh');
            $data['hinh_anh'] = $this->uploadImageHelper->upload($file, 'bai-giang');
        }

        $result = $this->baiGiangService->them($data);

        if ($result['success']) {
            return redirect()->route('bai-giang.index')->with([
                'message' => $result['message'],
                'icon' => 'success'
            ]);
        }

        return redirect()->back()->with([
            'message' => $result['message'],
            'icon' => 'error'
        ]);

        // echo "<pre>";
        // print_r($data);
        // echo "</pre>";
    }

    public function modalChiTiet($id)
    {
        $baiGiang = $this->baiGiangService->layTheoId($id);

        return response()->json([
            'data' => $baiGiang
        ]);
    }

    public function handleChinhSua(Request $request, $id)
    {
        $data = $request->validate(
            [
                'ten' => 'sometimes|required|string|max:100',
                'mo_ta_ngan' => 'nullable|string|max:255',
                'hinh_anh' => 'image'
            ],
            [
                'ten.required' => 'Vui lòng nhập tên bài giảng',
                'ten.max' => 'Tên bài giảng tối đa 100 ký tự',
                'mo_ta_ngan.max' => 'Mô tả tối đa 255 ký tự',
                'hinh_anh.image' => 'Hình ảnh không hợp lệ'
            ]
        );

        $data['hinh_anh'] = NULL;

        if ($request->hasFile('hinh_anh')) {
            $file = $request->file('hinh_anh');

            // Xóa ảnh trong storage (nếu ảnh mặc định thì ko xóa)
            $hinh_anh_goc = $this->baiGiangService->layTheoId($id)->hinh_anh;
            if (!Str::contains($hinh_anh_goc, 'no-image.png')) {
                $this->uploadImageHelper->delete($hinh_anh_goc);
            }

            $data['hinh_anh'] = $this->uploadImageHelper->upload($file, 'bai-giang');
        }

        return $this->baiGiangService->chinhSua($id, $data);
    }

    public function modalChinhSua(Request $request, $id)
    {
        $result = $this->handleChinhSua($request, $id);

        if ($result['success']) {
            return redirect()->route('bai-giang.index')->with([
                'message' => $result['message'],
                'icon' => 'success'
            ]);
        }

        return redirect()->back()->with([
            'message' => $result['message'],
            'icon' => 'error'
        ]);
    }

    public function xoa($id)
    {
        $result = $this->baiGiangService->xoa($id);

        if ($result['success']) {
            return redirect()->route('bai-giang.index')->with([
                'message' => $result['message'],
                'icon' => 'success'
            ]);
        }

        return redirect()->route('bai-giang.index')->with([
            'message' => $result['message'],
            'icon' => 'error'
        ]);
    }

    public function chinhSua(Request $request, $id)
    {
        $result = $this->handleChinhSua($request, $id);

        if ($result['success']) {
            return redirect()->route('bai-giang.detail', $id)->with([
                'message' => $result['message'],
                'icon' => 'success'
            ]);
        }

        return redirect()->back()->with([
            'message' => $result['message'],
            'icon' => 'error'
        ]);
    }
}
