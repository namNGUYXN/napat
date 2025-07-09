@extends('layouts.admin')

@section('content')
    <!-- Main Content -->
    <div class="col bg-light p-4 overflow-auto custom-scrollbar">
        <h2 class="mb-3">Quản lý Người dùng</h2>

        {{-- @if (session('message'))
      <div class="alert alert-{{ session('status') }} alert-dismissible fade show" role="alert">
        {{ session('message') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif --}}

        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Danh sách Người dùng</h5>
                <a href="{{ route('nguoi-dung.them') }}" class="btn btn-light btn-sm">
                    <i class="fas fa-plus-circle me-2"></i>Thêm người dùng mới
                </a>
            </div>
            <div class="card-body position-relative">
                <div class="container mt-4">
                    <div class="row mb-3">
                        <div class="col-12 col-md-3 mb-2 mb-md-0">
                            <select id="vaiTroSelect" class="form-select">
                                <option value="">Tất cả vai trò</option>
                                <option value="Giảng viên" {{ request('vai_tro') == 1 ? 'selected' : '' }}>Giảng viên
                                </option>
                                <option value="Sinh viên" {{ request('vai_tro') == 2 ? 'selected' : '' }}>Sinh viên</option>
                            </select>
                        </div>

                        <div class="col-12 col-md-5 mb-2 mb-md-0">
                            <input type="text" id="searchInput" class="form-control"
                                placeholder="Tìm kiếm theo tên, email, SĐT..." value="{{ request('keyword') }}">
                        </div>

                        <div class="col-12 col-md-2 mb-2 mb-md-0">
                            <select id="perPageSelect" class="form-select">
                                <option value="5">5 dòng</option>
                                <option value="10">10 dòng</option>
                                <option value="20">20 dòng</option>
                            </select>
                        </div>

                        <div class="col-12 col-md-2">
                            <button id="btnSearch" class="btn btn-primary w-100">Tìm</button>
                        </div>
                    </div>

                    <div id="nguoiDungTable">
                        @include('admin.partials.nguoi-dung._table', ['danhSach' => $danhSach])
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('modules/nguoi-dung/js/danh-sach.js') }}"></script>

    @if (session('message'))
        <script>
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                width: 'auto',
                showConfirmButton: false,
                timer: 3500,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.onmouseenter = Swal.stopTimer;
                    toast.onmouseleave = Swal.resumeTimer;
                }
            });

            Toast.fire({
                icon: '{{ session('icon') ?? 'success' }}',
                title: '{{ session('message') }}'
            });
        </script>
    @endif
@endsection
