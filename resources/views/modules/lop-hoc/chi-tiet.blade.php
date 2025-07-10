@extends('layouts.app')

@section('title', 'Lớp học - chi tiết')

@section('content')
  <form id="csrfForm" class="d-none">
    @csrf
  </form>
  <!-- Main Content -->
  <div class="col bg-light p-4 overflow-auto custom-scrollbar">

    <div id="info-lop-hoc" class="d-none" data-id-lop-hoc="{{ $lopHocPhan->id }}"></div>

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

    <!-- PHẦN DƯỚI: TAB NỘI DUNG -->
    <ul class="nav nav-tabs mb-3" id="classTab" role="tablist">
      <li class="nav-item" role="presentation">
        <button class="nav-link active fw-bold" id="news-tab" data-bs-toggle="tab" data-bs-target="#news" type="button"
          role="tab">Bản tin <span class="badge text-bg-danger">{{ $listBanTin->count() }}</span>
        </button>
      </li>
      <li class="nav-item fw-bold" role="presentation">
        @php
          $tongSoBaiCongKhai = $listChuongTrongLop
              ->flatten(1)
              ->filter(function ($bai) {
                  return $bai->pivot->cong_khai == true;
              })
              ->count();
        @endphp
        <button class="nav-link" id="lecture-tab" data-bs-toggle="tab" data-bs-target="#lecture" type="button"
          role="tab">Bài giảng <span class="badge text-bg-danger">{{ $tongSoBaiCongKhai }}</span></button>
      </li>
      <li class="nav-item fw-bold" role="presentation">
        <button class="nav-link" id="exam-tab" data-bs-toggle="tab" data-bs-target="#exam" type="button"
          role="tab">Bài kiểm tra <span class="badge text-bg-danger"></span></button>
      </li>
      <li class="nav-item fw-bold" role="presentation">
        <button class="nav-link" id="member-tab" data-bs-toggle="tab" data-bs-target="#member" type="button"
          role="tab">Thành viên <span class="badge text-bg-danger">{{ $thanhVien->count() }}</span></button>
      </li>
    </ul>

    <!--Nội dung-->
    <div class="tab-content" id="classTabContent">

      <!--Bản tin-->
      <div class="tab-pane fade show active" id="news" role="tabpanel">

        <!-- PHẦN TRÊN: THÔNG TIN LỚP HỌC -->
        <div class="card mb-4 shadow-sm border-0 rounded-4 overflow-hidden">
          <div class="row g-0">
            <!-- Hình ảnh lớp học -->
            <div class="col-md-4 bg-light d-flex align-items-center">
              <img src="{{ asset('storage/' . $lopHocPhan->hinh_anh) }}"
                class="w-100 h-100 object-fit-cover rounded-start" alt="">
            </div>

            <!-- Nội dung lớp học -->
            <div class="col-md-8 position-relative bg-white">
              <div class="card-body px-4 py-4">
                <h4 class="card-title mb-3 fw-bold text-primary">
                  <i class="fas fa-chalkboard-teacher me-2 text-secondary"></i>{{ $lopHocPhan->ten }}
                </h4>

                <ul class="list-unstyled mb-3">
                  <li class="mb-2">
                    <i class="fas fa-user me-2 text-muted"></i><strong>Giảng viên:</strong>
                    {{ $lopHocPhan->giang_vien->ho_ten }}
                  </li>
                  <li class="mb-2">
                    <i class="fas fa-barcode me-2 text-muted"></i><strong>Mã lớp:</strong>
                    {{ $lopHocPhan->ma }}
                  </li>
                  <li class="mb-2">
                    <i class="fas fa-building me-2 text-muted"></i><strong>Khoa:</strong>
                    {{ $lopHocPhan->khoa->ten }}
                  </li>
                  @if (session('vai_tro') == 'Giảng viên')
                    <li class="mb-2">
                      <i class="fas fa-book me-2 text-muted"></i><strong>Bài giảng:</strong>
                      {{ $lopHocPhan->bai_giang->ten }}
                    </li>
                  @endif
                </ul>

                <p class="card-text mb-0">
                  <i class="fas fa-info-circle me-2 text-muted"></i>
                  <small class="text-muted">{{ $lopHocPhan->mo_ta_ngan }}</small>
                </p>

                <!-- Nút hành động -->
                <div class="class-action-btn position-absolute top-0 end-0 p-3">
                  <div class="dropdown">
                    <button class="btn btn-light btn-sm shadow-sm dropdown-toggle remove-arrow-down" type="button"
                      data-bs-toggle="dropdown" aria-expanded="false">
                      <i class="fas fa-ellipsis-v text-secondary"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                      @if (session('vai_tro') == 'Giảng viên')
                        <li>
                          <button class="dropdown-item" type="button" data-bs-toggle="modal"
                            data-bs-target="#modal-chinh-sua-lop-hoc-phan">
                            <i class="fas fa-edit me-2"></i>Chỉnh sửa
                          </button>
                        </li>
                        <li>
                          <button class="dropdown-item btn-delete-class" type="button"
                            data-url-delete="{{ route('lop-hoc-phan.delete', $lopHocPhan->id) }}"
                            data-url-my-class="{{ route('lop-hoc.lop-hoc-cua-toi') }}">
                            <i class="fas fa-trash-alt me-2 text-danger"></i>Xóa
                          </button>
                        </li>
                      @elseif (session('vai_tro') == 'Sinh viên')
                        <li>
                          <button class="dropdown-item btn-leave-class" type="button"
                            data-url-leave="{{ route('lop-hoc-phan.leave', $lopHocPhan->id) }}"
                            data-url-my-class="{{ route('lop-hoc.lop-hoc-cua-toi') }}">
                            <i class="fas fa-sign-out-alt me-2 text-warning"></i>Rời khỏi lớp
                          </button>
                        </li>
                      @endif
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>


        <div id="wp-list-ban-tin">
          @include('partials.ban-tin.list', [$listBanTin, $lopHocPhan, $nguoiDung])
        </div>

        @if (session('vai_tro') == 'Giảng viên')
          <button type="button" class="newsletter-add-btn btn btn-primary rounded-circle" title="Tạo bản tin mới"
            data-bs-toggle="modal" data-bs-target="#modal-them-ban-tin">
            <i class="fas fa-plus"></i>
          </button>
        @endif
      </div>

      <!--Bài giảng-->
      <div class="tab-pane fade" id="lecture" role="tabpanel">
        <div class="card">
          <div class="card-body">
            <div class="accordion" id="accordion-chuong">

              @include('partials.lop-hoc-phan.chi-tiet.list-bai', [
                  $listChuong,
                  $listChuongTrongLop,
                  $lopHocPhan,
              ])

            </div>
          </div>
        </div>
      </div>

      <!--Bài kiểm tra-->
      <div class="tab-pane fade" id="exam" role="tabpanel">
        <div class="card">
          <div class="card-body">
            <!-- Danh sách bài kiểm tra -->
            <div class="row row-cols-1 row-cols-md-2 g-3 " id="danhSachBaiKiemTra">
            </div>
          </div>

          <!-- Modal chi tiết bài kiểm tra -->
          <div class="modal fade" id="modalChiTiet" tabindex="-1">
            <div class="modal-dialog modal-lg modal-dialog-centered">
              <div class="modal-content">
                <div class="modal-header d-flex align-items-center">
                  <div class="modal-title flex-grow-1">
                  </div>

                  <div class="modal-actions ms-auto d-flex gap-2">

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                </div>
                <div class="modal-body overflow-auto" id="modalChiTietBody" style="max-height: 60vh;">
                  <!-- Nội dung sẽ được render bằng JS -->
                </div>
                <div class="modal-footer">
                </div>
              </div>
            </div>
          </div>

          {{-- Modal thêm bài kiểm tra --}}
          @if (session('vai_tro') == 'Giảng viên')
            <div class="modal fade" id="addExerciseModal" tabindex="-1" aria-labelledby="addExerciseModalLabel"
              aria-hidden="true">
              <div class="modal-dialog modal-lg modal-dialog-scrollable">
                <div class="modal-content">

                  <!-- HEADER: luôn cố định -->
                  <div
                    class="modal-header bg-gradient bg-success text-white py-3 px-4 shadow-sm border-bottom border-white rounded-top">
                    <h5 class="modal-title fw-bold d-flex align-items-center gap-2" id="addExerciseModalLabel">
                      <i class="fas fa-file-alt fa-lg"></i> Tạo mới bài kiểm tra
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                      aria-label="Close"></button>
                  </div>

                  <!-- BODY: chỉ phần này cuộn -->
                  <div class="modal-body custom-scrollbar">
                    <form id="newExerciseForm">
                      <div class="row mb-4">
                        <!-- Tiêu đề -->
                        <div class="col-lg-10 col-sm-9 mb-3 mb-sm-0">
                          <label for="newExerciseTitle" class="form-label fw-semibold text-primary">
                            Tiêu đề <span class="text-danger">*</span>
                          </label>
                          <input type="text" class="form-control rounded-3 shadow-sm" name="tieuDe"
                            id="newExerciseTitle" placeholder="Nhập tiêu đề bài tập">
                          <div class="invalid-feedback fw-bold mt-1">
                            Vui lòng nhập tiêu đề cho bài kiểm tra.
                          </div>
                        </div>

                        <!-- Điểm tối đa -->
                        <div class="col-lg-2 col-sm-3">
                          <label for="newExerciseMaxScore" class="form-label fw-semibold text-primary">
                            Điểm tối đa
                          </label>
                          <input type="number" class="form-control rounded-3 shadow-sm" name="diemToiDa"
                            id="newExerciseMaxScore" placeholder="100" min="0" max="100">
                          <div class="invalid-feedback fw-bold mt-1">
                            Vui lòng nhập điểm tối đa
                          </div>
                        </div>
                      </div>

                      <!-- Thời gian bắt đầu và kết thúc -->
                      <div class="row mb-4">
                        <div class="col-md-6">
                          <label for="startTime" class="form-label fw-semibold text-primary">
                            Thời gian bắt đầu
                          </label>
                          <input type="text" class="form-control rounded-3 shadow-sm" id="startTime"
                            name="thoiGianBatDau" placeholder="Chọn thời gian" autocomplete="off">
                          <div class="invalid-feedback fw-bold mt-1">
                            Vui lòng chọn thời gian bắt đầu
                          </div>
                        </div>
                        <div class="col-md-6">
                          <label for="endTime" class="form-label fw-semibold text-primary">
                            Thời gian kết thúc
                          </label>
                          <input type="text" class="form-control rounded-3 shadow-sm" id="endTime"
                            name="thoiGianKetThuc" placeholder="Chọn thời gian" autocomplete="off">
                          <div class="invalid-feedback fw-bold mt-1">
                            Vui lòng chọn thời gian kết thúc
                          </div>
                        </div>
                      </div>

                      <!-- Cho phép nộp trễ -->
                      <div class="d-flex justify-content-center mt-3">
                        <div
                          class="form-check form-check-lg d-flex align-items-center gap-2 px-3 py-2 border rounded-3 shadow-sm bg-light">
                          <input class="form-check-input fs-4" type="checkbox" value="1" id="choPhepNopTre"
                            name="choPhepNopTre" style="width: 1em; height: 1em;">
                          <label class="form-check-label fw-bold fs-5 text-dark" for="choPhepNopTre">
                            Cho phép nộp quá hạn
                          </label>
                        </div>
                      </div>


                      <hr>

                      <input type="hidden" name="idLopHoc" id="idLopHoc" value="{{ $lopHocPhan->id }}">

                      <div id="questionsFormContainer-them">
                        <h6>Danh sách câu hỏi:</h6>
                        <div class="question-item mb-4 p-3 border rounded bg-light"></div>
                      </div>

                      <!-- THÔNG BÁO nếu chưa có câu hỏi -->
                      <div class="text-center text-danger fw-bold mb-3 d-none" id="noQuestionsMessage">
                        Vui lòng thêm ít nhất một câu hỏi.
                      </div>
                    </form>
                  </div>

                  <!-- FOOTER: chứa nút cố định -->
                  <div class="modal-footer d-flex flex-column align-items-stretch gap-3 border-0 pt-0">
                    <!-- Nhóm nút Thêm và Chọn file -->
                    <div class="d-flex gap-3 w-100">
                      <!-- Nút thêm câu hỏi -->
                      <button type="button" class="btn btn-outline-success flex-fill rounded-3 shadow-sm fw-semibold"
                        id="addQuestionBtn">
                        <i class="fas fa-plus me-2"></i>Thêm câu hỏi mới
                      </button>

                      <!-- Nút chọn file -->
                      <label class="btn btn-outline-info flex-fill rounded-3 shadow-sm fw-semibold m-0"
                        for="excelFileInput">
                        <i class="fas fa-file-excel me-2"></i>Chọn file Excel
                      </label>
                      <input type="file" id="excelFileInput" accept=".xlsx, .xls" class="d-none">
                    </div>

                    <!-- Nhóm nút Hủy và Lưu -->
                    <div class="d-flex justify-content-end gap-2 w-100">
                      <button type="button" class="btn btn-light border shadow-sm px-4 fw-semibold"
                        data-bs-dismiss="modal">Hủy</button>
                      <button type="submit" form="newExerciseForm"
                        class="btn btn-primary px-4 shadow-sm fw-semibold">Lưu bài kiểm
                        tra</button>
                    </div>
                  </div>

                </div>
              </div>
            </div>
          @endif

        </div>
        @if (session('vai_tro') == 'Giảng viên')
          <button type="button" class="newsletter-add-btn btn btn-primary rounded-circle" id="addNewExerciseBtn"
            title="Bài kiểm tra mới">
            <i class="fas fa-plus"></i>
          </button>
        @endif
      </div>

      <!--Thành viên lớp-->
      <div class="tab-pane fade" id="member" role="tabpanel">
        <div class="card">

          <div class="card-body">
            <div id="list-thanh-vien">
              @include('partials._thanh-vien-lop', [$lopHocPhan, $thanhVien])
            </div>
          </div>
        </div>
        @if (session('vai_tro') == 'Giảng viên')
          <button type="button" class="newsletter-add-btn btn btn-primary rounded-circle" title="Thêm thành viên"
            id="member-add-btn" data-bs-toggle="modal" data-bs-target="#addMemberModal">
            <i class="fas fa-plus"></i>
          </button>
        @endif
      </div>

    </div>

    {{-- Modal thêm bản tin --}}
    @if (session('vai_tro') == 'Giảng viên')
      <form action="{{ route('ban-tin.store', $lopHocPhan->id) }}" method="POST">
        @csrf
        <div class="modal fade" id="modal-them-ban-tin" tabindex="-1" aria-labelledby="" aria-hidden="true"
          data-bs-focus="false">
          <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
              <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                  <i class="far fa-plus-square me-2"></i>Tạo bản tin mới
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                  aria-label="Close"></button>
              </div>
              <div class="modal-body custom-scrollbar">
                <div class="mb-3">
                  <label for="" class="form-label">
                    Nội dung thông báo: <abbr class="text-danger" title="Bắt buộc">*</abbr>
                  </label>
                  <textarea class="form-control tinymce" name="noi_dung" id="noi-dung-ban-tin-them"
                    placeholder="Nhập nội dung thông báo chi tiết..."></textarea>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="submit" class="btn btn-primary">Đăng bản tin</button>
              </div>
            </div>
          </div>
        </div>
      </form>
    @endif

    {{-- Modal sửa bản tin --}}
    @if (session('vai_tro') == 'Giảng viên')
      <form action="" method="POST">
        @csrf
        @method('PUT')
        <div class="modal fade" id="modal-chinh-sua-ban-tin" tabindex="-1" aria-labelledby="" aria-hidden="true"
          data-bs-focus="false">
          <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
              <div class="modal-header bg-warning text-white">
                <h5 class="modal-title">
                  <i class="fas fa-edit me-2"></i></i>Chỉnh sửa bản tin
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                  aria-label="Close"></button>
              </div>
              <div class="modal-body custom-scrollbar">
                <div class="mb-3">
                  <label for="" class="form-label">
                    Nội dung thông báo: <abbr class="text-danger" title="Bắt buộc">*</abbr>
                  </label>
                  <textarea class="form-control tinymce" name="noi_dung" id="noi-dung-ban-tin-chinh-sua"
                    placeholder="Nhập nội dung thông báo chi tiết..."></textarea>
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
    @endif

    {{-- Modal import thành viên bằng file excel --}}
    <div class="modal fade" id="addMemberModal" tabindex="-1" aria-labelledby="" aria-hidden="true"
      data-bs-focus="false">
      <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
          <div class="modal-header bg-primary text-white">
            <h5 class="modal-title">Thêm thành viên vào lớp</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
              aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form action="{{ route('thanh-vien-lop.import') }}" method="POST" enctype="multipart/form-data">
              @csrf
              <div class="guide-import">
                <h3>Hướng dẫn import:</h3>
                <p class="text-dark mb-0"><b>Bước 1: </b>Tạo một file excel (.xlsx) trên máy tính</p>
                <p class="text-dark mb-0"><b>Bước 2: </b>Đặt tên cho ô đầu tiên là "email"</p>
                <p class="text-dark mb-0"><b>Bước 3: </b>Nhập lần lượt các email của sinh viên cần thêm vào
                  lớp</p>
                <p class="mb-3 mt-1"><b>Lưu ý: </b>Các email không tồn tại trên hệ thống sẽ bị bỏ qua</p>
                <div class="text-center">
                  <h3 class="text-info">Ví dụ:</h3>
                  <img src="{{ asset('images/guide-import-dssv-from-excel.png') }}" class="img-fluid rounded shadow-lg"
                    alt="Hướng dẫn chèn danh sách sinh viên">
                </div>
                <p class="mb-3 mt-2"><b>Bước 4: </b>Chọn file excel vừa tạo</p>
              </div>
              <div class="input-group">
                <input type="hidden" name="id_lop_hoc_phan" value="{{ $lopHocPhan->id }}">
                <input type="file" name="file" class="form-control" accept=".xls,.xlsx" required>
                <button class="btn btn-outline-primary" type="submit">Thực hiện</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    {{-- Modal chỉnh sửa lớp học phần --}}
    <form action="{{ route('lop-hoc-phan.update', $lopHocPhan->id) }}" method="POST" enctype="multipart/form-data">
      @csrf
      @method('PUT')
      <div class="modal fade" id="modal-chinh-sua-lop-hoc-phan" tabindex="-1" aria-hidden="true"
        data-bs-focus="false" data-view="lop-hoc-cua-toi">
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
                <input type="text" name="ten" class="form-control" value="{{ old('ten', $lopHocPhan->ten) }}"
                  required maxlength="100">
              </div>
              <div class="mb-3">
                <label for="" class="form-label">Khoa</label>
                <select class="form-select" name="id_khoa" id="select-khoa" required>
                  <option selected disabled value="">-- Chọn khoa --</option>
                  @foreach ($listKhoa as $khoa)
                    <option value="{{ $khoa->id }}"{{ $khoa->id == $lopHocPhan->id_khoa ? ' selected' : '' }}>
                      {{ $khoa->ten }}</option>
                  @endforeach
                </select>
              </div>
              <div class="mb-3">
                <label for="" class="form-label">Bài giảng</label>
                <select class="form-select" name="id_bai_giang" id="select-bai-giang" required>
                  <option selected disabled value="">-- Chọn bài giảng --</option>
                  @foreach ($listBaiGiang as $baiGiang)
                    <option
                      value="{{ $baiGiang->id }}"{{ $baiGiang->id == $lopHocPhan->id_bai_giang ? ' selected' : '' }}>
                      {{ $baiGiang->ten }}</option>
                  @endforeach
                </select>
              </div>
              <div class="mb-3">
                <label for="" class="form-label">Mô tả ngắn (255 từ)</label>
                <textarea name="mo_ta_ngan" rows="5" class="form-control" maxlength="255">{{ old('mo_ta_ngan', $lopHocPhan->mo_ta_ngan) }}</textarea>
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
                  <img src="{{ asset('storage/' . $lopHocPhan->hinh_anh) }}" data-url="{{ asset('storage/') }}"
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
  <link rel="stylesheet" href="{{ asset('modules/lop-hoc/css/chi-tiet-lop-hoc.css') }}">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endsection

@section('scripts')
  <script src="{{ asset('vendor/tinymce-5/tinymce.min.js') }}"></script>
  <script src="{{ asset('js/config-tinymce.js') }}"></script>
  <script src="{{ asset('modules/lop-hoc/js/chi-tiet-lop-hoc.js') }}"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
  <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/vn.js"></script>

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
