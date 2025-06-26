@extends('layouts.app')

@section('content')
  <form id="csrfForm" class="d-none">
    @csrf
  </form>
  <div class="col bg-light px-4 pt-4 overflow-auto custom-scrollbar">
    <h2 class="mb-4">Chỉnh sửa chương</h2>

    <a href="{{ route('bai-giang.detail', $chuong->bai_giang->id) }}" class="btn btn-outline-secondary mb-4">
      <i class="fas fa-arrow-alt-circle-left me-2"></i>Danh sách chương
    </a>

    @if ($errors->any())
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <ul class="list-unstyled m-0">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

    {{-- @if (session('message'))
      <div class="alert alert-{{ session('status') }} alert-dismissible fade show" role="alert">
        {{ session('message') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif --}}

    <div class="row align-items-start">
      <div class="col-lg-4">
        <div class="card h-100 shadow-sm p-3">
          <form action="{{ route('chuong.update', $chuong->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
              <label for="" class="form-label">
                Tiêu đề chương
                <span class="text-muted">(100 ký tự)</span>
                <abbr class="text-danger" title="Bắt buộc">*</abbr>
              </label>
              <input type="text" name="tieu_de" class="form-control" id="" required maxlength="100"
                value="{{ $chuong->tieu_de }}" placeholder="Nhập tiêu đề chương...">
            </div>
            <div class="mb-3">
              <label for="" class="form-label">Mô tả ngắn <span class="text-muted">(255 ký tự)</span></label>
              <textarea name="mo_ta_ngan" id="" class="form-control" rows="6" maxlength="255"
                placeholder="Nhập nội dung mô tả chương...">{{ $chuong->mo_ta_ngan }}</textarea>
            </div>
            <div class="text-end">
              <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
            </div>
          </form>
        </div>
      </div>

      <div class="col-lg-8 my-4 mt-md-0">
        <div class="card shadow-sm">
          <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Danh sách các bài trong chương</h5>
            <a href="{{ route('bai.create', $chuong->id) }}" class="btn btn-light btn-sm">
              <i class="fas fa-plus-circle me-2"></i>Thêm bài
            </a>
          </div>
          <div class="card-body">
            <form action="" method="GET">
              <div class="input-group mb-3">
                <input type="text" class="form-control" name="search" value="{{ request()->input('search') }}"
                  placeholder="Nhập tiêu đề của bài cần tìm..." autocomplete="off">
                <button class="btn btn-outline-secondary">
                  <i class="fas fa-search"></i> </button>
              </div>
            </form>

            <div class="table-responsive custom-scrollbar">
              <div class="text-center text-muted fst-italic" id="url-cap-nhat-thu-tu-bai"
                data-url="{{ route('thu-tu-bai.update', $chuong->id) }}">
                Giữ vào một bài 0.5s sau đó có thể kéo thả để thiết lập vị trí
              </div>
              <form action="#" method="POST">
                @csrf
                @method('PUT')
                <table class="table table-hover table-striped caption-top" style="min-width: 600px;">
                  <caption>Có {{ $listBai->count() }} bản ghi bài trong chương</caption>
                  <thead>
                    <tr>
                      <th scope="col">
                        <input type="checkbox" class="form-check-input" name="" id="check-all">
                      </th>
                      <th scope="col">Tiêu đề</th>
                      <th scope="col">Ngày tạo</th>
                      <th scope="col" class="text-center">Thao tác</th>
                    </tr>
                  </thead>
                  <tbody id="list-bai">

                    @forelse ($listBai as $bai)
                      <tr data-id="{{ $bai->id }}">
                        <th scope="row">
                          <input type="checkbox" class="form-check-input row-checkbox" name="" id="">
                        </th>
                        <td class="align-middle">{{ $bai->tieu_de }}</td>
                        <td class="align-middle">{{ $bai->ngay_tao }}</td>
                        <td class="text-center align-middle" style="min-width: 140px;">
                          <button type="button" class="btn btn-info btn-sm me-1 btn-detail-bai"
                            data-url="{{ route('bai.detail', $bai->id) }}">
                            <i class="fas fa-eye"></i>
                          </button>
                          <a href="{{ route('bai.edit', $bai->id) }}" class="btn btn-warning btn-sm me-1">
                            <i class="fas fa-edit"></i>
                          </a>
                          <button type="button" class="btn btn-danger btn-sm btn-delete-bai"
                            data-url="{{ route('bai.delete', $bai->id) }}">
                            <i class="fas fa-trash-alt"></i>
                          </button>
                        </td>
                      </tr>
                    @empty
                      <tr>
                        <td colspan="5" class="text-center">
                          Không tìm thấy bài hoặc chương chưa có bài nào
                        </td>
                      </tr>
                    @endforelse
                  </tbody>
                </table>

                @if ($listBai->count() > 0)
                  <div class="input-group ms-1 mb-3" style="max-width: 210px;">
                    <select class="form-select" name="action">
                      {{-- <option value="cap-nhat">Cập nhật</option> --}}
                      <option value="xoa">Xóa</option>
                    </select>
                    <button type="submit" class="btn btn-success">Thực hiện</button>
                  </div>
                @endif
              </form>
            </div>

          </div>
        </div>
      </div>
    </div>

    {{-- Modal chi tiết bài --}}
    <div class="modal fade" id="modal-chi-tiet-bai" tabindex="-1" aria-labelledby="" aria-hidden="true"
      data-bs-focus="false">
      <div class="modal-dialog modal-xl modal-dialog-scrollable modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header bg-info text-white">
            <h5 class="modal-title">Chi tiết bài</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
              aria-label="Close"></button>
          </div>
          <div class="modal-body custom-scrollbar">
            <p><strong>Tiêu đề bài:</strong> <span id="tieu-de-bai"></span></p>
            <hr>
            <h6>Nội dung:</h6>
            <div id="noi-dung-bai"></div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
          </div>
        </div>
      </div>
    </div>

    {{-- Modal xóa bài --}}
    <form action="" method="POST">
      @csrf
      @method('DELETE')
      <div class="modal fade" id="modal-xoa-bai" tabindex="-1" aria-labelledby="" aria-hidden="true"
        data-bs-focus="false">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header bg-danger text-white">
              <h5 class="modal-title">
                <i class="fas fa-trash-alt me-2"></i>Xác nhận xóa Bài
              </h5>
              <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <p>Bạn có chắc chắn muốn xóa bài này không?</p>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
              <button type="submit" class="btn btn-danger">Xóa bài</button>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
@endsection

@section('styles')
  <link rel="stylesheet" href="{{ asset('vendor/sortable/css/sortable.css') }}">
@endsection

@section('scripts')
  <script src="{{ asset('vendor/sortable/js/sortable.min.js') }}"></script>
  <script src="{{ asset('vendor/sortable/js/jquery-sortable.min.js') }}"></script>
  <script src="{{ asset('modules/chuong/js/chinh-sua.js') }}"></script>

  @if (session('message'))
    <script>
      const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        width: 'auto',
        showConfirmButton: false,
        timer: 3500,
        timerProgressBar: true,
        didOpen: (toast) => {
          toast.onmouseenter = Swal.stopTimer;
          toast.onmouseleave = Swal.resumeTimer;
        }
      });

      Toast.fire({
        icon: '{{ session('icon') }}',
        title: '{{ session('message') }}'
      });
    </script>
  @endif
@endsection
