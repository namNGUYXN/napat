<!-- Sidebar -->
<div id="sidebar" class="sidebar position-relative d-none d-md-block p-0 overflow-auto custom-scrollbar">
  <button id="toggleSidebar" class="btn btn-light btn-sm toggle-btn">
    <i class="fas fa-bars"></i>
  </button>
  <ul class="menu-sidebar mt-5">
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
          <a href="{{ route('list-menu') }}" class="nav-link"><span class="nav-text">Xem danh sách</span></a>
        </li>
        <li>
          <a href="{{ route('them-menu') }}" class="nav-link"><span class="nav-text">Thêm Menu mới</span></a>
        </li>
      </ul>
    </li>
  </ul>
</div>
