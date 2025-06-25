<ul class="menu-sidebar mt-5">
  @foreach ($listMenu as $menu)
    @php
      $path = "{$menu->loai_menu->slug}/{$menu->gia_tri}";
      $url = url('/' . $path);
      // $active = $isActive($url) ? 'active' : '';
      if (session('vai_tro') != 'Giảng viên' && $menu->loai_menu->slug == 'bai-giang') break;
    @endphp
    <li>
      @if ($menu->list_menu_con->count())
        <a href="javascript:void(0)" class="nav-link">
          <i class="{{ $menu->loai_menu->icon }} me-2 fs-5"></i>
          <span class="nav-text">{{ $menu->ten }}</span>
          <i class="fas fa-angle-down float-end d-inline-block mt-1"></i>
        </a>
        <x-menu-children :children="$menu->list_menu_con" />
      @else
        <a href="{{ $url }}" class="nav-link">
          <i class="{{ $menu->loai_menu->icon }} me-2 fs-5"></i>
          <span class="nav-text">{{ $menu->ten }}</span>
        </a>
      @endif
    </li>
  @endforeach
</ul>
