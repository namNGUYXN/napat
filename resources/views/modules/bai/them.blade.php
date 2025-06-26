@extends('layouts.app')

@section('content')
  <!-- Main Content -->
  <div class="col bg-light p-4 overflow-auto custom-scrollbar">
    <h2 class="mb-4">
      Thêm bài cho Chương <span class="fst-italic text-secondary">"{{ $chuong->tieu_de }}"</span>
      - Bài giảng <span class="fst-italic text-secondary">"{{ $chuong->bai_giang->ten }}"</span>
    </h2>

    <a href="{{ route('chuong.edit', $chuong->id) }}" class="btn btn-outline-secondary mb-4">
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
        <form action="{{ route('bai.store', $chuong->id) }}" method="POST">
          @csrf
          <div class="mb-3">
            <label for="lecture-title" class="form-label">
              Tiêu đề bài
              <span class="text-muted">(100 ký tự)</span>
              <abbr class="text-danger" title="Bắt buộc">*</abbr>
            </label>
            <input type="text" class="form-control @error('tieu_de') is-invalid @enderror" name="tieu_de"
              value="{{ old('tieu_de') }}" placeholder="Nhập tiêu đề bài..." id="lecture-title">
            @error('tieu_de')
              <div class="invalid-feedback">
                {{ $message }}
              </div>
            @enderror
          </div>
          <div class="mb-3">
            <label for="lecture-content" class="form-label">Nội dung bài <abbr class="text-danger"
                title="Bắt buộc">*</abbr></label>
            <textarea class="form-control tinymce" name="noi_dung" rows="10" placeholder="Nhập nội dung chi tiết bài..."
              id="lecture-content">
              {{ old('noi_dung') }}
            </textarea>
            @error('noi_dung')
              <div class="invalid-feedback">
                {{ $message }}
              </div>
            @enderror
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
  <script src="{{ asset('vendor/tinymce-5/tinymce.min.js') }}"></script>
  <script src="{{ asset('js/config-tinymce.js') }}"></script>
  <script src="{{ asset('modules/bai/js/them-bai-giang.js') }}"></script>

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
