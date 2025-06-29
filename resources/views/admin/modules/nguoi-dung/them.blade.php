@extends('layouts.admin')

@section('content')
    <div class="col bg-light p-4 overflow-auto custom-scrollbar">
        <h2 class="mb-4">Thêm người dùng mới</h2>

        <a href="{{ route('nguoi-dung.index') }}" class="btn btn-outline-secondary mb-4">
            <i class="fas fa-arrow-alt-circle-left me-2"></i>Danh sách người dùng
        </a>

        <div class="card shadow-sm">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">Quản lý người dùng</h5>
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
                            aria-controls="form-import" aria-selected="false">Import từ file</a>
                    </li>

                </ul>

                <!-- Tab content -->
                <div class="tab-content mt-3" id="userTabContent">
                    <!-- Form nhập -->
                    <div class="tab-pane fade show active" id="form-input" role="tabpanel" aria-labelledby="form-tab">
                        <form method="POST" action="{{ route('nguoi-dung.xu-ly-them') }}">
                            @csrf
                            <div class="form-group mb-3">
                                <label>Họ tên</label>
                                <input type="text" name="ho_ten" class="form-control" required>
                            </div>
                            <div class="form-group mb-3">
                                <label>Email</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>
                            <div class="form-group mb-3">
                                <label>Số điện thoại</label>
                                <input type="text" name="sdt" class="form-control">
                            </div>
                            <div class="form-group mb-3">
                                <label>Vai trò</label>
                                <select name="vai_tro" class="form-control" required>
                                    <option value="Giảng viên">Giảng viên</option>
                                    <option value="Sinh viên">Sinh viên</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-success mt-2">Thêm người dùng</button>
                        </form>
                    </div>

                    <!-- Form import -->
                    <div class="tab-pane fade" id="form-import" role="tabpanel" aria-labelledby="import-tab">
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
                            <button type="submit" class="btn btn-primary">Import</button>
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
    <script src="{{ asset('modules/nguoi-dung/js/danh-sach.js') }}"></script>
    <script>
        function updateFileName(input) {
            const fileName = input.files.length > 0 ? input.files[0].name : 'Chưa chọn file';
            document.getElementById('file-name').textContent = fileName;
        }
    </script>
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
