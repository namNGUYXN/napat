@extends('layouts.app')

@section('content')
  <!-- Main Content -->
  <div class="col bg-light p-4 overflow-auto custom-scrollbar">
    <h2 class="mb-4">Tạo bài giảng mới</h2>

    <a href="{{ route('muc-bai-giang.detail', $idMucBaiGiang) }}" class="btn btn-outline-secondary mb-4">
      <i class="fas fa-arrow-alt-circle-left me-2"></i>Danh sách bài giảng
    </a>

    @error('id_muc_bai_giang')
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ $message }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @enderror

    <div class="card shadow-sm">
      <div class="card-header bg-success text-white">
        <h5 class="mb-0">Thông tin bài giảng</h5>
      </div>
      <div class="card-body">
        <form action="{{ route('bai-giang.store') }}" method="POST">
          @csrf
          <input type="hidden" name="id_muc_bai_giang" value="{{ $idMucBaiGiang }}">
          <div class="mb-3">
            <label for="lecture-title" class="form-label">Tiêu đề bài giảng <span class="text-danger">*</span></label>
            <input type="text" class="form-control @error('tieu_de') is-invalid @enderror" name="tieu_de"
              value="{{ old('tieu_de') }}" placeholder="Nhập tên bài giảng" id="lecture-title">
            @error('tieu_de')
              <div class="invalid-feedback">
                {{ $message }}
              </div>
            @enderror
          </div>
          <div class="mb-3">
            <label for="lecture-content" class="form-label">Nội dung bài giảng <span class="text-danger">*</span></label>
            <textarea class="form-control textarea-tiny @error('noi_dung') is-invalid @enderror" name="noi_dung" rows="10"
              placeholder="Nhập nội dung chi tiết bài giảng" id="lecture-content">
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
  <script src="https://cdn.tiny.cloud/1/49cqngm4aad2mfsqcxldsfyni14qw3mjr893daq7kzrqa40a/tinymce/5/tinymce.min.js"
    referrerpolicy="origin"></script>
@endsection

@section('scripts')
  <script src="{{ asset('js/tiny-mce.js') }}"></script>
  <script src="{{ asset('modules/baigiang/js/them-bai-giang.js') }}"></script>
@endsection
