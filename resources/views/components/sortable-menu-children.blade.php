<ul class="list-unstyled">
  @foreach ($children as $menuCon)
    <li data-id="{{ $menuCon->id }}">
      {{ $menuCon->ten }}
      @if ($menuCon->list_menu_con->count())
          <x-sortable-menu-children :children="$menuCon->list_menu_con" />
      @endif
    </li>
  @endforeach
</ul>
