@extends('layouts.admin')

@section('content')
    <div class="col bg-light p-4 overflow-auto custom-scrollbar">
        <h2 class="mb-3">Quản lý Khoa</h2>

        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Danh sách Khoa</h5>
                <a href="{{ route('khoa.them') }}" class="btn btn-light btn-sm">
                    <i class="fas fa-plus-circle me-2"></i>Thêm Khoa mới
                </a>
            </div>
            <div class="card-body position-relative">
                <div class="container mt-4">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <input type="text" id="searchInput" class="form-control"
                                placeholder="Tìm theo mã, tên, email..." value="{{ request('keyword') }}">
                        </div>
                        <div class="col-md-3">
                            <select id="perPageSelect" class="form-select">
                                <option value="5" {{ request('per_page') == 5 ? 'selected' : '' }}>5 dòng</option>
                                <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10 dòng</option>
                                <option value="20" {{ request('per_page') == 20 ? 'selected' : '' }}>20 dòng</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button id="btnSearch" class="btn btn-primary w-100">Tìm</button>
                        </div>
                    </div>

                    <div id="khoaTable">
                        @include('admin.partials.khoa._table', ['danhSach' => $danhSach])
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('modules/khoa/js/danh-sach.js') }}"></script>

    @if (session('message'))
        <script>
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: '{{ session('icon') ?? 'success' }}',
                title: '{{ session('message') }}',
                showConfirmButton: false,
                timer: 3000
            });
        </script>
    @endif
@endsection
