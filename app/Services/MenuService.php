<?php

namespace App\Services;

use App\LoaiMenu;
use App\Menu;

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
}
