@extends('layouts.app')

@section('title', 'Lớp học của tôi')

@section('content')
  <!-- Main Content -->
  <div class="col bg-light p-4 overflow-auto custom-scrollbar">
    <h2 class="mb-3">Lớp học của tôi</h2>

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

    <div class="class-grid">
      @foreach ($dsLopHoc as $lop)
        <div class="class-card rounded">
          <a href="{{ route('lop-hoc.detail', ['slug' => $lop->slug]) }}" class="class-img">
            <img src="{{ asset('storage/' . $lop->hinh_anh) }}" class="img-fluid rounded-top" alt="">
          </a>
          <div class="p-3">
            <a href="{{ route('lop-hoc.detail', ['slug' => $lop->slug]) }}"
              class="text-dark class-name">{{ $lop->ten }}</a>
            <p class="mb-1"><b>Giảng viên: </b>{{ $lop->giang_vien->ho_ten }}</p>
            <p class="mb-1"><b>Mã lớp: </b>{{ $lop->ma }}</p>
            <p class="mb-1"><b>Khoa: </b>{{ $lop->khoa->ten }}</p>
            <small class="text-secondary fst-italic d-inline-block me-3">
              {{ Str::of($lop->mo_ta_ngan)->limit(100) }}
            </small>
            <div class="class-action-btn">
              <div class="dropdown">
                <button class="btn btn-transparent dropdown-toggle remove-arrow-down" type="button"
                  data-bs-toggle="dropdown" aria-expanded="false">
                  <i class="fas fa-ellipsis-v"></i>
                </button>
                <ul class="dropdown-menu">
                  <li>
                    <a class="dropdown-item" href="{{ route('lop-hoc.detail', ['slug' => $lop->slug]) }}">
                      Xem
                    </a>
                  </li>
                  <li>
                    <button class="dropdown-item btn-update-class" type="button"
                      data-url-detail="{{ route('lop-hoc-phan.detail-modal', $lop->id) }}"
                      data-url-update="{{ route('lop-hoc-phan.update-modal', $lop->id) }}">
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
          </div>
        </div>
      @endforeach
      <!-- Thêm các lớp khác nếu cần -->
    </div>

    {{-- Dấu : báo hiệu cho blade đây là biểu thức php --}}
    <x-pagination :paginator="$dsLopHoc" base-url="{{ route('lop-hoc.lop-hoc-cua-toi') }}" />

    <button type="button" class="class-add-btn btn btn-primary rounded-circle" data-bs-toggle="modal"
      data-bs-target="#modal-them-lop-hoc-phan">
      <i class="fas fa-plus"></i>
    </button>

    {{-- Modal thêm lớp học phần --}}
    <form action="{{ route('lop-hoc.store') }}" method="POST" enctype="multipart/form-data">
      @csrf
      <div class="modal fade" id="modal-them-lop-hoc-phan" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
          <div class="modal-content">
            <div class="modal-header bg-primary text-white">
              <h5 class="modal-title">
                <i class="far fa-plus-square me-2"></i>Thêm lớp học phần
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
                <input type="text" name="ten" class="form-control" id="" required maxlength="100">
              </div>
              <div class="mb-3">
                <label for="" class="form-label">Khoa</label>
                <select class="form-select" name="id_khoa" aria-label="" required>
                  <option selected disabled value="">-- Chọn khoa --</option>
                  @foreach ($listKhoa as $khoa)
                    <option value="{{ $khoa->id }}">{{ $khoa->ten }}</option>
                  @endforeach
                </select>
              </div>
              <div class="mb-3">
                <label for="" class="form-label">Bài giảng</label>
                <select class="form-select" name="id_bai_giang" aria-label="" required>
                  <option selected disabled value="">-- Chọn bài giảng --</option>
                  @foreach ($listBaiGiang as $baiGiang)
                    <option value="{{ $baiGiang->id }}">{{ $baiGiang->ten }}</option>
                  @endforeach
                </select>
              </div>
              <div class="mb-3">
                <label for="" class="form-label">Mô tả ngắn (255 từ)</label>
                <textarea name="mo_ta_ngan" id="" rows="5" class="form-control" maxlength="255"></textarea>
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
                <input type="text" name="ten" class="form-control" id="ten-lop-hoc-phan" required
                  maxlength="100">
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
