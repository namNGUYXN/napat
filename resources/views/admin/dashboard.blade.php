@extends('layouts.admin')

@section('content')
  <!-- Main Content -->
  <div class="col bg-light p-4 overflow-auto custom-scrollbar">
    <h2 class="mb-3">Tổng quan hệ thống</h2>

    <div class="row g-4 justify-content-center">
      <div class="col-xl-3 col-md-6 col-sm-12">
        <div class="card card-custom card-faculty text-white h-100">
          <div class="card-body d-flex align-items-center">
            <i class="fas fa-building card-icon"></i>
            <div>
              <div class="card-title fs-5">Tổng số Khoa</div>
              <div class="card-value">{{ $tongSoKhoa }}</div>
            </div>
          </div>
          {{-- <div class="card-footer bg-transparent border-0 text-end">
            <a href="#" class="text-white-50 text-decoration-none">Xem chi tiết <i
                class="fas fa-arrow-circle-right"></i></a>
          </div> --}}
        </div>
      </div>

      <div class="col-xl-3 col-md-6 col-sm-12">
        <div class="card card-custom card-student text-white h-100">
          <div class="card-body d-flex align-items-center">
            <i class="fas fa-user-graduate card-icon"></i>
            <div>
              <div class="card-title fs-5">Tổng số Sinh viên</div>
              <div class="card-value">{{ $tongSoSinhVien }}</div>
            </div>
          </div>
          {{-- <div class="card-footer bg-transparent border-0 text-end">
            <a href="#" class="text-white-50 text-decoration-none">Xem chi tiết <i
                class="fas fa-arrow-circle-right"></i></a>
          </div> --}}
        </div>
      </div>

      <div class="col-xl-3 col-md-6 col-sm-12">
        <div class="card card-custom card-lecturer text-white h-100">
          <div class="card-body d-flex align-items-center">
            <i class="fas fa-chalkboard-teacher card-icon"></i>
            <div>
              <div class="card-title fs-5">Tổng số Giảng viên</div>
              <div class="card-value">{{ $tongSoGiangVien }}</div>
            </div>
          </div>
          {{-- <div class="card-footer bg-transparent border-0 text-end">
            <a href="#" class="text-white-50 text-decoration-none">Xem chi tiết <i
                class="fas fa-arrow-circle-right"></i></a>
          </div> --}}
        </div>
      </div>

      <div class="col-xl-3 col-md-6 col-sm-12">
        <div class="card card-custom card-course text-white h-100">
          <div class="card-body d-flex align-items-center">
            <i class="fas fa-book-open card-icon"></i>
            <div>
              <div class="card-title fs-5">Tổng số Bài giảng</div>
              <div class="card-value">{{ $tongSoBaiGiang }}</div>
            </div>
          </div>
          {{-- <div class="card-footer bg-transparent border-0 text-end">
            <a href="#" class="text-white-50 text-decoration-none">Xem chi tiết <i
                class="fas fa-arrow-circle-right"></i></a>
          </div> --}}
        </div>
      </div>
    </div>
  </div>
@endsection

@section('styles')
  <link rel="stylesheet" href="{{ asset('modules/dashboard/css/dashboard.css') }}">
@endsection
