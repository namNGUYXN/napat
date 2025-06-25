<!-- Offcanvas Sidebar (Mobile) -->
<div class="offcanvas offcanvas-start" tabindex="-1" id="sidebarOffcanvas">
  <div class="offcanvas-header">
    <h6 class="offcanvas-title">Menu</h6>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
  </div>
  <div class="offcanvas-body p-0 custom-scrollbar">
    <ul class="menu-sidebar">
      <li>
        <a href="#" class="nav-link">
          <i class="fas fa-tachometer-alt me-2 fs-5"></i>
          <span class="nav-text">Bảng điều khiển</span>
        </a>
      </li>
      <li>
        <a href="javascript:void(0)" class="nav-link">
          <i class="fas fa-layer-group me-2 fs-5"></i>
          <span class="nav-text">Quản lý Khoa</span>
          <i class="fas fa-angle-down float-end d-inline-block mt-1"></i>
        </a>
        <ul class="submenu-sidebar custom-scrollbar">
          <li>
            <a href="#" class="nav-link"><span class="nav-text">Xem danh sách</span></a>
          </li>
          <li>
            <a href="#" class="nav-link"><span class="nav-text">Thêm Khoa mới</span></a>
          </li>
        </ul>
      </li>
      <li>
        <a href="javascript:void(0)" class="nav-link">
          <i class="fas fa-book me-2 fs-5"></i>
          <span class="nav-text">Quản lý Học phần</span>
          <i class="fas fa-angle-down float-end d-inline-block mt-1"></i>
        </a>
        <ul class="submenu-sidebar custom-scrollbar">
          <li>
            <a href="#" class="nav-link"><span class="nav-text">Xem danh sách</span></a>
          </li>
          <li>
            <a href="#" class="nav-link"><span class="nav-text">Thêm Học phần mới</span></a>
          </li>
        </ul>
      </li>
      <li>
        <a href="javascript:void(0)" class="nav-link">
          <i class="fas fa-user-friends me-2 fs-5"></i>
          <span class="nav-text">Quản lý Người dùng</span>
          <i class="fas fa-angle-down float-end d-inline-block mt-1"></i>
        </a>
        <ul class="submenu-sidebar custom-scrollbar">
          <li>
            <a href="#" class="nav-link"><span class="nav-text">Xem danh sách</span></a>
          </li>
          <li>
            <a href="#" class="nav-link"><span class="nav-text">Thêm Người dùng mới</span></a>
          </li>
        </ul>
      </li>
      <li>
        <a href="javascript:void(0)" class="nav-link">
          <i class="fas fa-bars me-2 fs-5"></i>
          <span class="nav-text">Quản lý Menu</span>
          <i class="fas fa-angle-down float-end d-inline-block mt-1"></i>
        </a>
        <ul class="submenu-sidebar custom-scrollbar">
          <li>
            <a href="{{ route('menu.index') }}" class="nav-link"><span class="nav-text">Xem danh sách</span></a>
          </li>
          <li>
            <a href="{{ route('menu.create') }}" class="nav-link"><span class="nav-text">Thêm Menu mới</span></a>
          </li>
        </ul>
      </li>
    </ul>
  </div>
</div>
