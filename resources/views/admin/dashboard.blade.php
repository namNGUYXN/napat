@extends('layouts.admin')

@section('content')
  <!-- Main Content -->
  <div class="col bg-light p-4 overflow-auto custom-scrollbar">
    <h1>Trang dashboard (Admin)</h1>
    <h3>Xin chào {{ session('ho_ten') }}!</h3>
  </div>
@endsection
