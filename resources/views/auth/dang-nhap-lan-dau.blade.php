@extends('layouts.auth')

@section('title', 'Thiết lập mật khẩu')

@section('form-title')
    <h2 class="text-center">Đăng nhập</h2>
@endsection

@section('form-content')
    <form action="{{ route('doi-mat-khau-lan-dau') }}" method="POST">
        @csrf
        @if (session('message'))
            <div class="alert alert-{{ session('status') }} alert-dismissible fade show" role="alert">
                {{ session('message') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <div class="mb-3">
            <label for="password" class="form-label">Mật khẩu:</label>
            <div class="input-group">
                <input type="password" class="form-control" id="mat_khau" name="mat_khau">
                <button class="btn btn-outline-secondary toggle-password" type="button">
                    <i class="fas fa-eye-slash toggle-icon"></i>
                </button>
            </div>
            @error('mat_khau')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        <button class="btn btn-primary form-control">Đăng nhập</button>
    </form>
@endsection
