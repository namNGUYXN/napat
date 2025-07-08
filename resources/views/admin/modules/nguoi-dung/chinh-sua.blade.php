@extends('layouts.admin')

@section('content')
    <div class="col bg-light p-4 overflow-auto custom-scrollbar">
        <h2 class="mb-4">Chỉnh sửa người dùng</h2>

        <a href="{{ route('nguoi-dung.index') }}" class="btn btn-outline-secondary mb-4">
            <i class="fas fa-arrow-alt-circle-left me-2"></i>Danh sách người dùng
        </a>
        <div class="card shadow-sm">
            
            <div class="card-body">
                <form method="POST" action="{{ route('nguoi-dung.cap-nhat', $nguoiDung->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label>Họ tên</label>
                        <input type="text" name="ho_ten" class="form-control @error('ho_ten') is-invalid @enderror"
                            value="{{ old('ho_ten', $nguoiDung->ho_ten) }}">
                        @error('ho_ten')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email', $nguoiDung->email) }}">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label>Số điện thoại</label>
                        <input type="text" name="sdt" class="form-control @error('sdt') is-invalid @enderror"
                            value="{{ old('sdt', $nguoiDung->sdt) }}">
                        @error('sdt')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label>Vai trò</label>
                        <select name="vai_tro" class="form-select">
                            <option value="Giảng viên" {{ $nguoiDung->vai_tro == 'Giảng viên' ? 'selected' : '' }}>Giảng
                                viên</option>
                            <option value="Sinh viên" {{ $nguoiDung->vai_tro == 'Sinh viên' ? 'selected' : '' }}>Sinh viên
                            </option>
                        </select>
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" name="is_active" value="1"
                            {{ $nguoiDung->is_active ? 'checked' : '' }}>
                        <label class="form-check-label">Kích hoạt</label>
                    </div>

                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('styles')
@endsection

@section('scripts')
    <script src="{{ asset('modules/nguoi-dung/js/danh-sach.js') }}"></script>
@endsection
