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
        <a href="{{ route('menu.create') }}" class="btn btn-light btn-sm">
          <i class="fas fa-plus-circle me-2"></i>Thêm Menu mới
        </a>
      </div>
      <div class="card-body">

        <div class="table-responsive">
          <table class="table table-hover caption-top table-striped">
            <caption>Có {{ count($listMenu) }} bản ghi menu</caption>
            <thead>
              <tr>
                <th scope="col">
                  <input type="checkbox" class="form-check-input" name="" id="check-all">
                </th>
                <th scope="col">Thứ tự</th>
                <th scope="col">Tên menu</th>
                <th scope="col">Loại menu</th>
                <th scope="col" class="text-center">Thao tác</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($listMenu as $menu)
                <tr>
                  <th scope="row">
                    <input type="checkbox" class="form-check-input row-checkbox" name="" id="">
                  </th>
                  <td class="align-middle">
                    <div style="max-width: 60px;">
                      <input type="number" name="" id="" value="{{ $menu['thu_tu'] }}"
                        class="form-control text-center no-spinner">
                    </div>
                  </td>
                  <td class="align-middle">{{ $menu['ten'] }}</td>
                  <td class="align-middle">
                    <div class="d-inline-block text-white bg-success px-2 py-1 rounded">
                      {{ $menu['loai_menu'] }}
                    </div>
                  </td>
                  <td class="align-middle text-center">
                    <a href="{{ route('menu.edit', $menu['id']) }}" class="btn btn-warning btn-sm me-1">
                      <i class="fas fa-edit"></i>
                    </a>
                    <form action="{{ route('menu.delete', $menu['id']) }}" method="POST" class="d-inline-block"
                      onsubmit="return confirm('Bạn có chắc chắn muốn xóa menu này?');">
                      @csrf
                      @method('DELETE')
                      <button class="btn btn-danger btn-sm">
                        <i class="fas fa-trash-alt"></i>
                      </button>
                    </form>

                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="5" class="text-center">
                    Không có menu nào trên hệ thống
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>

          @if (count($listMenu) > 0)
            <div class="input-group ms-1 mb-3" style="max-width: 210px;">
              <select class="form-select">
                <option value="1">Cập nhật</option>
                <option value="2">Xóa</option>
              </select>
              <button type="submit" class="btn btn-outline-success">Thực hiện</button>
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>
@endsection

@section('scripts')
  <script src="{{ asset('modules/menu/js/danh-sach.js') }}"></script>
@endsection
