<?php

namespace App\Http\Controllers;

use App\Services\HocPhanService;
use App\Services\KhoaService;
use App\Services\MenuService;
use Illuminate\Http\Request;

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
}
