@extends('layouts.app')

@section('title', 'Tài liệu - Danh sách bài giảng')

@section('content')
  <form id="csrfForm" class="d-none">
    @csrf
  </form>
  <div class="col bg-light px-4 pt-4 overflow-auto custom-scrollbar">
    <h2 class="mb-4">Chi tiết tài liệu bài giảng</h2>

    <a href="{{ route('muc-bai-giang.index') }}" class="btn btn-outline-secondary mb-4">
      <i class="fas fa-arrow-alt-circle-left me-2"></i>Danh sách mục bài giảng
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

    @if (session('message'))
      <div class="alert alert-{{ session('status') }} alert-dismissible fade show" role="alert">
        {{ session('message') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

    <div class="row">
      <div class="col-lg-4 mb-4">
        <div class="card h-100 shadow-sm">
          <img src="{{ asset('storage/' . $mucBaiGiang->hinh_anh) }}" class="card-img-top" alt="">
          <div class="card-body" id="courseDetailContent">
            <h4 id="courseTitle">{{ $mucBaiGiang->ten }}</h4>
            <p><strong>Mô tả:</strong> <span id="courseDescription">{{ $mucBaiGiang->mo_ta_ngan }}</span></p>
            <p><strong>Số lượng bài giảng:</strong> <span id="lessonCount">{{ $mucBaiGiang->so_bai_giang }}</span>
              bài</p>
            <p><strong>Ngày tạo:</strong> <span id="courseCreationDate">{{ $mucBaiGiang->ngay_tao }}</span></p>
            <hr>
            <button class="btn btn-warning btn-sm me-2" data-bs-toggle="modal"
              data-bs-target="#modal-chinh-sua-muc-bai-giang">
              <i class="fas fa-edit me-1"></i>Chỉnh sửa
            </button>
            <button class="btn btn-danger btn-sm" id="delete-doc-btn" data-bs-toggle="modal"
              data-bs-target="#modal-xoa-muc-bai-giang">
              <i class="fas fa-trash-alt me-1"></i>Xóa
            </button>
          </div>
        </div>
      </div>

      <div class="col-lg-8 mb-4">
        <div class="card shadow-sm">
          <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Danh sách các bài giảng</h5>
            <a href="{{ route('bai-giang.create', $mucBaiGiang->id) }}" class="btn btn-light btn-sm">
              <i class="fas fa-plus-circle me-2"></i>Tạo mới bài giảng
            </a>
          </div>
          <div class="card-body">
            <form action="{{ route('muc-bai-giang.detail', $mucBaiGiang->id) }}" method="GET">
              <div class="input-group mb-3">
                <input type="text" class="form-control" name="search" value="{{ request()->input('search') }}"
                  placeholder="Tìm kiếm bài giảng theo tên..." id="">
                <button class="btn btn-outline-secondary">
                  <i class="fas fa-search"></i> </button>
              </div>
            </form>

            <div class="table-responsive custom-scrollbar">
              <table class="table table-hover table-striped" style="min-width: 600px;">
                <thead>
                  <tr>
                    <th scope="col">STT</th>
                    <th scope="col">Tiêu đề bài giảng</th>
                    <th scope="col">Ngày tạo</th>
                    <th scope="col" class="text-center">Thao tác</th>
                  </tr>
                </thead>
                <tbody>
                  @php
                    $page = request()->query('page') ?: 1;
                    $start = ($page - 1) * $numPerPage;
                  @endphp
                  @foreach ($listBaiGiang as $baiGiang)
                    <tr>
                      <th scope="row">{{ ++$start }}</th>
                      <td>{{ $baiGiang->tieu_de }}</td>
                      <td>{{ $baiGiang->ngay_tao }}</td>
                      <td class="text-center">
                        <button class="btn btn-info btn-sm me-1 btn-detail-bai-giang"
                          data-url="{{ route('bai-giang.detail', $baiGiang->id) }}">
                          <i class="fas fa-eye"></i>
                        </button>
                        <a href="{{ route('bai-giang.edit', $baiGiang->id) }}"
                          class="btn btn-warning btn-sm me-1 edit-lesson-btn">
                          <i class="fas fa-edit"></i>
                        </a>
                        <button class="btn btn-danger btn-sm btn-xoa-bai-giang"
                          data-url="{{ route('bai-giang.delete', $baiGiang->id) }}">
                          <i class="fas fa-trash-alt"></i>
                        </button>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>

            {{-- Dấu : báo hiệu cho blade đây là biểu thức php --}}
            <x-pagination :paginator="$listBaiGiang" base-url="{{ route('muc-bai-giang.detail', $mucBaiGiang->id) }}" />

          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="modal-chi-tiet-bai-giang" tabindex="-1" aria-labelledby="ChiTietBaiGiangModalLabel"
      aria-hidden="true">
      <div class="modal-dialog modal-xl modal-dialog-scrollable modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header bg-info text-white">
            <h5 class="modal-title" id="ChiTietBaiGiangModalLabel">Chi tiết bài giảng</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <p><strong>Tên bài giảng:</strong> <span id="tieu-de-bai-giang"></span></p>
            <p><strong>Ngày tạo:</strong> <span id="ngay-tao-bai-giang"></span></p>
            <hr>
            <h6>Nội dung:</h6>
            <div id="noi-dung-bai-giang"></div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="modal-xoa-bai-giang" tabindex="-1" aria-labelledby="XoaBaiGiangModalLabel"
      aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header bg-danger text-white">
            <h5 class="modal-title" id="XoaBaiGiangModalLabel">Xác nhận xóa bài giảng</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
              aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <p>Bạn có chắc chắn muốn xóa Bài Giảng này không?</p>
            <p class="text-danger">Hành động này sẽ xóa bài giảng trong mục bài giảng và không thể hoàn tác!</p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
            <form action="" method="POST" class="d-inline-block">
              @csrf
              @method('DELETE')
              <input type="hidden" name="id_muc_bai_giang" value="{{ $mucBaiGiang->id }}">
              <button type="submit" class="btn btn-danger" id="btn-confirm-xoa-bai-giang">Xóa Bài
                Giảng</button>
            </form>
          </div>
        </div>
      </div>
    </div>

    <form action="{{ route('muc-bai-giang.update', $mucBaiGiang->id) }}" method="POST" enctype="multipart/form-data">
      @csrf
      @method('PUT')
      <div class="modal fade" id="modal-chinh-sua-muc-bai-giang" tabindex="-1" aria-labelledby="editCourseModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
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
                <input type="text" name="ten" class="form-control" id="ten-muc-bai-giang"
                  value="{{ $mucBaiGiang->ten }}">
              </div>
              <div class="mb-3">
                <label for="" class="form-label">Mô tả ngắn <span class="text-muted">(255 từ)</span></label>
                <textarea name="mo_ta_ngan" id="mo-ta-muc-bai-giang" class="form-control" rows="6">{{ $mucBaiGiang->mo_ta_ngan }}</textarea>
              </div>
              <div class="mb-3">
                <label for="img-upload-modal-chinh-sua" class="form-label">
                  Hình ảnh <span class="text-muted">(không bắt buộc)</span>
                </label>
                <input class="form-control" type="file" name="hinh_anh" id="img-upload-modal-chinh-sua"
                  accept="image/*">
                <div id="img-preview-container-modal-chinh-sua" class="mt-3 position-relative d-inline-block">
                  <img src="#" alt="Ảnh xem trước" class="img-preview img-thumbnail"
                    style="display: none; max-width: 200px; max-height: 200px;">
                  <span class="img-remove-btn close-btn" style="display: none;">&times;</span>
                </div>
                <div class="mt-3 d-inline-block">
                  <img src="{{ asset('storage/' . $mucBaiGiang->hinh_anh) }}" id="hinh-anh-muc-bai-giang"
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

    <form action="{{ route('muc-bai-giang.delete', $mucBaiGiang->id) }}" method="POST">
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
  <link rel="stylesheet" href="{{ asset('modules/tailieu/css/chi-tiet.css') }}">
@endsection

@section('scripts')
  <script src="{{ asset('modules/tailieu/js/chi-tiet.js') }}"></script>
  <script src="{{ asset('modules/baigiang/js/danh-sach-bai-giang.js') }}"></script>
@endsection
