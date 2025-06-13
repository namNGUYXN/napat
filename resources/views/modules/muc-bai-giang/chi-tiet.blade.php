@extends('layouts.app')

@section('title', 'Tài liệu - Danh sách bài giảng')

@section('content')
  <form id="csrfForm" class="d-none">
    @csrf
  </form>
  <div class="col bg-light p-4 overflow-auto custom-scrollbar">
    <h2 class="mb-4">Chi tiết tài liệu bài giảng</h2>

    <a href="{{ route('muc-bai-giang.index') }}" class="btn btn-outline-secondary mb-4">
      <i class="fas fa-arrow-alt-circle-left me-2"></i>Danh sách tài liệu
    </a>

    <div class="row">
      <div class="col-lg-4 mb-4">
        <div class="card h-100 shadow-sm">
          <img src="https://picsum.photos/id/66/1000/600" class="card-img-top" alt="">
          <div class="card-body" id="courseDetailContent">
            <h4 id="courseTitle">{{ $mucBaiGiang->ten }}</h4>
            <p><strong>Mô tả:</strong> <span id="courseDescription">{{ $mucBaiGiang->mo_ta_ngan }}</span></p>
            <p><strong>Số lượng bài giảng:</strong> <span id="lessonCount">{{ $mucBaiGiang->so_bai_giang }}</span>
              bài</p>
            <p><strong>Ngày tạo:</strong> <span id="courseCreationDate">{{ $mucBaiGiang->ngay_tao }}</span></p>
            <hr>
            <button class="btn btn-warning btn-sm me-2"><i class="fas fa-edit me-1"></i>Sửa Kho</button>
            <button class="btn btn-danger btn-sm" id="delete-doc-btn"><i class="fas fa-trash-alt me-1"></i>Xóa
              Kho</button>
          </div>
        </div>
      </div>

      <div class="col-lg-8 mb-4">
        @if (session('message'))
          <div class="alert alert-{{ session('status') }} alert-dismissible fade show" role="alert">
            {{ session('message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        @endif
        <div class="card shadow-sm">
          <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Danh sách các bài giảng</h5>
            <a href="{{ route('bai-giang.create', $mucBaiGiang->id) }}" class="btn btn-light btn-sm">
              <i class="fas fa-plus-circle me-2"></i>Tạo mới bài giảng
            </a>
          </div>
          <div class="card-body">
            <div class="input-group mb-3">
              <input type="text" class="form-control" placeholder="Tìm kiếm bài giảng theo tên..."
                id="lessonSearchInput">
              <button class="btn btn-outline-secondary" type="button" id="searchLessonBtn">
                <i class="fas fa-search"></i> </button>
            </div>

            <div class="table-responsive">
              <table class="table table-hover table-striped">
                <thead>
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">Tiêu đề bài giảng</th>
                    <th scope="col">Ngày tạo</th>
                    <th scope="col" class="text-center">Thao tác</th>
                  </tr>
                </thead>
                <tbody id="lessonListBody">
                  @foreach ($mucBaiGiang->baiGiangs as $baiGiang)
                    <tr>
                      <th scope="row">1</th>
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
            <nav aria-label="" class="mt-4">
              <ul class="pagination justify-content-center">
                <li class="page-item">
                  <a class="page-link" href="#" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                  </a>
                </li>
                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">3</a></li>
                <li class="page-item">
                  <a class="page-link" href="#" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                  </a>
                </li>
              </ul>
            </nav>
          </div>
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

  <div class="modal fade" id="editCourseModal" tabindex="-1" aria-labelledby="editCourseModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header bg-warning text-white">
          <h5 class="modal-title" id="editCourseModalLabel"><i class="fas fa-edit me-2"></i>Chỉnh sửa thông tin
            Kho bài giảng</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
            aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="editCourseForm">
            <input type="hidden" id="editCourseId">
            <div class="mb-3">
              <label for="editCourseTitle" class="form-label">Tên Kho bài giảng</label>
              <input type="text" class="form-control" id="editCourseTitle" required>
            </div>
            <div class="mb-3">
              <label for="editCourseDescription" class="form-label">Mô tả</label>
              <textarea class="form-control" id="editCourseDescription" rows="4"></textarea>
            </div>
            <div class="mb-3">
              <label for="editCourseCreationDate" class="form-label">Ngày tạo</label>
              <input type="date" class="form-control" id="editCourseCreationDate" required>
            </div>
            <div class="mb-3">
              <label for="editCourseLessonCount" class="form-label">Số lượng bài giảng</label>
              <input type="number" class="form-control" id="editCourseLessonCount" readonly>
              <div class="form-text">Số lượng bài giảng sẽ được tự động cập nhật.</div>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
          <button type="submit" form="editCourseForm" class="btn btn-primary" id="saveCourseChangesBtn">Lưu
            thay
            đổi</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="deleteCourseConfirmModal" tabindex="-1" aria-labelledby="deleteCourseConfirmModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-danger text-white">
          <h5 class="modal-title" id="deleteCourseConfirmModalLabel">Xác nhận xóa Kho Bài Giảng</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
            aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p>Bạn có chắc chắn muốn xóa Kho Bài Giảng **"<span id="courseToDeleteTitle"></span>"** này không?</p>
          <p class="text-danger">Hành động này sẽ xóa tất cả các bài giảng trong kho và không thể hoàn tác!</p>
          <input type="hidden" id="courseToDeleteId">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
          <button type="button" class="btn btn-danger" id="confirmDeleteCourseBtn">Xóa Kho Bài Giảng</button>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('scripts')
  <script src="{{ asset('modules/baigiang/js/danh-sach-bai-giang.js') }}"></script>
@endsection
