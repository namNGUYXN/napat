@extends('layouts.error')

@section('title', '404')

@php
  $url = session('vai_tro') === "Admin" ? url('/admin') : session()->has('vai_tro') ? url('/') : url('/dang-nhap');
@endphp

@section('content')
  <div class="text-center">
    <h1>404 - Không tìm thấy trang</h1>
    <p>Trang bạn tìm không tồn tại hoặc đã bị xóa.</p>
    <a href="{{ $url }}" class="btn btn-info">Về trang chủ</a>
  </div>
@endsection
