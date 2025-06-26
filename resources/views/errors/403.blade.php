@extends('layouts.error')

@section('title', '403')

@php
  $url = session('vai_tro') === "Admin" ? url('/admin') : session()->has('vai_tro') ? url('/') : url('/dang-nhap');
@endphp

@section('content')
  <div class="text-center">
    <h1>403 - Bạn không được phép truy cập vào đây</h1>
    <p>Trang này đã ngăn chặn việc truy cập trái phép.</p>
    <a href="{{ $url }}" class="btn btn-info">Về trang chủ</a>
  </div>
@endsection
