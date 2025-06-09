@extends('layouts.auth')

@section('title', 'Đăng nhập')

@section('form-title')
  <h2 class="text-center">Đăng nhập</h2>
@endsection

@section('form-content')
  <form action="{{ route('dang-nhap') }}" method="POST">
    @csrf
    {{-- @if ($errors->any())
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ $errors->first() }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif --}}
    @if (session('success'))
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif
    <div class="mb-3">
      <label for="email" class="form-label">Email:</label>
      <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}">
      @error('email')
        <small class="text-danger">{{ $message }}</small>
      @enderror
    </div>
    <div class="mb-3">
      <label for="password" class="form-label">Mật khẩu:</label>
      <input type="password" class="form-control" id="password" name="mat_khau">
      @error('mat_khau')
        <small class="text-danger">{{ $message }}</small>
      @enderror
    </div>
    <div class="mb-3 clear-fix">
      <input type="checkbox" name="ghi_nho_dang_nhap" id="remember-me-btn" class="form-check-input">
      <label for="remember-me-btn" class="form-check-label">Ghi nhớ đăng nhập</label>
      <a href="{{ route('quen-mat-khau') }}" class="float-end">Quên mật khẩu?</a>
    </div>
    <button class="btn btn-primary form-control">Đăng nhập</button>
  </form>
@endsection

@section('styles')
  <link rel="stylesheet" href="{{ asset('modules/auth/css/login.css') }}">
@endsection
