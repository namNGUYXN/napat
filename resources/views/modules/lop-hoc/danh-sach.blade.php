@extends('layouts.app')

@section('title', 'Danh sách lớp học')

@section('content')
  <!-- Main Content -->
  <div class="col bg-light p-4 overflow-auto custom-scrollbar">
    <h2 class="mb-3">Danh sách lớp học phần thuộc Khoa
      <span class="text-secondary fst-italic">{{ $khoa->ten }}</span>
    </h2>

    <div class="class-grid">
      @foreach ($listLopHocPhan as $lopHocPhan)
        <div class="class-card rounded">
          <a href="{{ route('lop-hoc.detail', ['slug' => $lopHocPhan->slug]) }}" class="class-img">
            <img src="{{ asset('storage/' . $lopHocPhan->hinh_anh) }}" class="img-fluid rounded-top" alt="">
          </a>
          <div class="p-3">
            <a href="{{ route('lop-hoc.detail', ['slug' => $lopHocPhan->slug]) }}"
              class="text-dark class-name">{{ $lopHocPhan->ten }}</a>
            <p class="mb-1"><b>Giảng viên: </b>{{ $lopHocPhan->giang_vien->ho_ten }}</p>
            <p class="mb-1"><b>Mã lớp: </b>{{ $lopHocPhan->ma }}</p>
            <p class="mb-1"><b>Khoa: </b>{{ $lopHocPhan->khoa->ten }}</p>
            <small class="text-secondary fst-italic">{{ $lopHocPhan->mo_ta_ngan }}</small>

            @if (session('vai_tro') == 'Giảng viên' && session('id_nguoi_dung') == $lopHocPhan->giang_vien->id)
              <div class="class-action-btn">
                <div class="dropdown">
                  <button class="btn btn-transparent dropdown-toggle remove-arrow-down" type="button"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-ellipsis-v"></i>
                  </button>
                  <ul class="dropdown-menu">
                    <li>
                      <a class="dropdown-item" href="{{ route('lop-hoc.detail', ['slug' => $lopHocPhan->slug]) }}">
                        Xem
                      </a>
                    </li>
                    <li>
                      <button class="dropdown-item btn-update-class" type="button"
                        data-url-detail="{{ route('lop-hoc-phan.detail-modal', $lopHocPhan->id) }}"
                        data-url-update="{{ route('lop-hoc-phan.update-modal', $lopHocPhan->id) }}">
                        Chỉnh sửa
                      </button>
                    </li>
                    <li>
                      <button class="dropdown-item class-delete-btn" type="button">
                        Xóa
                      </button>
                    </li>
                  </ul>
                </div>
              </div>
            @elseif (session('vai_tro') == 'Sinh viên')
              <div class="class-action-btn">
                <div class="dropdown">
                  <button class="btn btn-transparent dropdown-toggle remove-arrow-down" type="button"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-ellipsis-v"></i>
                  </button>
                  <ul class="dropdown-menu">
                    <li><button class="dropdown-item" type="button">Đăng ký lớp học</button></li>
                    <li><button class="dropdown-item" type="button">Rời lớp học</button></li>
                  </ul>
                </div>
              </div>
            @endif
          </div>
        </div>
      @endforeach
      <!-- Thêm các lớp khác nếu cần -->
    </div>

    {{-- Dấu : báo hiệu cho blade đây là biểu thức php --}}
    <x-pagination :paginator="$listLopHocPhan" base-url="{{ route('lop-hoc.index', $khoa->slug) }}" />

    {{-- <button type="button" class="class-add-btn btn btn-primary rounded-circle" data-bs-toggle="modal"
      data-bs-target="#exampleModal">
      <i class="fas fa-plus"></i>
    </button> --}}

    {{-- Modal thêm lớp học phần --}}
    {{-- <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
          <div class="modal-header bg-primary text-white">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Tạo mới lớp học</h1>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form action="" enctype="multipart/form-data">
              <div class="mb-3">
                <label for="" class="form-label">Tên lớp học</label>
                <input type="text" name="" class="form-control" id="">
              </div>
              <div class="mb-3">
                <label for="" class="form-label">Học phần</label>
                <select class="form-select" aria-label="">
                  <option selected>-- Chọn học phần --</option>
                  <option value="1">Cơ Sở Dữ Liệu</option>
                  <option value="2">Mạng Máy Tính</option>
                  <option value="3">Toán Rời Rạc</option>
                </select>
              </div>
              <div class="mb-3">
                <label for="" class="form-label">Mô tả ngắn (100 từ)</label>
                <textarea name="" id="" class="form-control"></textarea>
              </div>
              <div class="mb-3">
                <label for="imageUpload" class="form-label">Hình ảnh</label>
                <input class="form-control" type="file" id="imageUpload" accept="image/*">
                <div id="imagePreviewContainer" class="mt-3 position-relative d-inline-block">
                  <img id="imagePreview" src="#" alt="Ảnh xem trước" class="img-thumbnail"
                    style="display: none; max-width: 200px; max-height: 200px;">
                  <span id="removeImageBtn" class="close-btn" style="display: none;">&times;</span>
                </div>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
            <button type="button" class="btn btn-primary">Tạo mới</button>
          </div>
        </div>
      </div>
    </div> --}}

    {{-- Modal chỉnh sửa lớp học phần --}}
    <form action="" method="POST" enctype="multipart/form-data">
      @csrf
      @method('PUT')
      <div class="modal fade" id="modal-chinh-sua-lop-hoc-phan" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
          <div class="modal-content">
            <div class="modal-header bg-warning text-white">
              <h5 class="modal-title">
                <i class="far fa-edit me-2"></i>Chỉnh sửa lớp học phần
              </h5>
              <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                aria-label="Close"></button>
            </div>
            <div class="modal-body custom-scrollbar">
              <div class="mb-3">
                <label for="" class="form-label">
                  Tên lớp học phần
                  <span class="text-muted">(100 từ)</span>
                  <abbr class="text-danger" title="Bắt buộc">*</abbr>
                </label>
                <input type="text" name="ten" class="form-control" id="ten-lop-hoc-phan" required maxlength="100">
              </div>
              <div class="mb-3">
                <label for="" class="form-label">Khoa</label>
                <select class="form-select" name="id_khoa" id="select-khoa" required>
                  <option selected disabled value="">-- Chọn khoa --</option>
                  @foreach ($listKhoa as $khoa)
                    <option value="{{ $khoa->id }}">{{ $khoa->ten }}</option>
                  @endforeach
                </select>
              </div>
              <div class="mb-3">
                <label for="" class="form-label">Bài giảng</label>
                <select class="form-select" name="id_bai_giang" id="select-bai-giang" required>
                  <option selected disabled value="">-- Chọn bài giảng --</option>
                  @foreach ($listBaiGiang as $baiGiang)
                    <option value="{{ $baiGiang->id }}">{{ $baiGiang->ten }}</option>
                  @endforeach
                </select>
              </div>
              <div class="mb-3">
                <label for="" class="form-label">Mô tả ngắn (255 từ)</label>
                <textarea name="mo_ta_ngan" id="mo-ta-lop-hoc-phan" rows="5" class="form-control" maxlength="255"></textarea>
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
                  <img src="" id="hinh-anh-lop-hoc-phan" data-url="{{ asset('storage/') }}"
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

  </div>
@endsection

@section('styles')
  <link rel="stylesheet" href="{{ asset('modules/lop-hoc/css/lop-hoc-cua-toi.css') }}">
@endsection

@section('scripts')
  <script src="{{ asset('modules/lop-hoc/js/lop-hoc-cua-toi.js') }}"></script>

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
