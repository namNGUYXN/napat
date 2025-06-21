@extends('layouts.app')

@section('title', 'Lớp học - chi tiết')

@section('content')
  <form id="csrfForm" class="d-none">
    @csrf
  </form>
  <!-- Main Content -->
  <div class="col bg-light p-4 overflow-auto custom-scrollbar">

    <div id="info-lop-hoc" class="d-none" data-id-lop-hoc="{{ $lop->id }}" data-id-hoc-phan="{{ $hocPhan->id }}"></div>

    <!-- PHẦN DƯỚI: TAB NỘI DUNG -->
    <ul class="nav nav-tabs mb-3" id="classTab" role="tablist">
      <li class="nav-item" role="presentation">
        <button class="nav-link active" id="news-tab" data-bs-toggle="tab" data-bs-target="#news" type="button"
          role="tab">Bảng tin <span class="badge text-bg-danger">4</span>
        </button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link" id="lecture-tab" data-bs-toggle="tab" data-bs-target="#lecture" type="button"
          role="tab">Bài giảng <span class="badge text-bg-danger">4</span></button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link" id="exam-tab" data-bs-toggle="tab" data-bs-target="#exam" type="button"
          role="tab">Bài kiểm tra <span class="badge text-bg-danger">4</span></button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link" id="member-tab" data-bs-toggle="tab" data-bs-target="#member" type="button"
          role="tab">Thành viên <span class="badge text-bg-danger">4</span></button>
      </li>
    </ul>

    <!--Nội dung-->
    <div class="tab-content" id="classTabContent">

      <!--Bản tin-->
      <div class="tab-pane fade show active" id="news" role="tabpanel">
        <!-- PHẦN TRÊN: THÔNG TIN LỚP HỌC -->
        <div class="card mb-4">
          <div class="row g-0">
            <div class="col-md-4">
              <img src="{{ asset('storage/' . $lop->hinh_anh) }}" class="img-fluid rounded-start" alt="">
            </div>
            <div class="col-md-8 position-relative">
              <div class="card-body">
                <h5 class="card-title mb-3">{{ $lop->ten }} - {{ $lop->ma }}</h5>
                <p class="card-text mb-1"><strong>Học phần:</strong> {{ $lop->hoc_phan->ten }}</p>
                <p class="card-text mb-1"><strong>Giảng viên:</strong> {{ $lop->giang_vien->ho_ten }}</p>
                <p class="card-text mb-1"><strong>Học kì:</strong> 2024 - 2025</p>
                <p class="card-text mt-3 mb-0">
                  <small class="text-muted">{{ $lop->mo_ta_ngan }}</small>
                </p>

                <div class="class-action-btn">
                  <div class="dropdown">
                    <button class="btn btn-transparent dropdown-toggle remove-arrow-down" type="button"
                      data-bs-toggle="dropdown" aria-expanded="false">
                      <i class="fas fa-ellipsis-v"></i>
                    </button>
                    <ul class="dropdown-menu">
                      <!-- Sinh viên -->
                      <li><button class="dropdown-item" type="button">Đăng ký lớp học</button></li>
                      <li><button class="dropdown-item" type="button">Rời lớp học</button></li>
                      <!-- Giảng viên -->
                      <li><button class="dropdown-item" type="button" data-bs-toggle="modal"
                          data-bs-target="#updateClassModal">Chỉnh sửa lớp học</button>
                      </li>
                      <li><button class="dropdown-item" type="button">Xóa lớp học</button></li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        @foreach ($banTin as $item)
          <!-- Mỗi bản tin là một thẻ -->
          <div class="card news-item overflow-hidden mb-5" style="cursor: pointer;">
            <div class="card-body d-flex">
              <!-- Avatar người đăng -->
              <img src="https://picsum.photos/id/54/400/400" class="border border-secondary rounded-circle me-3"
                alt="Avatar" width="40" height="40">
              <div class="flex-grow-1">
                <h6 class="card-title mb-3">{{ $item->thanh_vien_lop->nguoi_dung->vai_tro ?? '' }}
                  : {{ $item->thanh_vien_lop->nguoi_dung->ho_ten }}</h6>
                <div class="news-content">
                  {!! $item->noi_dung !!}
                </div>

                <!-- Nút hiển thị số phản hồi -->
                <div class="mt-2">
                  @if (count($item->list_ban_tin_con) > 0)
                    <a href="javascript:void(0)" class="text-primary toggle-comments" data-bs-toggle="collapse"
                      data-bs-target="#comments-{{ $item->id }}">
                      {{ count($item->list_ban_tin_con) }} phản hồi
                    </a>
                  @endif

                </div>
              </div>
            </div>

            <div class="card-footer bg-white">
              <!-- Form phản hồi -->
              <form>
                <div class="d-flex align-items-start gap-2 mb-3">
                  <img src="https://picsum.photos/id/54/400/400" alt="Avatar" class="rounded-circle" width="40"
                    height="40">
                  <div class="flex-grow-1">
                    <div class="input-group">
                      <input type="text" class="form-control" placeholder="Nhập phản hồi...">
                      <button class="btn btn-outline-primary" type="submit">Gửi</button>
                    </div>
                  </div>
                </div>
              </form>

              <!-- Danh sách bình luận - collapse -->
              <div class="collapse" id="comments-{{ $item->id }}">
                <!-- Bình luận 1 -->
                @foreach ($item->list_ban_tin_con as $cmt)
                  <div class="d-flex align-items-start mb-3">
                    <img src="https://picsum.photos/id/54/400/400" class="border border-secondary rounded-circle me-2"
                      alt="Avatar" width="36" height="36">
                    <div class="bg-light rounded p-2 flex-grow-1">
                      <strong>{{ $cmt->thanh_vien_lop->nguoi_dung->ho_ten }}</strong>
                      <p class="mb-0">{{ $cmt->noi_dung }}</p>
                    </div>
                  </div>
                @endforeach
              </div>
            </div>
          </div>
        @endforeach

        @if (session('vai_tro') == 'Giảng viên')
          <button type="button" class="newsletter-add-btn btn btn-primary rounded-circle" title="Tạo bản tin mới"
            data-bs-toggle="modal" data-bs-target="#newNewsletterModal">
            <i class="fas fa-plus"></i>
          </button>
        @endif
      </div>

      {{-- <!--Bài giảng-->
      <div class="tab-pane fade" id="lecture" role="tabpanel">
        <div class="card">
          <h5 class="card-header bg-dark text-white">Danh sách bài giảng</h5>
          <div class="card-body">

            @if (session('vai_tro') != 'Giảng viên')
              <div class="accordion">

                @foreach ($listChuong as $key => $chuong)
                  <!-- Chương 1 -->
                  <div class="accordion-item">
                    <h2 class="accordion-header" id="heading-{{ $key }}">
                      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapse-{{ $key }}" aria-expanded="false"
                        aria-controls="collapse-{{ $key }}">
                        {{ $chuong->tieu_de }}
                      </button>
                    </h2>
                    <div id="collapse-{{ $key }}" class="accordion-collapse collapse"
                      aria-labelledby="heading-{{ $key }}">
                      <div class="accordion-body">
                        <div class="list-group">

                          @foreach ($listBaiGiang as $value)
                            @if ($value->chuong->id == $chuong->id)
                              <div
                                class="list-group-item list-group-item-action list-group-item-info d-flex justify-content-between align-items-center">
                                <a href="#" class="text-decoration-none text-info-emphasis flex-grow-1">
                                  {{ $value->bai_giang->tieu_de }}
                                </a>
                              </div>
                            @endif
                          @endforeach

                        </div>
                      </div>
                    </div>
                  </div>
                @endforeach

              </div>
            @else
              <div class="accordion" id="accordion-chuong"></div>
            @endif

          </div>
        </div>
      </div>

      <!--Bài kiểm tra-->
      <div class="tab-pane fade" id="exam" role="tabpanel">
        <div class="card">
          <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Danh sách bài kiểm tra</h5>
            <button class="btn btn-light btn-sm">
              <i class="fas fa-plus-circle me-2"></i>Tạo bài kiểm tra
            </button>
          </div>
          <div class="card-body">
            <!-- Danh sách bài kiểm tra -->
            <div class="row row-cols-1 row-cols-md-2 g-3 mt-2">
              <!-- Bài kiểm tra 1 -->
              <div class="col">
                <div class="card shadow-sm h-100">
                  <div class="card-body">
                    <h5 class="card-title">Bài kiểm tra chương 1</h5>
                    <p class="card-text mb-1"><i class="bi bi-calendar-check"></i> Ngày đăng:
                      01/06/2025</p>
                    <p class="card-text"><i class="bi bi-clock"></i> Hạn chót: 05/06/2025</p>
                  </div>
                </div>
              </div>

              <!-- Bài kiểm tra 2 -->
              <div class="col">
                <div class="card shadow-sm h-100">
                  <div class="card-body">
                    <h5 class="card-title">Bài kiểm tra chương 2</h5>
                    <p class="card-text mb-1"><i class="bi bi-calendar-check"></i> Ngày đăng:
                      03/06/2025</p>
                    <p class="card-text"><i class="bi bi-clock"></i> Hạn chót: 07/06/2025</p>
                  </div>
                </div>
              </div>

              <!-- Các bài kiểm tra khác -->
            </div>
          </div>
        </div>
      </div> --}}

      <!--Thành viên lớp-->
      <div class="tab-pane fade" id="member" role="tabpanel">
        <div class="card">
          <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Danh sách thành viên</h5>
            <button class="btn btn-light btn-sm" id="member-add-btn" data-bs-toggle="modal"
              data-bs-target="#addMemberModal">
              <i class="fas fa-plus-circle me-2"></i>Thêm vào lớp
            </button>

            <div class="modal fade" id="addMemberModal" tabindex="-1" aria-labelledby="addMemberModalLabel"
              aria-hidden="true">
              <div class="modal-dialog modal-lg">
                <div class="modal-content">
                  <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="addMemberModalLabel">Thêm thành viên vào lớp</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                      aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                    <div class="mb-3">
                      <label for="studentSearch" class="text-dark form-label">Tìm kiếm sinh
                        viên:</label>
                      <div class="input-group">
                        <input type="text" class="form-control" id="studentSearch"
                          placeholder="Nhập họ tên hoặc email để tìm kiếm">
                        <button class="btn btn-outline-secondary" type="button" id="searchStudentBtn">
                          <i class="fas fa-search"></i>
                        </button>
                      </div>
                    </div>

                    <div class="alert alert-danger" id="noStudentsFoundAlert" role="alert" style="display: none;">
                      Không tìm thấy sinh viên nào phù hợp.
                    </div>

                    <div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
                      <table class="table table-hover">
                        <thead>
                          <tr>
                            <th scope="col" style="width: 50px;">Chọn</th>
                            <th scope="col">Họ và tên</th>
                            <th scope="col">Email</th>
                            <th scope="col">Số điện thoại</th>
                          </tr>
                        </thead>
                        <tbody id="studentListBody">
                          <tr>
                            <td colspan="4" class="text-center">Nhập thông tin để tìm kiếm
                              sinh viên.</td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="button" class="btn btn-primary" id="addSelectedMembersBtn">Thêm
                      vào lớp</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="card-body">
            @include('partials._thanh-vien-lop')
          </div>
        </div>
      </div>

    </div>

    {{-- Modal thêm bản tin --}}
    @if (session('vai_tro') == 'Giảng viên')
      <div class="modal fade" id="newNewsletterModal" tabindex="-1" aria-labelledby="newNewsletterModalLabel"
        aria-hidden="true" data-bs-focus="false">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
          <div class="modal-content">
            <div class="modal-header bg-primary text-white">
              <h5 class="modal-title" id="newNewsletterModalLabel">Tạo bản tin mới</h5>
              <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <form id="newNewsletterForm" action="#" method="POST">
                <div class="mb-3">
                  <label for="newsletterContent" class="form-label">Nội dung thông báo:</label>
                  <textarea class="form-control textarea-tiny" id="newsletterContent" rows="8"
                    placeholder="Nhập nội dung thông báo chi tiết"></textarea>
                </div>
              </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
              <button type="submit" form="newNewsletterForm" class="btn btn-primary">Đăng bản tin</button>
            </div>
          </div>
        </div>
      </div>
    @endif

    {{-- Modal gán bài giảng --}}
    {{-- <div class="modal fade" id="modal-gan-bai-giang" tabindex="-1" aria-labelledby="" aria-hidden="true"
      data-bs-focus="false">
      <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
          <div class="modal-header bg-primary text-white">
            <h5 class="modal-title">Gán bài giảng cho lớp học</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
              aria-label="Close"></button>
          </div>
          <div class="modal-body custom-scrollbar">
            <div class="mb-3">
              <label for="documentSelect" class="form-label">Chọn bài giảng từ mục bài giảng cá nhân:</label>
              <select class="form-select" id="select-muc-bai-giang">
                <option value="">-- Chọn mục bài giảng --</option>
                @foreach ($listMucBaiGiang as $mucBaiGiang)
                  <option value="{{ route('bai-giang.list', $mucBaiGiang->id) }}">
                    {{ $mucBaiGiang->ten }}
                  </option>
                @endforeach
              </select>
            </div>

            <hr class="my-4">

            <h5>Danh sách bài giảng trong mục bài giảng:</h5>
            <div class="alert alert-warning" id="alert-ko-muc-bai-giang" role="alert">
              Vui lòng chọn một mục bài giảng để xem danh sách bài giảng.
            </div>

            <div id="section-list-bai-giang">

              <div class="input-group mb-3">
                <input type="text" class="form-control" placeholder="Tìm kiếm bài giảng theo tiêu đề..."
                  id="input-search-bai-giang">
              </div>

              <div class="table-responsive custom-scrollbar" style="max-height: 400px; overflow-y: auto;">
                <table class="table table-hover table-striped">
                  <thead>
                    <tr>
                      <th scope="col" style="width: 50px;">Chọn</th>
                      <th scope="col">Tiêu đề bài giảng</th>
                      <th scope="col"></th>
                    </tr>
                    </tr>
                  </thead>
                  <tbody id="body-table-list-bai-giang">
                    <tr>
                      <td colspan="4" class="text-center">
                        Chọn tài liệu để hiển thị bài giảng.
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
            <button type="button" class="btn btn-primary" id="selected-lecture-insert-btn">Chèn bài
              giảng đã chọn</button>
          </div>
        </div>
      </div>
    </div> --}}

    {{-- Modal xem chi tiết bài giảng --}}
    {{-- <div class="modal fade" id="modal-chi-tiet-bai-giang" tabindex="-1" aria-labelledby="" aria-hidden="true"
      data-bs-focus="false">
      <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
          <div class="modal-header bg-info text-white">
            <h5 class="modal-title">Chi tiết Bài giảng:</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
              aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <p><strong>Tiêu đề:</strong> <span id="tieu-de-bai-giang"></span></p>
            <hr>
            <h6>Nội dung bài giảng:</h6>
            <div id="noi-dung-bai-giang">
              Không có nội dung chi tiết.
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-toggle="modal"
              data-bs-target="#modal-gan-bai-giang">Quay lại</button>
          </div>
        </div>
      </div>
    </div> --}}

    {{-- Modal chỉnh sửa lớp học --}}
    {{-- <div class="modal fade" id="updateClassModal" tabindex="-1" aria-labelledby="updateClassModalLabel"
      aria-hidden="true" data-bs-focus="false">
      <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
          <div class="modal-header bg-warning text-white">
            <h1 class="modal-title fs-5" id="updateClassModalLabel">Chỉnh sửa lớp học</h1>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
              aria-label="Close"></button>
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
            <button type="button" class="btn btn-primary">Lưu thay đổi</button>
          </div>
        </div>
      </div>
    </div> --}}

  </div>
@endsection

@section('styles')
  <link rel="stylesheet" href="{{ asset('modules/lop-hoc/css/chi-tiet-lop-hoc.css') }}">
  <script src="https://cdn.tiny.cloud/1/49cqngm4aad2mfsqcxldsfyni14qw3mjr893daq7kzrqa40a/tinymce/5/tinymce.min.js"
    referrerpolicy="origin"></script>
@endsection

@section('scripts')
  <script src="{{ asset('js/tiny-mce.js') }}"></script>
  <script src="{{ asset('modules/lop-hoc/js/chi-tiet-lop-hoc.js') }}"></script>
@endsection
