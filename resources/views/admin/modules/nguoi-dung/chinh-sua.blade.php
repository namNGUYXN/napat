@extends('layouts.admin')

@section('content')
    <div class="col bg-light p-4 overflow-auto custom-scrollbar">
        <h2 class="mb-3">Chỉnh sửa người dùng</h2>

        <a href="{{ route('nguoi-dung.index') }}" class="btn btn-outline-secondary mb-4">
            <i class="fas fa-arrow-alt-circle-left me-2"></i>Danh sách người dùng
        </a>
        <div class="card shadow-sm">

            <div class="card-body">
                <form method="POST" id="form-input" action="{{ route('nguoi-dung.cap-nhat', $nguoiDung->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Họ tên</label>
                        <input type="text" name="ho_ten" class="form-control @error('ho_ten') is-invalid @enderror"
                            value="{{ old('ho_ten', $nguoiDung->ho_ten) }}">
                        <div class="invalid-feedback">
                            @error('ho_ten')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Email</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email', $nguoiDung->email) }}" {{ $nguoiDung->is_logged ? 'readonly' : '' }}>

                        <div class="invalid-feedback"> @error('email')
                                {{ $message }}
                            @enderror
                        </div>


                        @if ($nguoiDung->is_logged)
                            <div class="alert alert-warning mt-2 py-1 px-2 small mb-0">
                                <i class="bi bi-exclamation-circle me-1"></i>
                                Tài khoản đã đăng nhập – không thể thay đổi email.
                            </div>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Số điện thoại</label>
                        <input type="text" name="sdt" class="form-control @error('sdt') is-invalid @enderror"
                            value="{{ old('sdt', $nguoiDung->sdt) }}">
                        <div class="invalid-feedback">
                            @error('sdt')
                                {{ $message }}
                            @enderror
                        </div>

                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Vai trò</label>
                        <select name="vai_tro" class="form-select @error('vai_tro') is-invalid @enderror">
                            <option value="Giảng viên"
                                {{ old('vai_tro', $nguoiDung->vai_tro) == 'Giảng viên' ? 'selected' : '' }}>Giảng viên
                            </option>
                            <option value="Sinh viên"
                                {{ old('vai_tro', $nguoiDung->vai_tro) == 'Sinh viên' ? 'selected' : '' }}>Sinh viên
                            </option>
                        </select>
                        <div class="invalid-feedback">
                            @error('vai_tro')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-save me-1"></i> Cập nhật
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('styles')
@endsection

@section('scripts')
    <script src="{{ asset('modules/nguoi-dung/js/chinh-sua.js') }}"></script>
@endsection
