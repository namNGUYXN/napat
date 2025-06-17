<?php

namespace App\Http\Controllers;

use App\Helpers\UploadImageHelper;
use App\Services\BaiGiangService;
use App\Services\MucBaiGiangService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MucBaiGiangController extends Controller
{
    protected $mucBaiGiangService;
    protected $baiGiangService;
    protected $uploadImageHelper;

    function __construct(MucBaiGiangService $mucBaiGiangService, BaiGiangService $baiGiangService, UploadImageHelper $uploadImageHelper)
    {
        $this->mucBaiGiangService = $mucBaiGiangService;
        $this->baiGiangService = $baiGiangService;
        $this->uploadImageHelper = $uploadImageHelper;
        $this->middleware('muc_bai_giang')->only('chiTiet');
    }

    function giaoDienQuanLy(Request $request)
    {
        $numPerPage = 3;
        $listMucBaiGiang = $this->mucBaiGiangService->layListTheoGiangVien($numPerPage);

        // Kiểm tra số trang
        $page = (int) $request->input('page', 1);
        $lastPage = $listMucBaiGiang->lastPage();

        if ($page > $lastPage && $lastPage > 0) {
            return redirect()->route('muc-bai-giang.index', array_merge(
                $request->except('page'),
                ['page' => $lastPage]
            ));
        }

        return view('modules.muc-bai-giang.danh-sach', compact('listMucBaiGiang'));
    }

    public function chiTiet(Request $request, $id)
    {
        $mucBaiGiang = $this->mucBaiGiangService->layTheoId($id);
        $numPerPage = 5;
        $listBaiGiang = $this->baiGiangService->layListBaiGiangTheoMucBaiGiang($request, $id, $numPerPage);

        // Kiểm tra số trang
        $page = (int) $request->input('page', 1);
        $lastPage = $listBaiGiang->lastPage();

        if ($page > $lastPage && $lastPage > 0) {
            return redirect()->route('muc-bai-giang.detail', array_merge(
                ['id' => $id],
                $request->except('page'),
                ['page' => $lastPage]
            ));
        }

        return view(
            'modules.muc-bai-giang.chi-tiet',
            compact('mucBaiGiang', 'listBaiGiang', 'numPerPage')
        );
    }

    public function them(Request $request)
    {
        $data = $request->validate(
            [
                'ten' => 'required|string|max:255',
                'mo_ta_ngan' => 'nullable|string|max:255',
                'hinh_anh' => 'image'
            ],
            [
                'ten.required' => 'Vui lòng nhập tên mục bài giảng',
                'ten.max' => 'Tên mục bài giảng tối đa 255 ký tự',
                'mo_ta_ngan.max' => 'Mô tả tối đa 255 ký tự',
                'hinh_anh.image' => 'Hình ảnh không hợp lệ'
            ]
        );
        $data['hinh_anh'] = NULL;

        // var_dump($request->file('hinh_anh'));
        if ($request->hasFile('hinh_anh')) {
            $file = $request->file('hinh_anh');
            $data['hinh_anh'] = $this->uploadImageHelper->upload($file, 'muc-bai-giang');
        }

        $result = $this->mucBaiGiangService->them($data);

        if ($result['success']) {
            return redirect()->route('muc-bai-giang.index')->with([
                'message' => $result['message'],
                'status' => 'success'
            ]);
        }

        return redirect()->back()->with([
            'message' => $result['message'],
            'status' => 'danger'
        ]);

        // echo "<pre>";
        // print_r($data);
        // echo "</pre>";
    }

    public function modalChiTiet($id)
    {
        $mucBaiGiang = $this->mucBaiGiangService->layTheoId($id);

        return response()->json([
            'data' => $mucBaiGiang
        ]);
    }

    public function handleChinhSua(Request $request, $id)
    {
        $data = $request->validate(
            [
                'ten' => 'required|string|max:255',
                'mo_ta_ngan' => 'nullable|string|max:255',
                'hinh_anh' => 'image'
            ],
            [
                'ten.required' => 'Vui lòng nhập tên mục bài giảng',
                'ten.max' => 'Tên mục bài giảng tối đa 255 ký tự',
                'mo_ta_ngan.max' => 'Mô tả tối đa 255 ký tự',
                'hinh_anh.image' => 'Hình ảnh không hợp lệ'
            ]
        );

        $data['hinh_anh'] = NULL;

        if ($request->hasFile('hinh_anh')) {
            $file = $request->file('hinh_anh');

            // Xóa ảnh trong storage (nếu ảnh mặc định thì ko xóa)
            $hinh_anh_goc = $this->mucBaiGiangService->layTheoId($id)->hinh_anh;
            if (!Str::contains($hinh_anh_goc, 'no-image.png')) {
                $this->uploadImageHelper->delete($hinh_anh_goc);
            }

            $data['hinh_anh'] = $this->uploadImageHelper->upload($file, 'muc-bai-giang');
        }

        return $this->mucBaiGiangService->chinhSua($id, $data);
    }

    public function modalChinhSua(Request $request, $id)
    {
        $result = $this->handleChinhSua($request, $id);

        if ($result['success']) {
            return redirect()->route('muc-bai-giang.index')->with([
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
        $result = $this->mucBaiGiangService->xoa($id);

        if ($result['success']) {
            return redirect()->route('muc-bai-giang.index')->with([
                'message' => $result['message'],
                'status' => 'success'
            ]);
        }

        return redirect()->route('muc-bai-giang.index')->with([
            'message' => $result['message'],
            'status' => 'danger'
        ]);
    }

    public function chinhSua(Request $request, $id)
    {
        $result = $this->handleChinhSua($request, $id);

        if ($result['success']) {
            return redirect()->route('muc-bai-giang.detail', $id)->with([
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
