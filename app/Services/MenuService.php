<?php

namespace App\Services;

use App\LoaiMenu;
use App\Menu;
use Illuminate\Support\Facades\DB;

class MenuService
{
  function hasMenuCon($id)
  {
    return Menu::where('id_menu_cha', $id)->exists();
  }

  function dataTree($parent_id = null, $level = 0)
  {
    $listMenu = Menu::with('loai_menu') // Eager load quan hệ loai_menu
      ->where('id_menu_cha', $parent_id)
      ->orderBy('thu_tu')
      ->get();

    $result = [];

    foreach ($listMenu as $menu) {
      $result[] = [
        'id' => $menu->id,
        'ten' => str_repeat('|---', $level) . ' ' . $menu->ten,
        'slug' => $menu->slug,
        'loai_menu' => $menu->loai_menu ? $menu->loai_menu->ten : null,
        'gia_tri' => $menu->gia_tri,
        'thu_tu' => $menu->thu_tu,
        'level' => $level
      ];

      if ($this->hasMenuCon($menu->id)) {
        $resultChild = $this->dataTree($menu->id, $level + 1);
        $result = array_merge($result, $resultChild);
      }
    }

    return $result;
  }

  function layListLoaiMenu()
  {
    return LoaiMenu::all();
  }

  function them(array $data)
  {
    try {
      $maxThuTu = Menu::where('id_menu_cha', $data['id_menu_cha'])
        ->max('thu_tu');

      DB::beginTransaction();

      $menu = Menu::create([
        'ten' => $data['ten'],
        'id_loai_menu' => $data['id_loai_menu'],
        'id_menu_cha' => $data['id_menu_cha'],
        'gia_tri' => $data['gia_tri'] ?? '',
        'thu_tu' => $data['thu_tu'] ?? $maxThuTu + 1,
      ]);

      DB::commit();
      return [
        'success' => true,
        'message' => 'Thêm menu thành công',
        'data' => $menu
      ];
    } catch (\Exception $e) {
      DB::rollBack();
      return [
        'success' => false,
        'message' => 'Lỗi khi thêm menu: ' . $e->getMessage()
      ];
    }
  }

  function layTheoId($id)
  {
    return Menu::find($id);
  }

  function chinhSua($id, array $data)
  {
    try {
      DB::beginTransaction();

      $menu = Menu::findOrFail($id);

      $menu->update([
        'ten' => $data['ten'] ?? $menu->ten,
        'id_loai_menu' => $data['id_loai_menu'] ?? $menu->id_loai_menu,
        'id_menu_cha' => $data['id_menu_cha'] ?? $menu->id_menu_cha,
        'gia_tri' => $data['gia_tri'] ?? $menu->gia_tri,
        'thu_tu' => $data['thu_tu'] ?? $menu->thu_tu,
      ]);

      DB::commit();
      return [
        'success' => true,
        'message' => 'Cập nhật menu thành công',
        'data' => $menu->fresh()
      ];
    } catch (\Exception $e) {
      DB::rollBack();
      return [
        'success' => false,
        'message' => 'Lỗi khi cập nhật menu: ' . $e->getMessage()
      ];
    }
  }

  public function xoa($id)
  {
    try {
      DB::beginTransaction();

      $menu = Menu::findOrFail($id);

      // Kiểm tra xem menu có menu con không
      if ($this->hasMenuCon($id)) {
        throw new \Exception('Không thể xóa menu vì có menu con phụ thuộc.');
      }

      $menu->delete();

      DB::commit();
      return [
        'success' => true,
        'message' => 'Xóa menu thành công'
      ];
    } catch (\Exception $e) {
      DB::rollBack();
      return [
        'success' => false,
        'message' => 'Lỗi khi xóa menu: ' . $e->getMessage()
      ];
    }
  }
}
