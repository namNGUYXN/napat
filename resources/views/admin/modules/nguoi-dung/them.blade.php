@extends('layouts.admin')

@section('content')
    <div class="col bg-light p-4 overflow-auto custom-scrollbar">

        <a href="{{ route('nguoi-dung.index') }}" class="btn btn-outline-secondary mb-4">
            <i class="fas fa-arrow-alt-circle-left me-2"></i>Danh sách người dùng
        </a>

        <div class="card shadow-sm">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">Thêm người dùng mới</h5>
            </div>
            <div class="card-body">
                <!-- Tabs -->
                <ul class="nav nav-tabs" id="userTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active fw-bold" id="form-tab" data-bs-toggle="tab" href="#form-input"
                            role="tab" aria-controls="form-input" aria-selected="true">Nhập thủ công</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link fw-bold" id="import-tab" data-bs-toggle="tab" href="#form-import" role="tab"
                            aria-controls="form-import" aria-selected="false">Thêm từ file</a>
                    </li>

                </ul>

                <!-- Tab content -->
                <div class="tab-content mt-3" id="userTabContent">
                    <!-- Form nhập -->
                    <div class="tab-pane fade show active" id="form-input" role="tabpanel" aria-labelledby="form-tab">
                        <form method="POST" action="{{ route('nguoi-dung.xu-ly-them') }}">
                            @csrf
                            {{-- Họ tên --}}
                            <div class="form-group mb-3">
                                <label>Họ tên</label>
                                <input type="text" name="ho_ten"
                                    class="form-control @error('ho_ten') is-invalid @enderror" value="{{ old('ho_ten') }}">
                                <div id="ho_ten_error" class="invalid-feedback d-none"></div>
                                @error('ho_ten')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Email --}}
                            <div class="form-group mb-3">
                                <label>Email</label>
                                <input type="email" name="email"
                                    class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}">
                                <div id="email_error" class="invalid-feedback d-none"></div>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Số điện thoại --}}
                            <div class="form-group mb-3">
                                <label>Số điện thoại</label>
                                <input type="text" name="sdt" class="form-control @error('sdt') is-invalid @enderror"
                                    value="{{ old('sdt') }}">
                                <div id="sdt_error" class="invalid-feedback d-none"></div>
                                @error('sdt')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Vai trò --}}
                            <div class="form-group mb-3">
                                <label>Vai trò</label>
                                <select name="vai_tro" class="form-control @error('vai_tro') is-invalid @enderror">
                                    <option value="">-- Chọn vai trò --</option>
                                    <option value="Giảng viên" {{ old('vai_tro') == 'Giảng viên' ? 'selected' : '' }}>Giảng
                                        viên</option>
                                    <option value="Sinh viên" {{ old('vai_tro') == 'Sinh viên' ? 'selected' : '' }}>Sinh
                                        viên</option>
                                </select>
                                <div id="vai_tro_error" class="invalid-feedback d-none"></div>
                                @error('vai_tro')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-success mt-2">Thêm người dùng</button>
                        </form>
                    </div>

                    <!-- Form import -->
                    <div class="tab-pane fade" id="form-import" role="tabpanel" aria-labelledby="import-tab">
                        {{-- Hiển thị lỗi nếu có --}}
                        @if (session('errors_import'))
                            <div class="alert alert-danger">
                                <strong>Dữ liệu bị lỗi:</strong>
                                <ul class="mb-0">
                                    @foreach (session('errors_import') as $failure)
                                        <li>
                                            <strong>Dòng {{ $failure->row() }}:</strong>
                                            <ul>
                                                @foreach ($failure->errors() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('nguoi-dung.import') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group mb-3">
                                <label for="file_excel">Chọn file Excel (.xlsx, .xls, .csv)</label>
                                <div class="custom-file-upload border rounded d-flex align-items-center justify-content-center p-3"
                                    style="cursor: pointer; height: 150px;"
                                    onclick="document.getElementById('file_excel').click();">
                                    <span id="file-name" class="text-muted">Chưa chọn file</span>
                                </div>
                                <input type="file" name="file_excel" id="file_excel" class="d-none" required
                                    onchange="updateFileName(this)">
                            </div>
                            <button type="submit" class="btn btn-primary">Thêm</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>


    </div>
@endsection

@section('styles')
@endsection

@section('scripts')
    <script src="{{ asset('modules/nguoi-dung/js/them.js') }}"></script>
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
                icon: '{{ session('icon') }}',
                title: '{{ session('message') }}'
            });
        </script>
    @endif
@endsection
