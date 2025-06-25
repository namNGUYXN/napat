<?php

namespace App\View\Components;

use App\Menu as MenuModel;
use Illuminate\View\Component;
use Illuminate\Support\Str;

class Menu extends Component
{
    public $listMenu;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->listMenu = MenuModel::whereNull('id_menu_cha')->with(['list_menu_con', 'loai_menu'])
            ->orderBy('thu_tu')->get();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.menu');
    }

    public function isActive($url)
    {
        return request()->url() === url($url);
    }

    public function urlContainsHocPhan()
    {
        return Str::contains(request()->url(), 'hoc-phan');
    }
}
