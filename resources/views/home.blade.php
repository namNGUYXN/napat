@extends('layouts.app')

@section('title', 'Trang chủ')

@section('content')
  <!-- Main Content -->
  <div class="col bg-light p-4 overflow-auto custom-scrollbar">
    <div class="home-banner">
      <img src="{{ asset('images/home-banner.jpg') }}" class="img-fluid" alt="">
    </div>

    <div class="department-list mt-5">
      <h3 class="mb-3">Danh sách khoa</h3>

      <div class="container-fluid px-0">
        <div class="row">
          <div class="col-12">
            <ul class="list-unstyled">
              <li class="department-item">
                <a href="javascript:void(0)"
                  class="d-block bg-info-subtle text-info-emphasis py-2 px-3 text-decoration-none department-toggle">
                  Khoa Công Nghệ Thông Tin
                  <i class="fas fa-angle-down ms-1"></i>
                </a>
                <ul class="department-submenu-sidebar bg-info-subtle list-unstyled ps-5">
                  <li>
                    <a href="#" class="d-block text-info-emphasis py-2 text-decoration-none">Mạng Máy Tính</a>
                  </li>
                  <li>
                    <a href="#" class="d-block text-info-emphasis py-2 text-decoration-none">Cơ Sở Dữ Liệu</a>
                  </li>
                  <li>
                    <a href="#" class="d-block text-info-emphasis py-2 text-decoration-none">Thiết Kế Website</a>
                  </li>
                  <li>
                    <a href="#" class="d-block text-info-emphasis py-2 text-decoration-none">Cấu Trúc Dữ Liệu &
                      Giải Thuật</a>
                  </li>
                </ul>
              </li>
              <li class="department-item">
                <a href="#" class="d-block bg-info-subtle text-info-emphasis py-2 px-3 text-decoration-none">Khoa
                  Cơ Khí</a>
              </li>
              <li class="department-item">
                <a href="#" class="d-block bg-info-subtle text-info-emphasis py-2 px-3 text-decoration-none">Khoa
                  Điện Tử</a>
              </li>
              <li class="department-item">
                <a href="javascript:void(0)"
                  class="d-block bg-info-subtle text-info-emphasis py-2 px-3 text-decoration-none department-toggle">
                  Khoa Giáo Dục Đại Cương
                  <i class="fas fa-angle-down ms-1"></i>
                </a>
                <ul class="department-submenu-sidebar bg-info-subtle list-unstyled ps-5">
                  <li>
                    <a href="#" class="d-block text-info-emphasis py-2 text-decoration-none">Tiếng Anh 1</a>
                  </li>
                  <li>
                    <a href="#" class="d-block text-info-emphasis py-2 text-decoration-none">Tiếng Anh 2</a>
                  </li>
                  <li>
                    <a href="#" class="d-block text-info-emphasis py-2 text-decoration-none">Toán Cao Cấp</a>
                  </li>
                </ul>
              </li>
              <li class="department-item">
                <a href="#" class="d-block bg-info-subtle text-info-emphasis py-2 px-3 text-decoration-none">Khoa
                  Công Nghệ Ô Tô</a>
              </li>
              <li class="department-item">
                <a href="#" class="d-block bg-info-subtle text-info-emphasis py-2 px-3 text-decoration-none">Khoa
                  Điện Lạnh</a>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('modules/home/css/home.css') }}">
@endsection

@section('scripts')
    <script src="{{ asset('modules/home/js/home.js') }}"></script>
@endsection
