@extends('layouts.app')

@section('title', 'Danh sách tài liệu bài giảng')

@section('content')
  <form id="csrfForm" class="d-none">
    @csrf
  </form>
  <!-- Main Content -->
  <div class="col bg-light p-4 overflow-auto custom-scrollbar">
    <h2 class="mb-3">Danh sách bài giảng cá nhân</h2>

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

    <div class="document-grid">
      @foreach ($listBaiGiang as $baiGiang)
        <div class="document-card rounded">
          <a href="{{ route('bai-giang.detail', $baiGiang->id) }}" class="document-img">
            <img src="{{ asset('storage/' . $baiGiang->hinh_anh) }}" class="rounded-top" alt="">
          </a>
          <div class="p-3">
            <a href="{{ route('bai-giang.detail', $baiGiang->id) }}"
              class="text-dark document-name">{{ $baiGiang->ten }}</a>
            <p class="mb-1">
              <b>Số chương: </b>{{ $baiGiang->so_chuong }}
              / <b>Số bài: </b>{{ $baiGiang->tong_so_bai }}
            </p>
            <p class="mb-1"><b>Ngày tạo: </b> {{ $baiGiang->ngay_tao }}</p>
            <small class="text-secondary fst-italic d-inline-block me-3" title="{{ $baiGiang->mo_ta_ngan }}">
              {{ Str::of($baiGiang->mo_ta_ngan)->limit(100) }}
            </small>
            <div class="document-action-btn">
              <div class="dropdown">
                <button class="btn btn-transparent dropdown-toggle" type="button" data-bs-toggle="dropdown"
                  aria-expanded="false">
                  <i class="fas fa-ellipsis-v"></i>
                </button>
                <ul class="dropdown-menu">
                  <li><a class="dropdown-item" href="{{ route('bai-giang.detail', $baiGiang->id) }}">Xem</a></li>
                  <li>
                    <button type="button" class="dropdown-item document-edit-btn"
                      data-url-detail="{{ route('bai-giang.detail-modal', $baiGiang->id) }}"
                      data-url-update="{{ route('bai-giang.update-modal', $baiGiang->id) }}">Chỉnh sửa</button>
                  </li>
                  <li>
                    <button type="button" class="dropdown-item document-delete-btn"
                      data-url-delete="{{ route('bai-giang.delete', $baiGiang->id) }}">Xóa</button>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      @endforeach
    </div>

    {{-- Dấu : báo hiệu cho blade đây là biểu thức php --}}
    <x-pagination :paginator="$listBaiGiang" base-url="{{ route('bai-giang.index') }}" />

    <button type="button" class="btn btn-primary rounded-circle document-add-btn" data-bs-toggle="modal"
      data-bs-target="#modal-them-bai-giang">
      <i class="fas fa-plus"></i>
    </button>

    {{-- Modal thêm --}}
    <form action="{{ route('bai-giang.store') }}" method="POST" enctype="multipart/form-data">
      @csrf
      <div class="modal fade" id="modal-them-bai-giang" tabindex="-1" aria-labelledby="" aria-hidden="true"
        data-bs-focus="false">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
          <div class="modal-content">
            <div class="modal-header bg-primary text-white">
              <h5 class="modal-title">
                <i class="far fa-plus-square me-2"></i>Thêm bài giảng
              </h5>
              <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                aria-label="Close"></button>
            </div>
            <div class="modal-body custom-scrollbar">
              <div class="mb-3">
                <label for="" class="form-label">
                  Tên bài giảng
                  <span class="text-muted">(100 từ)</span>
                  <abbr class="text-danger" title="Bắt buộc">*</abbr>
                </label>
                <input type="text" name="ten" class="form-control" id="" required maxlength="100">
              </div>
              <div class="mb-3">
                <label for="" class="form-label">Mô tả ngắn <span class="text-muted">(255 từ)</span></label>
                <textarea name="mo_ta_ngan" id="" class="form-control" rows="6" maxlength="255"></textarea>
              </div>
              <div class="mb-3">
                <label for="img-upload-modal-them" class="form-label">Hình ảnh</label>
                <input class="form-control" type="file" name="hinh_anh" id="img-upload-modal-them"
                  accept="image/*">
                <div id="img-preview-container-modal-them" class="mt-3 position-relative d-inline-block">
                  <img src="#" alt="Ảnh xem trước" class="img-preview img-thumbnail"
                    style="display: none; max-width: 200px; max-height: 200px;">
                  <span class="img-remove-btn close-btn" style="display: none;">&times;</span>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
              <button type="submit" class="btn btn-primary">Tạo mới</button>
            </div>
          </div>
        </div>
      </div>
    </form>

    {{-- Modal chỉnh sửa --}}
    <form action="" method="POST" enctype="multipart/form-data">
      @csrf
      @method('PUT')
      <div class="modal fade" id="modal-chinh-sua-bai-giang" tabindex="-1" aria-labelledby="" aria-hidden="true"
        data-bs-focus="false">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
          <div class="modal-content">
            <div class="modal-header bg-warning text-white">
              <h5 class="modal-title">
                <i class="fas fa-edit me-2"></i>Chỉnh sửa bài giảng
              </h5>
              <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                aria-label="Close"></button>
            </div>
            <div class="modal-body custom-scrollbar">
              <div class="mb-3">
                <label for="" class="form-label">
                  Tên bài giảng
                  <span class="text-muted">(100 từ)</span>
                  <abbr class="text-danger" title="Bắt buộc">*</abbr>
                </label>
                <input type="text" name="ten" class="form-control" id="ten-bai-giang">
              </div>
              <div class="mb-3">
                <label for="" class="form-label">Mô tả ngắn <span class="text-muted">(255 từ)</span></label>
                <textarea name="mo_ta_ngan" id="mo-ta-bai-giang" class="form-control" rows="6"></textarea>
              </div>
              <div class="mb-3">
                <label for="img-upload-modal-chinh-sua" class="form-label">Hình ảnh</label>
                <input class="form-control" type="file" name="hinh_anh" id="img-upload-modal-chinh-sua"
                  accept="image/*">
                <div id="img-preview-container-modal-chinh-sua" class="mt-3 position-relative d-inline-block">
                  <img src="#" alt="Ảnh xem trước" class="img-preview img-thumbnail"
                    style="display: none; max-width: 200px; max-height: 200px;">
                  <span class="img-remove-btn close-btn" style="display: none;">&times;</span>
                </div>
                <div class="mt-3 d-inline-block">
                  <img src="" id="hinh-anh-bai-giang" data-url="{{ asset('storage/') }}"
                    class="img-thumbnail" style="max-width: 200px; max-height: 200px;">
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
              <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
            </div>
          </div>
        </div>
      </div>
    </form>

    {{-- Modal xóa --}}
    <form action="" method="POST">
      @csrf
      @method('DELETE')
      <div class="modal fade" id="modal-xoa-bai-giang" tabindex="-1" aria-labelledby="" aria-hidden="true"
        data-bs-focus="false">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header bg-danger text-white">
              <h5 class="modal-title">
                <i class="fas fa-trash-alt me-2"></i>Xác nhận xóa Bài giảng
              </h5>
              <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <p>Bạn có chắc chắn muốn xóa bài giảng này không?</p>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
              <button type="submit" class="btn btn-danger">Xóa bài giảng</button>
            </div>
          </div>
        </div>
      </div>
    </form>

  </div>
@endsection

@section('styles')
  <link rel="stylesheet" href="{{ asset('modules/bai-giang/css/danh-sach.css') }}">
@endsection

@section('scripts')
  <script src="{{ asset('modules/bai-giang/js/danh-sach.js') }}"></script>

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
