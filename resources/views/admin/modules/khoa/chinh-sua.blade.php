@extends('layouts.admin')

@section('content')
    <div class="col bg-light p-3 overflow-auto custom-scrollbar">
        <h2 class="mb-2">Chỉnh sửa khoa</h2>

        <a href="{{ route('khoa.index') }}" class="btn btn-outline-secondary mb-2">
            <i class="fas fa-arrow-alt-circle-left me-2"></i>Danh sách khoa
        </a>

        <div class="card shadow-sm">
            <div class="card-body">
                <form method="POST" action="{{ route('khoa.cap-nhat', $khoa->id) }}">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="id" name="id"
                        class="form-control @error('id') is-invalid @enderror" value="{{ old('id', $khoa->id) }}">

                    <div class="mb-3">
                        <label for="ten">Tên khoa</label>
                        <input type="text" id="ten" name="ten"
                            class="form-control @error('ten') is-invalid @enderror" value="{{ old('ten', $khoa->ten) }}">
                        @error('ten')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="ma">Mã khoa</label>
                        <input type="text" id="ma" name="ma"
                            class="form-control @error('ma') is-invalid @enderror" value="{{ old('ma', $khoa->ma) }}">
                        @error('ma')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email">Email</label>
                        <input type="text" id="email" name="email"
                            class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email', $khoa->email) }}">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="mo_ta_ngan">Mô tả</label>
                        <textarea id="mo_ta_ngan" name="mo_ta_ngan" rows="4"
                            class="form-control @error('mo_ta_ngan') is-invalid @enderror">{{ old('mo_ta_ngan', $khoa->mo_ta_ngan) }}</textarea>
                        @error('mo_ta_ngan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('styles')
@endsection
