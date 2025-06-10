<header class="container-fluid">
  <div class="row bg-info text-white py-3 align-items-center">
    <div class="col-auto d-md-none">
      <button class="btn btn-light" data-bs-toggle="offcanvas" data-bs-target="#sidebarOffcanvas">☰</button>
    </div>
    <div class="col d-flex justify-content-between align-items-center">
      <a href="#" class="text-white fs-4 fw-semibold text-decoration-none">NAPAT E-Learning</a>
      <div class="dropdown">
        <button class="btn btn-secondary dropdown-toggle remove-arrow-down rounded-circle" type="button"
          id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="fas fa-user"></i>
        </button>
        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
          <li><a class="dropdown-item" href="#">Tài khoản của tôi</a></li>
          <li>
            <hr class="dropdown-divider">
          </li>
          <li>
            <form action="{{ route('dang-xuat') }}" method="post">
              @csrf
              <button class="dropdown-item">Đăng xuất</button>
            </form>
          </li>
        </ul>
      </div>
    </div>
  </div>
</header>
