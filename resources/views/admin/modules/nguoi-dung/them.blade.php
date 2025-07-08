@extends('layouts.admin')

@section('content')
    <div class="col bg-light p-4 overflow-auto custom-scrollbar">
        <h2 class="mb-3">Thêm Người dùng mới</h2>

        <!--Nút quay về trang danh sách người dùng - Start -->
        <a href="{{ route('nguoi-dung.index') }}" class="btn btn-outline-secondary mb-4">
            <i class="fas fa-arrow-alt-circle-left me-2"></i>Danh sách người dùng
        </a>
        <!--Nút quay về trang danh sách người dùng - End -->

        <div class="card shadow-sm">

            <div class="card-body">
                <!-- Thẻ tab - Start -->
                <ul class="nav nav-tabs" id="userTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link fw-bold  {{ session('active_tab', 'manual') == 'manual' ? 'active' : '' }}"
                            id="form-tab" data-bs-toggle="tab" href="#form-input" role="tab" aria-controls="form-input"
                            aria-selected="{{ session('active_tab', 'manual') == 'manual' ? 'true' : 'false' }}">Nhập thủ
                            công</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link fw-bold {{ session('active_tab') == 'import' ? 'active' : '' }}" id="import-tab"
                            data-bs-toggle="tab" href="#form-import" role="tab" aria-controls="form-import"
                            aria-selected="{{ session('active_tab') == 'import' ? 'true' : 'false' }}">Thêm từ file</a>
                    </li>
                </ul>
                <!-- Thẻ tab - End -->

                <!-- Nội dung thẻ tab - Start -->
                <div class="tab-content mt-3" id="userTabContent">
                    <!--Tab nhập thủ công - Start-->
                    <div class="tab-pane {{ session('active_tab', 'manual') == 'manual' ? 'show active' : '' }}"
                        id="form-input" role="tabpanel" aria-labelledby="form-tab">
                        <form method="POST" action="{{ route('nguoi-dung.xu-ly-them') }}">
                            @csrf
                            {{-- Họ tên --}}
                            <div class="form-group mb-3">
                                <label>Họ tên</label>
                                <input type="text" name="ho_ten"
                                    class="form-control @error('ho_ten') is-invalid @enderror" value="{{ old('ho_ten') }}">
                                <div class="invalid-feedback {{ $errors->has('ho_ten') ? '' : 'd-none' }}">
                                    {{ $errors->first('ho_ten') }}
                                </div>
                            </div>

                            {{-- Email --}}
                            <div class="form-group mb-3">
                                <label>Email</label>
                                <input type="email" name="email"
                                    class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}">
                                <div class="invalid-feedback {{ $errors->has('email') ? '' : 'd-none' }}">
                                    {{ $errors->first('email') }}
                                </div>
                            </div>

                            {{-- Số điện thoại --}}
                            <div class="form-group mb-3">
                                <label>Số điện thoại</label>
                                <input type="text" name="sdt" class="form-control @error('sdt') is-invalid @enderror"
                                    value="{{ old('sdt') }}">
                                <div class="invalid-feedback {{ $errors->has('sdt') ? '' : 'd-none' }}">
                                    {{ $errors->first('sdt') }}
                                </div>
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
                                <div class="invalid-feedback {{ $errors->has('vai_tro') ? '' : 'd-none' }}">
                                    {{ $errors->first('vai_tro') }}
                                </div>
                            </div>
                            <button type="submit" class="btn btn-success mt-2">Thêm người dùng</button>
                        </form>
                    </div>
                    <!--Tab nhập thủ công - End-->

                    <!--Tab Import file - Start-->
                    <div class="tab-pane {{ session('active_tab') == 'import' ? 'show active' : '' }}" id="form-import"
                        role="tabpanel" aria-labelledby="import-tab">
                        <!--Thẻ hiển thị lỗi - Start-->
                        @if (session('errors_import'))
                            @php $failures = session('errors_import'); @endphp
                            <div class="alert alert-danger d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>Dữ liệu bị lỗi:</strong> {{ count($failures) }} dòng lỗi
                                </div>
                                <button class="btn btn-success btn-sm " data-bs-toggle="modal"
                                    data-bs-target="#importErrorModal">
                                    Xem chi tiết
                                </button>
                            </div>
                        @endif
                        @if (session('errors_import'))
                            @php $failures = session('errors_import'); @endphp

                            <div class="modal fade" id="importErrorModal" tabindex="-1"
                                aria-labelledby="importErrorModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg modal-dialog-scrollable">
                                    <div class="modal-content">
                                        <div class="modal-header bg-danger text-white">
                                            <h5 class="modal-title" id="importErrorModalLabel">Chi tiết lỗi import</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                                aria-label="Đóng"></button>
                                        </div>
                                        <div class="modal-body">
                                            <ul class="list-group">
                                                @foreach ($failures as $failure)
                                                    <li class="list-group-item">
                                                        <strong>Dòng {{ $failure->row() }}:</strong>
                                                        <ul class="mb-0">
                                                            @foreach ($failure->errors() as $error)
                                                                <li>{{ $error }}</li>
                                                            @endforeach
                                                        </ul>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Đóng</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <!--Thẻ hiển thị lỗi - End-->
                        <div class="d-flex justify-content-start mb-2">
                            <button type="button" class="btn btn-outline-info btn-sm" data-bs-toggle="modal"
                                data-bs-target="#importGuideModal">
                                📘 Hướng dẫn Import
                            </button>
                        </div>
                        <!-- Modal Hướng dẫn Import -->
                        <div class="modal fade" id="importGuideModal" tabindex="-1"
                            aria-labelledby="importGuideModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary text-white">
                                        <h5 class="modal-title" id="importGuideModalLabel">Hướng dẫn Import file Excel
                                        </h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                            aria-label="Đóng"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p>💡 <strong>Lưu ý khi Import:</strong></p>
                                        <ul>
                                            <li>File phải có định dạng <strong>.xlsx, .xls</strong> hoặc
                                                <strong>.csv</strong>.
                                            </li>
                                            <ul>
                                                <li><code>Cột A</code>: Họ và tên người dùng <strong>(bắt buộc
                                                        nhập)</strong></li>
                                                <li><code>Cột B</code>: Email <strong>(bắt buộc nhập)</strong></li>
                                                <li><code>Cột C</code>: Số điện thoại <em>(có thể bỏ trống)</em></li>
                                                <li><code>Cột D</code>: Vai trò <strong>(bắt buộc nhập)</strong> – chỉ chấp
                                                    nhận <code>Giảng viên</code> hoặc <code>Sinh viên</code></li>
                                            </ul>
                                            <li>Không được để trống các ô bắt buộc, Email phải hợp lệ và không trùng.</li>
                                        </ul>

                                        <hr>

                                        <p>📷 <strong>File mẫu Excel:</strong></p>
                                        <img src="{{ asset('images/guide-import-ds-nguoi-dung-from-excel.png') }}"
                                            alt="Mẫu file import" class="img-fluid rounded shadow">

                                        {{-- <p class="mt-3">Bạn có thể <a href="{{ asset('files/mau-import.xlsx') }}"
                                                download>tải file mẫu tại đây</a>.</p> --}}
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Đóng</button>
                                    </div>
                                </div>
                            </div>
                        </div>


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
                    <!--Tab Import file - End-->
                </div>
                <!-- Nội dung thẻ tab - End -->
            </div>
        </div>
    </div>
@endsection

@section('styles')
@endsection

@section('scripts')
    <script src="{{ asset('modules/nguoi-dung/js/them.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if (session('errors_import'))
                var triggerEl = document.querySelector('#import-tab');
                var tab = new bootstrap.Tab(triggerEl);
                tab.show();
            @endif
        });
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
