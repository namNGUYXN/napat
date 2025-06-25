<ul id="menu-root" class="list-unstyled p-0 m-0">
  @foreach ($listMenu as $menu)
    <li data-id="{{ $menu->id }}">
      {{ $menu->ten }}
      @if ($menu->list_menu_con->count())
          <x-sortable-menu-children :children="$menu->list_menu_con" />
      @endif
    </li>
  @endforeach
</ul>
