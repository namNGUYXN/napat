<?php

namespace App\Http\Controllers;

use App\Services\HocPhanService;
use App\Services\KhoaService;
use App\Services\MenuService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MenuController extends Controller
{
    protected $menuService;
    protected $khoaService;
    protected $hocPhanService;

    function __construct(MenuService $menuService, KhoaService $khoaService, HocPhanService $hocPhanService)
    {
        $this->menuService = $menuService;
        $this->khoaService = $khoaService;
        $this->hocPhanService = $hocPhanService;
    }

    function giaoDienQuanLy()
    {
        $listMenu = $this->menuService->dataTree();

        return view('admin.modules.menu.danh-sach', compact('listMenu'));
    }

    function giaoDienThem()
    {
        $listMenu = $this->menuService->dataTree();
        $listLoaiMenu = $this->menuService->layListLoaiMenu();
        $listKhoa = $this->khoaService->layListKhoa();
        $listHocPhan = $this->hocPhanService->laylistHocPhan();

        return view(
            'admin.modules.menu.them',
            compact('listMenu', 'listLoaiMenu', 'listKhoa', 'listHocPhan')
        );
    }

    function them(Request $request)
    {
        $data = $request->validate(
            [
                'ten' => 'required|string|max:100|unique:menu,ten',
                'id_loai_menu' => 'required|exists:loai_menu,id',
                'id_menu_cha' => 'nullable|exists:menu,id',
                'gia_tri' => 'nullable|string|max:255',
                'thu_tu' => 'nullable'
            ],
            [
                'ten.required' => 'Vui lòng nhập tên menu',
                'ten.max' => 'Tên menu tối đa 100 kí tự',
                'ten.unique' => 'Tên menu đã tồn tại',
                'id_loai_menu.required' => 'Vui lòng chọn loại menu',
                'id_loai_menu.exists' => 'Loại menu bạn chọn không tồn tại',
                'id_menu_cha.exists' => 'Menu bạn chọn không tồn tại để thuộc về'
            ]
        );

        $result = $this->menuService->them($data);

        if ($result['success']) {
            return redirect()->route('menu.index')->with([
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
        $menu = $this->menuService->layTheoId($id);

        $listMenu = $this->menuService->dataTree();
        $listLoaiMenu = $this->menuService->layListLoaiMenu();
        $listKhoa = $this->khoaService->layListKhoa();
        $listHocPhan = $this->hocPhanService->laylistHocPhan();

        return view(
            'admin.modules.menu.chinh-sua',
            compact('menu', 'listMenu', 'listLoaiMenu', 'listKhoa', 'listHocPhan')
        );
    }

    function chinhSua(Request $request, $id)
    {
        $data = $request->validate([
            'ten' => 'sometimes|string|max:100|unique:menu,ten,' . $id,
            'id_loai_menu' => 'sometimes|exists:loai_menu,id',
            'id_menu_cha' => 'nullable|exists:menu,id',
            'gia_tri' => 'sometimes|string|max:255',
            'thu_tu' => 'nullable',
        ]);

        $result = $this->menuService->chinhSua($id, $data);

        if ($result['success']) {
            return redirect()->route('menu.index')->with([
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

    public function xoa($id)
    {
        $result = $this->menuService->xoa($id);

        if ($result['success']) {
            return redirect()->route('menu.index')->with([
                'message' => $result['message'],
                'status' => 'success'
            ]);
        }

        return redirect()->route('menu.index')->with([
                'message' => $result['message'],
                'status' => 'danger'
            ]);
    }
}
