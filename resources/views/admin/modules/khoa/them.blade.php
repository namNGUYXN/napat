@extends('layouts.admin')

@section('content')
    <div class="col bg-light p-4 overflow-auto custom-scrollbar">
        <h2 class="mb-3">Thêm Khoa mới</h2>

        <!-- Nút quay về danh sách Khoa -->
        <a href="{{ route('khoa.index') }}" class="btn btn-outline-secondary mb-4">
            <i class="fas fa-arrow-alt-circle-left me-2"></i>Danh sách Khoa
        </a>

        <div class="card shadow-sm">
            <div class="card-body">
                <!-- Tabs -->
                <ul class="nav nav-tabs" id="khoaTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link fw-bold {{ session('active_tab', 'manual') == 'manual' ? 'active' : '' }}"
                            id="manual-tab" data-bs-toggle="tab" href="#manual" role="tab" aria-controls="manual"
                            aria-selected="{{ session('active_tab', 'manual') == 'manual' ? 'true' : 'false' }}">
                            Nhập thủ công
                        </a>

                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link fw-bold {{ session('active_tab') == 'import' ? 'active' : '' }}" id="import-tab"
                            data-bs-toggle="tab" href="#import" role="tab" aria-controls="import"
                            aria-selected="{{ session('active_tab') == 'import' ? 'true' : 'false' }}">
                            Import từ file
                        </a>
                    </li>
                </ul>

                <div class="tab-content mt-3" id="khoaTabContent">
                    <!-- Manual -->
                    <div class="tab-pane {{ session('active_tab', 'manual') == 'manual' ? 'show active' : '' }}"
                        id="manual" role="tabpanel" aria-labelledby="manual-tab">
                        <form method="POST" action="{{ route('khoa.them') }}" id="form-input">
                            @csrf
                            <div class="form-group mb-3">
                                <label for="ma">Mã Khoa <span class="text-danger">*</span></label>
                                <input type="text" id="ma" name="ma"
                                    class="form-control @error('ma') is-invalid @enderror" value="{{ old('ma') }}">
                                <div class="invalid-feedback fw-bold">{{ $errors->first('ma') }}</div>
                            </div>
                            <div class="form-group mb-3">
                                <label for="ten">Tên Khoa <span class="text-danger">*</span></label>
                                <input type="text" id="ten" name="ten"
                                    class="form-control @error('ten') is-invalid @enderror" value="{{ old('ten') }}">
                                <div class="invalid-feedback fw-bold">{{ $errors->first('ten') }}</div>
                            </div>
                            <div class="form-group mb-3">
                                <label for="email">Email Khoa</label>
                                <input type="email" id="email" name="email"
                                    class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}">
                                <div class="invalid-feedback fw-bold">{{ $errors->first('email') }}</div>
                            </div>
                            <div class="form-group mb-3">
                                <label for="mo_ta_ngan">Mô tả ngắn</label>
                                <textarea id="mo_ta_ngan" name="mo_ta_ngan" rows="3"
                                    class="form-control @error('mo_ta_ngan') is-invalid @enderror">{{ old('mo_ta_ngan') }}</textarea>
                                <div class="invalid-feedback fw-bold">{{ $errors->first('mo_ta_ngan') }}</div>
                            </div>
                            <button type="submit" class="btn btn-success mt-2">Thêm Khoa</button>
                        </form>
                    </div>

                    <!-- Import -->
                    <div class="tab-pane {{ session('active_tab') == 'import' ? 'show active' : '' }}" id="import"
                        role="tabpanel" aria-labelledby="import-tab">

                        {{-- Thông báo lỗi --}}
                        @if (session('errors_import'))
                            @php $fails = session('errors_import'); @endphp
                            <div class="alert alert-danger d-flex justify-content-between align-items-center">
                                <div><strong>Dữ liệu lỗi:</strong> {{ count($fails) }} dòng</div>
                                <button class="btn btn-success btn-sm " data-bs-toggle="modal"
                                    data-bs-target="#importErrorModal">
                                    Xem chi tiết
                                </button>
                            </div>
                        @endif

                        {{-- Modal chi tiết lỗi --}}
                        @if (session('errors_import'))
                            <div class="modal fade" id="importErrorModal" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-lg modal-dialog-scrollable">
                                    <div class="modal-content">
                                        <div class="modal-header bg-danger text-white">
                                            <h5 class="modal-title">Chi tiết lỗi import</h5>
                                            <button type="button" class="btn-close btn-close-white"
                                                data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <ul class="list-group">
                                                @foreach ($fails as $f)
                                                    <li class="list-group-item">
                                                        <strong>Dòng {{ $f->row() }}:</strong>
                                                        <ul class="mb-0">
                                                            @foreach ($f->errors() as $err)
                                                                <li>{{ $err }}</li>
                                                            @endforeach
                                                        </ul>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- Nút hướng dẫn --}}
                        <div class="mb-2">
                            <button class="btn btn-outline-info btn-sm" data-bs-toggle="modal"
                                data-bs-target="#importGuideModal">
                                📘 Hướng dẫn Import
                            </button>
                        </div>

                        {{-- Modal hướng dẫn --}}
                        <div class="modal fade" id="importGuideModal" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary text-white">
                                        <h5 class="modal-title">Hướng dẫn Import file Excel</h5>
                                        <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p><strong>Cột bắt buộc:</strong></p>
                                        <ul>
                                            <li><code>Cột A</code>: Mã Khoa (bắt buộc)</li>
                                            <li><code>Cột B</code>: Tên Khoa (bắt buộc)</li>
                                            <li><code>Cột C</code>: Email (có thể để trống)</li>
                                            <li><code>Cột D</code>: Mô tả ngắn (có thể để trống)</li>
                                        </ul>
                                        <p>✔️ Đảm bảo không trùng mã, tên, email đúng định dạng nếu có.</p>
                                        <hr>
                                        <p><strong>File mẫu:</strong></p>
                                        <img src="{{ asset('images/guide-import-khoa-from-excel.png') }}"
                                            alt="Mẫu import" class="img-fluid rounded shadow-sm">
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Form import --}}
                        <form method="POST" action="{{ route('khoa.import') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group mb-3">
                                <label for="file_excel">Chọn file Excel (.xlsx, .xls, .csv)</label>
                                <div class="border rounded d-flex align-items-center justify-content-center p-4"
                                    style="cursor:pointer; height:140px;"
                                    onclick="document.getElementById('file_excel').click()">
                                    <span id="file-name" class="text-muted">Chưa chọn file</span>
                                </div>
                                <input type="file" name="file_excel" id="file_excel" class="d-none" required
                                    onchange="document.getElementById('file-name').textContent = this.files[0]?.name || 'Chưa chọn file'">
                            </div>
                            <button type="submit" class="btn btn-primary">Import</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('modules/khoa/js/them.js') }}"></script>
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
