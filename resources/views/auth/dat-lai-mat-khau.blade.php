@extends('layouts.auth')

@section('title', 'Đặt lại mật khẩu')

@section('form-title')
  <h2 class="text-center">Đặt lại mật khẩu</h2>
@endsection

@section('form-content')
  <form action="{{ route('dat-lai-mat-khau') }}" method="POST">
    @csrf
    <input type="hidden" name="token" value="{{ $token }}">
    <input type="hidden" name="email" value="{{ $email }}">
    <div class="mb-3">
      <label for="password" class="form-label">Nhập mật khẩu mới:</label>
      <div class="input-group">
        <input type="password" class="form-control" id="password" name="mat_khau">
        <button class="btn btn-outline-secondary toggle-password" type="button">
          <i class="fas fa-eye-slash toggle-icon"></i>
        </button>
      </div>
    </div>
    <div class="mb-3">
      <label for="password-confirmation" class="form-label">Nhập lại mật khẩu mới:</label>
      <div class="input-group">
        <input type="password" class="form-control" id="password-confirmation" name="mat_khau_confirmation">
        <button class="btn btn-outline-secondary toggle-password" type="button">
          <i class="fas fa-eye-slash toggle-icon"></i>
        </button>
      </div>
      @error('mat_khau')
        <small class="text-danger">{{ $message }}</small>
      @enderror
    </div>
    <button class="btn btn-primary form-control">Đặt lại mật khẩu</button>
  </form>
@endsection

@section('styles')
  <link rel="stylesheet" href="{{ asset('modules/auth/css/login.css') }}">
@endsection
