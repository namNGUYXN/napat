<ul class="submenu-sidebar custom-scrollbar">
  @foreach ($children as $menuCon)
    @php
      $path = "{$menuCon->loai_menu->slug}/{$menuCon->gia_tri}";
    @endphp
    <li>
      @if ($menuCon->list_menu_con->count())
        <a href="javascript:void(0)" class="nav-link">
          <span class="nav-text">{{ $menuCon->ten }}</span>
          <i class="fas fa-angle-down float-end d-inline-block mt-1"></i>
        </a>
        <x-menu-children :children="$menuCon->list_menu_con" />
      @else
        <a href="{{ url('/' . $path) }}" class="nav-link">
          <span class="nav-text">{{ $menuCon->ten }}</span>
        </a>
      @endif
    </li>
  @endforeach
</ul>
