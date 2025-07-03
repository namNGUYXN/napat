@php
    use App\NguoiDung;

    $hinhAnh = NguoiDung::findOrFail(session('id_nguoi_dung'))->hinh_anh;
@endphp

<header class="container-fluid">
  <div class="row bg-info text-white py-3 pe-md-3 align-items-center">
    <div class="col-auto d-md-none">
      <button class="btn btn-light" data-bs-toggle="offcanvas" data-bs-target="#sidebarOffcanvas">☰</button>
    </div>
    <div class="col d-flex justify-content-between align-items-center">
      <a href="{{ route('dashboard') }}" class="text-white fs-4 fw-semibold text-decoration-none">NAPAT E-Learning</a>
      <div class="dropdown">
        <button class="dropdown-toggle" type="button" id="dropdownMenuButton1"
          data-bs-toggle="dropdown" aria-expanded="false">
          <img src="{{ asset('storage/' . $hinhAnh) }}" alt="Avt" width="40" height="40"
            class="border border-dark rounded-circle me-2">
          <span class="fw-medium">{{ session('ho_ten') }}</span>
        </button>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton1">
          <li><a class="dropdown-item" href="{{ route('tai-khoan.chi-tiet') }}">Tài khoản của tôi</a></li>
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
