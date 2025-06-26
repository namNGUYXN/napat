@extends('layouts.app')

@section('title', 'Tài liệu - Danh sách bài giảng')

@section('content')
  <form id="csrfForm" class="d-none">
    @csrf
  </form>
  <div class="col bg-light px-4 pt-4 overflow-auto custom-scrollbar">
    <h2 class="mb-4">Chi tiết bài giảng</h2>

    <a href="{{ route('bai-giang.index') }}" class="btn btn-outline-secondary mb-4">
      <i class="fas fa-arrow-alt-circle-left me-2"></i>Danh sách bài giảng
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
        <div class="card h-100 shadow-sm">
          <img src="{{ asset('storage/' . $baiGiang->hinh_anh) }}" class="card-img-top" alt="">
          <div class="card-body">
            <h4 id="courseTitle">{{ $baiGiang->ten }}</h4>
            <p class="mb-1"><b>Học phần:</b> {{ $baiGiang->hoc_phan->ten }}</p>
            <p class="mb-1">
              <b>Số chương: </b>{{ $baiGiang->so_chuong }}
              / <b>Số bài: </b>{{ $baiGiang->tong_so_bai }}
            </p>
            <p class="mb-1"><b>Ngày tạo:</b> {{ $baiGiang->ngay_tao }}</p>
            <p class="mb-1 mt-2"><b>Mô tả:</b> {{ $baiGiang->mo_ta_ngan }}</p>
            <hr>
            <button class="btn btn-warning btn-sm me-2" data-bs-toggle="modal"
              data-bs-target="#modal-chinh-sua-bai-giang">
              <i class="fas fa-edit me-1"></i>Chỉnh sửa
            </button>
            <button class="btn btn-danger btn-sm" id="delete-doc-btn" data-bs-toggle="modal"
              data-bs-target="#modal-xoa-bai-giang">
              <i class="fas fa-trash-alt me-1"></i>Xóa
            </button>
          </div>
        </div>
      </div>

      <div class="col-lg-8 my-4 mt-md-0">
        <div class="card shadow-sm">
          <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Danh sách các chương</h5>
            <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#modal-them-chuong">
              <i class="fas fa-plus-circle me-2"></i>Thêm chương
            </button>
            {{-- <a href="{{ route('bai-giang.create', $baiGiang->id) }}" class="btn btn-light btn-sm">
              <i class="fas fa-plus-circle me-2"></i>Thêm chương
            </a> --}}
          </div>
          <div class="card-body">
            <form action="{{ route('bai-giang.detail', $baiGiang->id) }}" method="GET">
              <div class="input-group mb-3">
                <input type="text" class="form-control" name="search" value="{{ request()->input('search') }}"
                  placeholder="Nhập tiêu đề hoặc mô tả của chương cần tìm..." autocomplete="off">
                <button class="btn btn-outline-secondary">
                  <i class="fas fa-search"></i> </button>
              </div>
            </form>

            <div class="table-responsive custom-scrollbar">
              <div id="url-cap-nhat-thu-tu-chuong" class="text-center text-muted fst-italic"
                data-url="{{ route('thu-tu-chuong.update', $baiGiang->id) }}">
                Giữ vào một chương 0.5s sau đó có thể kéo thả để thiết lập vị trí</div>
              <form action="#" method="POST">
                @csrf
                @method('PUT')
                <table class="table table-hover table-striped caption-top" style="min-width: 600px;">
                  <caption>Có {{ $listChuong->count() }} bản ghi chương</caption>
                  <thead>
                    <tr>
                      <th scope="col">
                        <input type="checkbox" class="form-check-input" name="" id="check-all">
                      </th>
                      <th scope="col">Tiêu đề</th>
                      <th scope="col">Mô tả ngắn</th>
                      <th scope="col" class="text-center">Thao tác</th>
                    </tr>
                  </thead>
                  <tbody id="list-chuong">

                    @forelse ($listChuong as $chuong)
                      <tr data-id="{{ $chuong->id }}">
                        <th scope="row">
                          <input type="checkbox" class="form-check-input row-checkbox" name="" id="">
                        </th>
                        <td class="align-middle">{{ $chuong->tieu_de }}</td>
                        <td class="align-middle">{{ $chuong->mo_ta_ngan }}</td>
                        <td class="text-center align-middle" style="min-width: 140px;">
                          <button type="button" class="btn btn-info btn-sm me-1 btn-detail-chuong"
                            data-tieu-de="{{ $chuong->tieu_de }}" data-url="{{ route('bai.list', $chuong->id) }}">
                            <i class="fas fa-eye"></i>
                          </button>
                          <a href="{{ route('chuong.edit', $chuong->id) }}" class="btn btn-warning btn-sm me-1">
                            <i class="fas fa-edit"></i>
                          </a>
                          <button type="button" class="btn btn-danger btn-sm btn-xoa-chuong"
                            data-url="{{ route('chuong.delete', $chuong->id) }}">
                            <i class="fas fa-trash-alt"></i>
                          </button>
                        </td>
                      </tr>
                    @empty
                      <tr>
                        <td colspan="5" class="text-center">
                          Không tìm thấy chương hoặc bài giảng chưa có chương nào
                        </td>
                      </tr>
                    @endforelse
                  </tbody>
                </table>

                @if ($listChuong->count() > 0)
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

            {{-- Dấu : báo hiệu cho blade đây là biểu thức php --}}
            {{-- <x-pagination :paginator="$listChuong" base-url="{{ route('bai-giang.detail', $baiGiang->id) }}" /> --}}

          </div>
        </div>
      </div>
    </div>

    <form action="{{ route('chuong.store', $baiGiang->id) }}" method="POST">
      @csrf
      <div class="modal fade" id="modal-them-chuong" tabindex="-1" aria-labelledby="" aria-hidden="true"
        data-bs-focus="false">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
          <div class="modal-content">
            <div class="modal-header bg-primary text-white">
              <h5 class="modal-title">
                <i class="far fa-plus-square me-2"></i>Thêm chương
              </h5>
              <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                aria-label="Close"></button>
            </div>
            <div class="modal-body custom-scrollbar">
              <div class="mb-3">
                <label for="" class="form-label">
                  Tiêu đề chương
                  <span class="text-muted">(100 ký tự)</span>
                  <abbr class="text-danger" title="Bắt buộc">*</abbr>
                </label>
                <input type="text" name="tieu_de" class="form-control" id="" required maxlength="100"
                  placeholder="Nhập tiêu đề chương...">
              </div>
              <div class="mb-3">
                <label for="" class="form-label">Mô tả ngắn <span class="text-muted">(255 ký tự)</span></label>
                <textarea name="mo_ta_ngan" id="" class="form-control" rows="6" maxlength="255"
                  placeholder="Nhập nội dung mô tả chương..."></textarea>
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

    <div class="modal fade" id="modal-chi-tiet-chuong" tabindex="-1" aria-labelledby="" aria-hidden="true"
      data-bs-focus="false">
      <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header bg-info text-white">
            <h5 class="modal-title" id="">Chi tiết chương</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
              aria-label="Close"></button>
          </div>
          <div class="modal-body custom-scrollbar">
            <h5>Danh sách các bài trong Chương <span class="fst-italic text-secondary" id="tieu-de-chuong"></span></h5>
            <hr>
            <div class="table-responsive custom-scrollbar">
              <table class="table table-hover table-striped" style="min-width: 600px;">
                <thead>
                  <tr>
                    <th scope="col">Thứ tự</th>
                    <th scope="col">Tiêu đề</th>
                    <th scope="col">Ngày tạo</th>
                  </tr>
                </thead>
                <tbody id="section-list-bai">
                  <tr class="text-center">
                    <td colspan="3">Không có bài nào trong chương</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="modal-xoa-chuong" tabindex="-1" aria-labelledby="" aria-hidden="true"
      data-bs-focus="false">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header bg-danger text-white">
            <h5 class="modal-title" id="">Xác nhận xóa Chương</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
              aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <p>Bạn có chắc chắn muốn xóa chương này không?</p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
            <form action="" method="POST" class="d-inline-block">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn btn-danger" id="btn-confirm-xoa-chuong">Xóa chương</button>
            </form>
          </div>
        </div>
      </div>
    </div>

    <form action="{{ route('bai-giang.update', $baiGiang->id) }}" method="POST" enctype="multipart/form-data">
      @csrf
      @method('PUT')
      <div class="modal fade" id="modal-chinh-sua-bai-giang" tabindex="-1" aria-labelledby="editCourseModalLabel"
        aria-hidden="true">
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
                  <span class="text-muted">(100 ký tự)</span>
                  <abbr class="text-danger" title="Bắt buộc">*</abbr>
                </label>
                <input type="text" name="ten" class="form-control" id="ten-bai-giang"
                  value="{{ $baiGiang->ten }}" placeholder="Nhập tên bài giảng...">
              </div>
              <div class="mb-3">
                <label for="" class="form-label">Chọn học phần <abbr class="text-danger"
                    title="Bắt buộc">*</abbr></label>
                <select name="id_hoc_phan" id="" required class="form-control">
                  @foreach ($listHocPhan as $hocPhan)
                    <option
                      value="{{ $hocPhan->id }}"{{ $baiGiang->hoc_phan->id == $hocPhan->id ? ' selected' : '' }}>
                      {{ $hocPhan->ten }}
                    </option>
                  @endforeach
                </select>
              </div>
              <div class="mb-3">
                <label for="" class="form-label">Mô tả ngắn <span class="text-muted">(255 ký tự)</span></label>
                <textarea name="mo_ta_ngan" id="mo-ta-bai-giang" class="form-control" rows="6"
                  placeholder="Nhập nội dung mô tả bài giảng...">{{ $baiGiang->mo_ta_ngan }}</textarea>
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
                  <img src="{{ asset('storage/' . $baiGiang->hinh_anh) }}" id="hinh-anh-bai-giang"
                    class="img-thumbnail" style="max-width: 200px; max-height: 200px;">
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
              <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
            </div>
          </div>
        </div>
      </div>
    </form>

    <form action="{{ route('bai-giang.delete', $baiGiang->id) }}" method="POST">
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
  <link rel="stylesheet" href="{{ asset('modules/bai-giang/css/chi-tiet.css') }}">
  <link rel="stylesheet" href="{{ asset('vendor/sortable/css/sortable.css') }}">
@endsection

@section('scripts')
  <script src="{{ asset('vendor/sortable/js/sortable.min.js') }}"></script>
  <script src="{{ asset('vendor/sortable/js/jquery-sortable.min.js') }}"></script>
  <script src="{{ asset('modules/bai-giang/js/chi-tiet.js') }}"></script>

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
