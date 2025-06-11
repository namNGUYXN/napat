@extends('layouts.admin')

@section('content')
  <!-- Main Content -->
  <div class="col bg-light p-4 overflow-auto custom-scrollbar">
    <h2 class="mb-3">Quản lý menu</h2>

    @if (session('message'))
      <div class="alert alert-{{ session('status') }} alert-dismissible fade show" role="alert">
        {{ session('message') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

    <div class="card shadow-sm">
      <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Danh sách Menu</h5>
        <a href="{{ route('them-menu') }}" class="btn btn-light btn-sm">
          <i class="fas fa-plus-circle me-2"></i>Thêm Menu mới
        </a>
      </div>
      <div class="card-body">

        <div class="table-responsive">
          <table class="table table-hover table-striped">
            <caption>Có {{ count($listMenu) }} bản ghi menu</caption>
            <thead>
              <tr>
                <th scope="col">Thứ tự</th>
                <th scope="col">Tên menu</th>
                <th scope="col">Loại menu</th>
                <th scope="col" class="text-center">Thao tác</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($listMenu as $menu)
                <tr>
                  <th scope="row" class="align-middle">
                    <div style="max-width: 80px;">
                      <input type="number" name="" id="" value="{{ $menu['thu_tu'] }}"
                        class="form-control text-center no-spinner">
                    </div>
                  </th>
                  <td class="align-middle">{{ $menu['ten'] }}</td>
                  <td class="align-middle">
                    <div class="d-inline-block text-white bg-success px-2 py-1 rounded">
                      {{ $menu['loai_menu'] }}
                    </div>
                  </td>
                  <td class="align-middle text-center">
                    <a href="{{ route('giao-dien-chinh-sua-menu', $menu['id']) }}" class="btn btn-warning btn-sm me-1">
                      <i class="fas fa-edit"></i>
                    </a>
                    <button class="btn btn-danger btn-sm">
                      <i class="fas fa-trash-alt"></i>
                    </button>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
@endsection
