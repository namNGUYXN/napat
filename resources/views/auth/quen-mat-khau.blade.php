@extends('layouts.auth')

@section('title', 'Quên mật khẩu')

@section('form-title')
  <h2 class="text-center">Quên mật khẩu</h2>
@endsection

@section('form-content')
  <form action="{{ route('lien-ket-dlmk') }}" method="POST">
    @csrf
    @if(session('message'))
      <div class="alert alert-{{ session('status') }} alert-dismissible fade show" role="alert">
        {{ session('message') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif
    @if (session('success'))
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif
    <div class="mb-3">
      <label for="email" class="form-label">Nhập email của bạn:</label>
      <input type="email" class="form-control" id="email" name="email">
      @error('email')
        <small class="text-danger">{{ $message }}</small>
      @enderror
    </div>
    <button class="btn btn-primary form-control">Gửi liên kết đặt lại mật khẩu</button>
  </form>
@endsection

@section('styles')
  <link rel="stylesheet" href="{{ asset('modules/auth/css/login.css') }}">
@endsection
