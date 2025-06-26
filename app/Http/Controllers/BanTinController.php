<?php

namespace App\Http\Controllers;

use App\Services\BanTinService;
use App\Services\NguoiDungService;
use App\Services\ThanhVienLopService;
use Illuminate\Http\Request;

class BanTinController extends Controller
{
    protected $banTinService;
    protected $thanhVienLopService;
    protected $nguoiDungService;

    public function __construct(
        BanTinService $banTinService,
        ThanhVienLopService $thanhVienLopService,
        NguoiDungService $nguoiDungService
    ) {
        $this->banTinService = $banTinService;
        $this->thanhVienLopService = $thanhVienLopService;
        $this->nguoiDungService = $nguoiDungService;
        $this->middleware('ban_tin')->only('chiTiet', 'chinhSua');
    }

    public function them(Request $request, $id)
    {
        $data = $request->validate(
            [
                'noi_dung' => 'required|string'
            ],
            [
                'noi_dung.required' => 'Vui lòng nhập nội dung bản tin'
            ]
        );

        $thanhVienLop = $this->thanhVienLopService->layTheoLopVaNguoiDung($id, session('id_nguoi_dung'));

        $data['id_thanh_vien_lop'] = $thanhVienLop->id;
        $data['id_lop_hoc_phan'] = $id;
        $data['id_ban_tin_cha'] = null;

        $result = $this->banTinService->them($data);

        // dd($data, $thanhVienLop->toArray());

        if ($result['success']) {
            return redirect()->route('lop-hoc.detail', $thanhVienLop->lop_hoc_phan->slug)
                ->with([
                    'message' => $result['message'],
                    'icon' => 'success'
                ]);
        }

        return redirect()->back()->with([
            'message' => $result['message'],
            'icon' => 'error',
        ]);
    }

    public function chiTiet($id)
    {
        $banTin = $this->banTinService->layTheoId($id);

        return response()->json([
            'data' => $banTin
        ]);
    }

    public function chinhSua(Request $request, $id)
    {
        $data = $request->validate(
            [
                'noi_dung' => 'required|string'
            ],
            [
                'noi_dung.required' => 'Vui lòng nhập nội dung bản tin'
            ]
        );

        $result = $this->banTinService->chinhSua($id, $data);
        $banTin = $result['data'];

        if ($result['success']) {
            return redirect()->route('lop-hoc.detail', $banTin->lop_hoc_phan->slug)
                ->with([
                    'message' => $result['message'],
                    'icon' => 'success'
                ]);
        }

        return redirect()->back()->with([
            'message' => $result['message'],
            'icon' => 'error'
        ]);
    }

    public function phanHoi(Request $request, $idLopHocPhan, $idBanTin)
    {
        $data = $request->validate(
            [
                'noi_dung' => 'required|string'
            ],
            [
                'noi_dung.required' => 'Vui lòng nhập nội dung bản tin'
            ]
        );

        $thanhVienLop = $this->thanhVienLopService->layTheoLopVaNguoiDung($idLopHocPhan, session('id_nguoi_dung'));

        $data['id_thanh_vien_lop'] = $thanhVienLop->id;
        $data['id_lop_hoc_phan'] = $idLopHocPhan;
        $data['id_ban_tin_cha'] = $idBanTin;

        $result = $this->banTinService->them($data);

        // dd($data, $thanhVienLop->toArray());

        if ($result['success']) {
            $lopHocPhan = $thanhVienLop->lop_hoc_phan;
            $listBanTin = $this->banTinService->layBanTinLopHoc($lopHocPhan->id);
            $nguoiDung = $this->nguoiDungService->layTheoId(session('id_nguoi_dung'));

            $html = view('partials.ban-tin.list', compact('lopHocPhan', 'listBanTin', 'nguoiDung'))->render();

            return response()->json([
                'message' => $result['message'],
                'icon' => 'success',
                'html' => $html
            ]);
        }

        return response()->json([
            'message' => $result['message'],
            'icon' => 'error'
        ]);
    }
}
