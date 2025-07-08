<?php

namespace App\Http\Controllers;

use App\Services\BaiService;
use App\Services\BaiTrongLopService;
use App\Services\BinhLuanService;
use App\Services\ThanhVienLopService;
use Illuminate\Http\Request;

class BinhLuanController extends Controller
{
    protected $binhLuanService;
    protected $baiTrongLopService;
    protected $thanhVienLopService;
    protected $baiService;

    public function __construct(
        BinhLuanService $binhLuanService,
        BaiTrongLopService $baiTrongLopService,
        ThanhVienLopService $thanhVienLopService,
        BaiService $baiService
    ) {
        $this->binhLuanService = $binhLuanService;
        $this->baiTrongLopService = $baiTrongLopService;
        $this->thanhVienLopService = $thanhVienLopService;
        $this->baiService = $baiService;
        $this->middleware('binh_luan')->only('chinhSua', 'xoa');
    }

    public function them(Request $request, $idLopHocPhan, $idBai)
    {
        // dd($request->all());

        $data = $request->validate(
            [
                'noi_dung' => 'required|string'
            ],
            [
                'noi_dung.required' => 'Vui lòng nhập nội dung bình luận',
            ]
        );

        $bai = $this->baiService->layTheoId($idBai);
        $baiGiang = $bai->chuong->bai_giang;
        $giangVienXem = session('id_nguoi_dung') == $baiGiang->id_giang_vien;

        $baiTrongLop = $this->baiTrongLopService->layBaiTrongLop($idLopHocPhan, $idBai, $giangVienXem);
        $data['id_bai_trong_lop'] = $baiTrongLop->id;
        
        $thanhVienLop = $this->thanhVienLopService->layTheoLopVaNguoiDung($idLopHocPhan, session('id_nguoi_dung'));
        $data['id_thanh_vien_lop'] = $thanhVienLop->id;

        $data['id_binh_luan_cha'] = null;

        // dd($data);
        $result = $this->binhLuanService->them($data);

        if ($result['success']) {
            $listBinhLuan = $this->binhLuanService->layListTheoBaiTrongLop($baiTrongLop->id);

            $html = view('partials.lop-hoc-phan.noi-dung-bai.list-binh-luan', compact('baiTrongLop', 'listBinhLuan'))->render();

            return response()->json([
            'message' => $result['message'],
            'icon' => 'success',
            'html' => $html
        ]);
        }

        return response()->json([
            'message' => $result['message'],
            'icon' => 'error',
        ]);
    }

    public function phanHoi(Request $request, $idLopHocPhan, $idBai, $idBinhLuanCha)
    {
        // dd($request->all());

        $data = $request->validate(
            [
                'noi_dung' => 'required|string'
            ],
            [
                'noi_dung.required' => 'Vui lòng nhập nội dung bình luận',
            ]
        );

        $bai = $this->baiService->layTheoId($idBai);
        $baiGiang = $bai->chuong->bai_giang;
        $giangVienXem = session('id_nguoi_dung') == $baiGiang->id_giang_vien;

        $baiTrongLop = $this->baiTrongLopService->layBaiTrongLop($idLopHocPhan, $idBai, $giangVienXem);
        $data['id_bai_trong_lop'] = $baiTrongLop->id;
        
        $thanhVienLop = $this->thanhVienLopService->layTheoLopVaNguoiDung($idLopHocPhan, session('id_nguoi_dung'));
        $data['id_thanh_vien_lop'] = $thanhVienLop->id;

        $data['id_binh_luan_cha'] = $idBinhLuanCha;

        // dd($data);
        $result = $this->binhLuanService->them($data);

        if ($result['success']) {
            $binhLuanCon = $result['data'];

            $html = view('partials.lop-hoc-phan.noi-dung-bai.item-binh-luan-con', compact('binhLuanCon'))->render();

            return response()->json([
            'message' => $result['message'],
            'icon' => 'success',
            'html' => $html
        ]);
        }

        return response()->json([
            'message' => $result['message'],
            'icon' => 'error',
        ]);
    }
    
    public function chinhSua(Request $request, $idBinhLuan)
    {
        // dd($request->all());

        $data = $request->validate(
            [
                'noi_dung' => 'required|string'
            ],
            [
                'noi_dung.required' => 'Vui lòng nhập nội dung bình luận'
            ]
        );

        $result = $this->binhLuanService->chinhSua($idBinhLuan, $data);

        if ($result['success']) {
            $binhLuan = $result['data'];

            return response()->json([
                'message' => $result['message'],
                'icon' => 'success',
                'binhLuan' => $binhLuan
            ]);
        }
        
        return response()->json([
            'message' => $result['message'],
            'icon' => 'error'
        ]);
    }

    public function xoa($idBinhLuan)
    {
        $result = $this->binhLuanService->xoa($idBinhLuan);

        if ($result['success']) {
            return response()->json([
                'message' => $result['message'],
                'icon' => 'success'
            ]);
        }
        
        return response()->json([
            'message' => $result['message'],
            'icon' => 'error'
        ]);
    }
}
