@extends('layouts.app')

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
      Chỉnh sửa bài thuộc Chương <span class="fst-italic text-secondary">"{{ $bai->chuong->tieu_de }}"</span>
      - Bài giảng <span class="fst-italic text-secondary">"{{ $bai->chuong->bai_giang->ten }}"</span>
    </h2>

    <a href="{{ route('bai.index', $bai->chuong->id) }}" class="btn btn-outline-secondary mb-4">
      <i class="fas fa-arrow-alt-circle-left me-2"></i>Danh sách bài của chương
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

        <ul class="nav nav-tabs mb-3" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="lesson-tab" data-bs-toggle="tab" data-bs-target="#lesson-pane"
                    type="button" role="tab" aria-controls="lesson-pane" aria-selected="true">Bài</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="exercise-tab" data-bs-toggle="tab" data-bs-target="#exercise-pane"
                    type="button" role="tab" aria-controls="exercise-pane" aria-selected="false">Bài tập của
                    bài</button>
            </li>
        </ul>

    <div class="tab-content" id="myTabContent">
      <div class="tab-pane fade show active" id="lesson-pane" role="tabpanel" aria-labelledby="lesson-tab" tabindex="0">
        <div class="card shadow-sm">
          <div class="card-header bg-success text-white">
            <h5 class="mb-0">Thông tin bài</h5>
          </div>
          <div class="card-body">
            <input type="file" id="upload-docx" accept=".docx" class="d-none">

            <form action="{{ route('bai.update', $bai->id) }}" method="POST" id="form-chinh-sua-bai">
              @csrf
              @method('PUT')
              <div class="mb-3">
                <label for="lecture-title" class="form-label">
                  Tiêu đề bài
                  <span class="text-muted">(100 ký tự)</span>
                  <abbr class="text-danger" title="Bắt buộc">*</abbr>
                </label>
                <input type="text" class="form-control @error('tieu_de') is-invalid @enderror" name="tieu_de"
                  placeholder="Nhập tiêu đề bài..." required maxlength="255" id="lecture-title" value="{{ $bai->tieu_de }}">
                <small class="text-danger" id="tieu-de-error"></small>
              </div>
              <div class="mb-3">
                <label for="lecture-content" class="form-label">Nội dung bài giảng <abbr class="text-danger"
                    title="Bắt buộc">*</abbr></label>
                <textarea class="form-control tinymce" name="noi_dung" rows="10" required placeholder="Nhập nội dung chi tiết bài..."
                  id="lecture-content">
                  {{ $bai->noi_dung }}
                </textarea>
                <small class="text-danger" id="noi-dung-error"></small>
              </div>

              <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
              </div>
            </form>
          </div>
        </div>
      </div>

                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="exercise-pane" role="tabpanel" aria-labelledby="exercise-tab" tabindex="0">
                <div class="card shadow-sm">
                    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Danh sách bài tập</h5>
                        <button class="btn btn-light btn-sm" id="addNewExerciseBtn">
                            <i class="fas fa-plus-circle me-2"></i>Tạo mới bài tập
                        </button>
                    </div>
                    <div class="card-body">

                        <div class="table-responsive">
                            <table class="table table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Tên bài tập</th>
                                        <th scope="col">Loại</th>
                                        <th scope="col">Ngày tạo</th>
                                        <th scope="col" class="text-center">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody id="exerciseListBody">
                                    @forelse ($bai->list_bai_tap as $index => $baiTap)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $baiTap->tieu_de }}</td>
                                            <td>Trắc nghiệm</td>
                                            <td>2023-01-20</td>
                                            <td class="text-center">
                                                <button class="btn btn-info btn-sm me-1 view-exercise-detail-btn"
                                                    data-bs-toggle="modal" data-bs-target="#exerciseDetailModal"
                                                    data-exercise-id="{{ $baiTap->id }}">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button class="btn btn-warning btn-sm me-1 edit-exercise-btn"
                                                    data-exercise-id="{{ $baiTap->id }}">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-danger btn-sm delete-exercise-btn"
                                                    data-exercise-id="{{ $baiTap->id }}">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center">Chưa có bài tập nào</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="exerciseDetailModal" tabindex="-1" aria-labelledby="exerciseDetailModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title" id="exerciseDetailModalLabel">Chi tiết bài tập: <span
                            id="modalExerciseTitle"></span>
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modalExerciseContent">
                    <p><strong>Tên bài tập:</strong> <span id="exerciseDetailTitle"></span></p>
                    <p><strong>Loại bài tập:</strong> <span id="exerciseDetailType"></span></p>
                    <p><strong>Ngày tạo:</strong> <span id="exerciseDetailDate"></span></p>
                    <hr>
                    <h6>Mô tả:</h6>
                    <div id="exerciseDetailContent"></div>

                    <div id="exerciseQuestionsContainer">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal thêm bài tập --}}
    @if (session('vai_tro') == 'Giảng viên')
        <div class="modal fade" id="addExerciseModal" tabindex="-1" aria-labelledby="addExerciseModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title" id="addExerciseModalLabel">Tạo mới bài tập</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="newExerciseForm">
                            <div class="row mb-3">
                                <div class="col-sm-9 col-lg-10">
                                    <label for="newExerciseTitle" class="form-label">Tiêu đề
                                        <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="tieuDe" id="newExerciseTitle"
                                        placeholder="Nhập tiêu đề bài tập">
                                    <div class="invalid-feedback fw-bold">
                                        Vui lòng nhập tiêu đề cho bài tập.
                                    </div>
                                </div>
                                <div class="col-sm-3 col-lg-2 mt-3 mt-sm-0"> <label for="newExerciseMaxScore"
                                        class="form-label">Điểm tối
                                        đa</label>
                                    <input type="number" class="form-control" name="diemToiDa" id="newExerciseMaxScore"
                                        placeholder="100" min="0" max="100">
                                    <div class="invalid-feedback fw-bold">
                                        Vui lòng nhập điểm tối đa
                                    </div>
                                </div>
                            </div>


                            <hr>

                            <input type="hidden" name="idBai" id="idBai" value="{{ $bai->id }}">

                            <div id="questionsFormContainer-them">
                                <h6>Danh sách câu hỏi:</h6>
                                <div class="question-item mb-4 p-3 border rounded bg-light">
                                </div>
                            </div>
                            <div class="d-flex gap-2 mb-3">
                                <!-- Nút Thêm câu hỏi mới -->
                                <button type="button" class="btn btn-outline-primary flex-fill" id="addQuestionBtn">
                                    <i class="fas fa-plus me-2"></i>Thêm câu hỏi mới
                                </button>

                                <!-- Nhãn chọn file (giả dạng nút) -->
                                <label class="btn btn-outline-secondary flex-fill m-0" for="excelFileInput">
                                    <i class="fas fa-file-excel me-2"></i>Chọn file Excel
                                </label>
                                <input type="file" id="excelFileInput" accept=".xlsx, .xls" class="d-none">

                            </div>
                            <div class="text-center text-danger fw-bold mb-3 d-none" id="noQuestionsMessage">
                                Vui lòng thêm ít nhất một câu hỏi.
                            </div>

                            <div class="d-flex justify-content-end mt-4">
                                <button type="button" class="btn btn-secondary me-2"
                                    data-bs-dismiss="modal">Hủy</button>
                                <button type="submit" class="btn btn-primary">Lưu bài kiểm tra</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="modal fade" id="editExerciseModal" tabindex="-1" aria-labelledby="editExerciseModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header bg-warning text-white">
                    <h5 class="modal-title" id="editExerciseModalLabel">Chỉnh sửa bài tập: <span
                            id="currentExerciseTitle"></span></h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editExerciseForm">
                        <input type="hidden" id="editExerciseId">
                        <div class="row mb-3">
                            <div class="col-10">
                                <label for="editExerciseTitle" class="form-label">Tiêu đề bài tập <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="editExerciseTitle"
                                    placeholder="Nhập tiêu đề bài tập" required>
                                <div class="invalid-feedback">
                                    Vui lòng nhập tiêu đề bài tập.
                                </div>
                            </div>
                            <div class="col-2">
                                <label for="editExerciseMaxScore" class="form-label">Điểm tối đa</label>
                                <input type="number" class="form-control" id="editExerciseMaxScore" placeholder="100"
                                    min="0">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="editExerciseDescription" class="form-label">Mô tả bài tập</label>
                            <textarea class="form-control" id="editExerciseDescription" rows="3"
                                placeholder="Nhập mô tả ngắn gọn về bài tập"></textarea>
                        </div>
                        <hr>

                        <div id="editQuestionsFormContainer">
                            <h6>Danh sách câu hỏi:</h6>
                            <div class="text-center text-muted p-3" id="noEditQuestionsMessage" style="display: none;">
                                Không có câu hỏi nào. Nhấn "Thêm câu hỏi mới" để bắt đầu.
                            </div>
                        </div>

                        <button type="button" class="btn btn-outline-primary w-100 mb-3" id="addEditQuestionBtn">
                            <i class="fas fa-plus me-2"></i>Thêm câu hỏi mới
                        </button>


                        <div class="d-flex justify-content-end mt-4">
                            <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Hủy</button>
                            <button type="submit" class="btn btn-warning">Lưu thay đổi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteExerciseConfirmModal" tabindex="-1"
        aria-labelledby="deleteExerciseConfirmModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteExerciseConfirmModalLabel">Xác nhận xóa bài tập</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Bạn có chắc chắn muốn xóa bài tập **"<span id="exerciseToDeleteTitle"></span>"** này không?</p>
                    <p class="text-danger">Hành động này không thể hoàn tác!</p>
                    <input type="hidden" id="exerciseToDeleteId">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteExerciseBtn">Xóa bài tập</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
@endsection

@section('scripts')
  <script src="{{ asset('vendor/tinymce-5/tinymce.min.js') }}"></script>
  <script src="{{ asset('js/mammoth.browser.min.js') }}"></script>

  <script>
    const uploadImageUrl = '{{ route('upload.image') }}';
    const csrfToken = '{{ csrf_token() }}';
  </script>

  <script src="{{ asset('js/config-tinymce-import-word.js') }}"></script>
  <script src="{{ asset('modules/bai-tap/js/chinh-sua-bai-tap.js') }}"></script>
  <script src="{{ asset('modules/bai/js/chinh-sua.js') }}"></script>
@endsection
