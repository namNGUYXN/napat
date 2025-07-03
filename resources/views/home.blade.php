@extends('layouts.app')

@section('title', 'Trang chủ')

@section('content')
  <!-- Main Content -->
  <div class="col bg-light p-4 overflow-auto custom-scrollbar">
    <div class="home-banner">
      <img src="{{ asset('images/home-banner.jpg') }}" class="img-fluid" alt="">
    </div>

    <div class="department-list mt-5">
      <h3 class="mb-3">Danh sách khoa</h3>

      <div class="container-fluid px-0">
        <div class="row">
          <div class="col-12">
            <ul class="list-unstyled">
              @foreach ($dsKhoa as $khoa)
                <li class="department-item">
                  <a href="{{ route('lop-hoc.index', $khoa->slug) }}" class="d-block bg-info-subtle text-info-emphasis py-2 px-3 text-decoration-none">
                    {{ $khoa->ten }}
                  </a>
                </li>
              @endforeach
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('styles')
  <link rel="stylesheet" href="{{ asset('modules/home/css/home.css') }}">
@endsection

@section('scripts')
  <script src="{{ asset('modules/home/js/home.js') }}"></script>
@endsection
