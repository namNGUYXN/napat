@extends('layouts.app')

@section('title', 'Danh sách tài liệu bài giảng')

@section('content')
  <form id="csrfForm" class="d-none">
    @csrf
  </form>
  <!-- Main Content -->
  <div class="col bg-light p-4 overflow-auto custom-scrollbar">
    <h2 class="mb-3">Danh sách mục bài giảng</h2>

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

    @if (session('message'))
      <div class="alert alert-{{ session('status') }} alert-dismissible fade show" role="alert">
        {{ session('message') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

    <div class="document-grid">
      @foreach ($listMucBaiGiang as $mucBaiGiang)
        <div class="document-card rounded">
          <a href="{{ route('muc-bai-giang.detail', $mucBaiGiang->id) }}" class="document-img">
            <img src="{{ asset('storage/' . $mucBaiGiang->hinh_anh) }}" class="rounded-top" alt="">
          </a>
          <div class="p-3">
            <a href="{{ route('muc-bai-giang.detail', $mucBaiGiang->id) }}"
              class="text-dark document-name">{{ $mucBaiGiang->ten }}</a>
            <p class="mb-1"><b>Số lượng bài giảng: </b>{{ $mucBaiGiang->so_bai_giang }}</p>
            <p class="mb-1"><b>Ngày tạo: </b> {{ $mucBaiGiang->ngay_tao }}</p>
            <small class="text-secondary fst-italic" title="{{ $mucBaiGiang->mo_ta_ngan }}">
              {{ Str::of($mucBaiGiang->mo_ta_ngan)->limit(100) }}
            </small>
            <div class="document-action-btn">
              <div class="dropdown">
                <button class="btn btn-transparent dropdown-toggle" type="button" data-bs-toggle="dropdown"
                  aria-expanded="false">
                  <i class="fas fa-ellipsis-v"></i>
                </button>
                <ul class="dropdown-menu">
                  <li><a class="dropdown-item" href="{{ route('muc-bai-giang.detail', $mucBaiGiang->id) }}">Xem</a></li>
                  <li>
                    <button type="button" class="dropdown-item document-edit-btn"
                      data-url-detail="{{ route('muc-bai-giang.detail-modal', $mucBaiGiang->id) }}"
                      data-url-update="{{ route('muc-bai-giang.update-modal', $mucBaiGiang->id) }}">Chỉnh sửa</button>
                  </li>
                  <li>
                    <button type="button" class="dropdown-item document-delete-btn"
                      data-url-delete="{{ route('muc-bai-giang.delete', $mucBaiGiang->id) }}">Xóa</button>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      @endforeach
    </div>

    {{-- Dấu : báo hiệu cho blade đây là biểu thức php --}}
    <x-pagination :paginator="$listMucBaiGiang" base-url="{{ route('muc-bai-giang.index') }}" />

    <button type="button" class="btn btn-primary rounded-circle document-add-btn" data-bs-toggle="modal"
      data-bs-target="#modal-them-muc-bai-giang">
      <i class="fas fa-plus"></i>
    </button>

    {{-- Modal thêm --}}
    <form action="{{ route('muc-bai-giang.store') }}" method="POST" enctype="multipart/form-data">
      @csrf
      <div class="modal fade" id="modal-them-muc-bai-giang" tabindex="-1" aria-labelledby="" aria-hidden="true"
        data-bs-focus="false">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
          <div class="modal-content">
            <div class="modal-header bg-primary text-white">
              <h5 class="modal-title">
                <i class="fas fa-plus-square me-2"></i>Tạo mới Mục bài giảng
              </h5>
              <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                aria-label="Close"></button>
            </div>
            <div class="modal-body custom-scrollbar">
              <div class="mb-3">
                <label for="" class="form-label">Tên mục bài giảng <abbr class="text-danger"
                    title="Bắt buộc">*</abbr></label>
                <input type="text" name="ten" class="form-control" id="" required maxlength="255">
              </div>
              <div class="mb-3">
                <label for="" class="form-label">Mô tả ngắn <span class="text-muted">(255 từ)</span></label>
                <textarea name="mo_ta_ngan" id="" class="form-control" rows="6" maxlength="255"></textarea>
              </div>
              <div class="mb-3">
                <label for="img-upload-modal-them" class="form-label">Hình ảnh <span class="text-muted">(không bắt
                    buộc)</span></label>
                <input class="form-control" type="file" name="hinh_anh" id="img-upload-modal-them" accept="image/*">
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
      <div class="modal fade" id="modal-chinh-sua-muc-bai-giang" tabindex="-1" aria-labelledby="" aria-hidden="true"
        data-bs-focus="false">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
          <div class="modal-content">
            <div class="modal-header bg-warning text-white">
              <h5 class="modal-title">
                <i class="fas fa-edit me-2"></i>Chỉnh sửa thông tin Mục bài giảng
              </h5>
              <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                aria-label="Close"></button>
            </div>
            <div class="modal-body custom-scrollbar">
              <div class="mb-3">
                <label for="" class="form-label">Tên mục bài giảng <abbr class="text-danger"
                    title="Bắt buộc">*</abbr></label>
                <input type="text" name="ten" class="form-control" id="ten-muc-bai-giang">
              </div>
              <div class="mb-3">
                <label for="" class="form-label">Mô tả ngắn <span class="text-muted">(255 từ)</span></label>
                <textarea name="mo_ta_ngan" id="mo-ta-muc-bai-giang" class="form-control" rows="6"></textarea>
              </div>
              <div class="mb-3">
                <label for="img-upload-modal-chinh-sua" class="form-label">Hình ảnh <span class="text-muted">(không bắt
                    buộc)</span></label>
                <input class="form-control" type="file" name="hinh_anh" id="img-upload-modal-chinh-sua"
                  accept="image/*">
                <div id="img-preview-container-modal-chinh-sua" class="mt-3 position-relative d-inline-block">
                  <img src="#" alt="Ảnh xem trước" class="img-preview img-thumbnail"
                    style="display: none; max-width: 200px; max-height: 200px;">
                  <span class="img-remove-btn close-btn" style="display: none;">&times;</span>
                </div>
                <div class="mt-3 d-inline-block">
                  <img src="" id="hinh-anh-muc-bai-giang" data-url="{{ asset('storage/') }}"
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
      <div class="modal fade" id="modal-xoa-muc-bai-giang" tabindex="-1" aria-labelledby="" aria-hidden="true"
        data-bs-focus="false">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header bg-danger text-white">
              <h5 class="modal-title">
                <i class="fas fa-trash-alt me-2"></i>Xác nhận xóa Mục bài giảng
              </h5>
              <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <p>Bạn có chắc chắn muốn xóa Mục bài giảng này không?</p>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
              <button type="submit" class="btn btn-danger">Xóa Mục bài giảng</button>
            </div>
          </div>
        </div>
      </div>
    </form>

  </div>
@endsection

@section('styles')
  <link rel="stylesheet" href="{{ asset('modules/tailieu/css/danh-sach-tai-lieu.css') }}">
@endsection

@section('scripts')
  <script src="{{ asset('modules/tailieu/js/danh-sach.js') }}"></script>
@endsection
