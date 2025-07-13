@extends('layouts.app')

@section('title', 'Thêm bài học')

@section('content')
  <!-- Loading Overlay -->
  <div id="loading-overlay"
    class="position-fixed top-0 start-0 w-100 h-100 d-none align-items-center justify-content-center"
    style="z-index: 1050; background-color: rgba(0, 0, 0, 0.5);">
    <div class="text-center text-white">
      <div class="spinner-border text-light" role="status" style="width: 3rem; height: 3rem;"></div>
      <div class="mt-2">Đang xử lý, vui lòng chờ...</div>
    </div>
  </div>

  <!-- Main Content -->
  <div class="col bg-light p-4 overflow-auto custom-scrollbar">
    <h2 class="mb-4">
      Thêm bài cho Chương <span class="fst-italic text-secondary">"{{ $chuong->tieu_de }}"</span>
      - Bài giảng <span class="fst-italic text-secondary">"{{ $chuong->bai_giang->ten }}"</span>
    </h2>

    <a href="{{ route('bai.index', $chuong->id) }}" class="btn btn-outline-secondary mb-4">
      <i class="fas fa-arrow-alt-circle-left me-2"></i>Danh sách bài của chương
    </a>

    {{-- @if (session('message'))
      <div class="alert alert-{{ session('status') }} alert-dismissible fade show" role="alert">
        {{ session('message') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif --}}

    <div class="card shadow-sm">
      <div class="card-header bg-success text-white">
        <h5 class="mb-0">Thông tin bài</h5>
      </div>
      <div class="card-body">
        <input type="file" id="upload-docx" accept=".docx" class="d-none">

        <form action="{{ route('bai.store', $chuong->id) }}" method="POST" id="form-them-bai">
          @csrf
          <div class="mb-3">
            <label for="lecture-title" class="form-label">
              Tiêu đề bài
              <span class="text-muted">(100 ký tự)</span>
              <abbr class="text-danger" title="Bắt buộc">*</abbr>
            </label>
            <input type="text" class="form-control @error('tieu_de') is-invalid @enderror" name="tieu_de"
              placeholder="Nhập tiêu đề bài..." id="lecture-title" required maxlength="255" autocomplete="off">
            <small class="text-danger" id="tieu-de-error"></small>
          </div>
          <div class="mb-3">
            <label for="lecture-content" class="form-label">Nội dung bài <abbr class="text-danger"
                title="Bắt buộc">*</abbr></label>
            <textarea class="form-control tinymce" name="noi_dung" rows="10" placeholder="Nhập nội dung chi tiết bài..."
              id="lecture-content" required>
            </textarea>
            <small class="text-danger" id="noi-dung-error"></small>
          </div>

          <div class="d-flex justify-content-end">
            <button type="reset" class="btn btn-secondary me-2">Đặt lại</button>
            <button type="submit" class="btn btn-primary">Tạo mới</button>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection

@section('styles')
@endsection

@section('scripts')
  {{-- <script src="{{ asset('vendor/tinymce-5/tinymce.min.js') }}"></script> --}}
  <script src="https://cdn.tiny.cloud/1/{{env('TINYMCE_KEY')}}/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
  <script src="{{ asset('js/mammoth.browser.min.js') }}"></script>

  <script>
    const uploadImageUrl = '{{ route('upload.image') }}';
    const csrfToken = '{{ csrf_token() }}';
  </script>

  <script src="{{ asset('js/config-tinymce-import-word.js') }}"></script>
  <script src="{{ asset('modules/bai/js/them.js') }}"></script>
@endsection
