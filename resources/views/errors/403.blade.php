@extends('layouts.error')

@section('title', '403')

@php
  $url = session('vai_tro') === "Admin" ? url('/admin') : session()->has('vai_tro') ? url('/') : url('/dang-nhap');
@endphp

@section('content')
  <div class="text-center">
    <h1>{{ $message }}</h1>
    {{-- <p>Trang này đã ngăn chặn việc truy cập trái phép.</p> --}}
    <a href="{{ $url }}" class="btn text-light mt-4" style="background-color: coral;">Về trang chủ</a>
  </div>
@endsection
