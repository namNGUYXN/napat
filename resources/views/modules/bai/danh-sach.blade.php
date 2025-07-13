@extends('layouts.app')

@section('title', 'Danh sách bài giảng')

@section('content')
  <form id="csrfForm" class="d-none">
    @csrf
  </form>
  <div class="col bg-light px-4 pt-4 overflow-auto custom-scrollbar">
    <h2 class="mb-4">
      Quản lý các bài trong Chương <span class="fst-italic text-secondary">"{{ $chuong->tieu_de }}"</span>
      - Bài giảng <span class="fst-italic text-secondary">"{{ $chuong->bai_giang->ten }}"</span>
    </h2>

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
      <div class="col-12">
        <div class="card shadow-sm">
          <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-file-alt me-2"></i>Danh sách các bài trong chương</h5>
            <a href="{{ route('bai.create', $chuong->id) }}" class="btn btn-light btn-sm">
              <i class="fas fa-plus-circle me-2"></i>Thêm bài
            </a>
          </div>
          <div class="card-body">

            {{-- Tìm kiếm --}}
            <form method="GET" class="mb-3">
              <div class="input-group">
                <input type="text" class="form-control" name="search" value="{{ request()->input('search') }}"
                  placeholder="Tìm bài..." autocomplete="off">
                <button class="btn btn-outline-secondary">
                  <i class="fas fa-search"></i>
                </button>
              </div>
            </form>

            {{-- Hướng dẫn drag --}}
            <div class="text-center text-muted small fst-italic mb-3" id="url-cap-nhat-thu-tu-bai"
              data-url="{{ route('thu-tu-bai.update', $chuong->id) }}">
              <input type="checkbox" class="form-check-input me-2" name="" id="check-all" title="Chọn tất cả">
              Giữ vào một bài 0.5s để kéo thả thay đổi thứ tự
            </div>

            {{-- Form thao tác --}}
            <form action="{{ route('bai.quick-delete') }}" id="form-xoa-hang-loat-bai" method="POST"
              data-url-detail="{{ route('bai.index', $chuong->id) }}">
              @csrf @method('DELETE')
              <input type="hidden" name="id_chuong" value="{{ $chuong->id }}">

              {{-- Lưới các bài học --}}
              <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-4" id="list-bai">
                @forelse ($listBai as $bai)
                  <div class="col draggable-item" data-id="{{ $bai->id }}">
                    <div class="card text-center shadow-sm h-100 position-relative">
                      <!-- Checkbox ở góc trên trái -->
                      <input type="checkbox" class="form-check-input position-absolute m-2 row-checkbox"
                        name="list_id_bai[]" value="{{ $bai->id }}" style="top: 0; left: 0; z-index: 1;">

                      <div class="card-body d-flex flex-column justify-content-between align-items-center">
                        <i class="fas fa-file-alt fa-3x text-primary mb-3"></i>

                        <div class="bai-card-title" title="{{ $bai->tieu_de }}">
                          {{ $bai->tieu_de }}
                        </div>
                        <p class="text-muted small mb-0 fst-italic">{{ $bai->ngay_tao }}</p>
                      </div>

                      <div class="card-footer bg-transparent border-0 d-flex justify-content-center gap-2">
                        <button type="button" class="btn btn-sm btn-outline-info btn-detail-bai"
                          data-url="{{ route('bai.detail', $bai->id) }}" title="Xem">
                          <i class="fas fa-eye"></i>
                        </button>
                        <a href="{{ route('bai.edit', $bai->id) }}" class="btn btn-sm btn-outline-warning"
                          title="Sửa">
                          <i class="fas fa-edit"></i>
                        </a>
                        <button type="button" class="btn btn-sm btn-outline-danger btn-delete-bai"
                          data-url-delete="{{ route('bai.delete', $bai->id) }}" title="Xóa"
                          data-url-detail="{{ route('bai.index', $chuong->id) }}">
                          <i class="fas fa-trash-alt"></i>
                        </button>
                      </div>
                    </div>

                  </div>
                @empty
                  <div class="col text-muted">Không có bài nào</div>
                @endforelse
              </div>

              {{-- Thao tác hàng loạt --}}
              @if ($listBai->count() > 0)
                <div class="input-group mt-4" style="max-width: 220px;">
                  <select class="form-select" name="action">
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
    {{-- <form action="" method="POST">
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
    </form> --}}
  </div>
@endsection

@section('styles')
  {{-- <link rel="stylesheet" href="{{ asset('vendor/sortable/css/sortable.css') }}"> --}}
  <link rel="stylesheet" href="{{ asset('modules/bai/css/danh-sach.css') }}">
@endsection

@section('scripts')
  <script src="{{ asset('vendor/sortable/js/sortable.min.js') }}"></script>
  <script src="{{ asset('vendor/sortable/js/jquery-sortable.min.js') }}"></script>
  <script src="{{ asset('modules/bai/js/danh-sach.js') }}"></script>

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
