@extends('layouts.app')

@section('content')
  <!-- Main Content -->
  <div class="col bg-light p-4 overflow-auto custom-scrollbar">
    <h2 class="mb-4">Chỉnh sửa bài giảng & Quản lý bài tập</h2>

    <a href="{{ route('muc-bai-giang.detail', $baiGiang->id_muc_bai_giang) }}" class="btn btn-outline-secondary mb-4">
      <i class="fas fa-arrow-alt-circle-left me-2"></i>Danh sách bài giảng
    </a>

    @if (session('message'))
      <div class="alert alert-{{ session('status') }} alert-dismissible fade show" role="alert">
        {{ session('message') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

    <ul class="nav nav-tabs mb-3" id="myTab" role="tablist">
      <li class="nav-item" role="presentation">
        <button class="nav-link active" id="lesson-tab" data-bs-toggle="tab" data-bs-target="#lesson-pane" type="button"
          role="tab" aria-controls="lesson-pane" aria-selected="true">Bài giảng</button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link" id="exercise-tab" data-bs-toggle="tab" data-bs-target="#exercise-pane" type="button"
          role="tab" aria-controls="exercise-pane" aria-selected="false">Bài tập</button>
      </li>
    </ul>

    <div class="tab-content" id="myTabContent">
      <div class="tab-pane fade show active" id="lesson-pane" role="tabpanel" aria-labelledby="lesson-tab" tabindex="0">
        <div class="card shadow-sm">
          <div class="card-header bg-success text-white">
            <h5 class="mb-0">Chỉnh sửa bài giảng</h5>
          </div>
          <div class="card-body">
            <form action="{{ route('bai-giang.update', $baiGiang->id) }}" method="POST">
              @csrf
              @method('PUT')
              <div class="mb-3">
                <label for="lecture-title" class="form-label">Tiêu đề bài giảng <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('tieu_de') is-invalid @enderror" name="tieu_de"
                  value="{{ old('tieu_de', $baiGiang->tieu_de) }}" placeholder="Nhập tên bài giảng" id="lecture-title">
                @error('tieu_de')
                  <div class="invalid-feedback">
                    {{ $message }}
                  </div>
                @enderror
              </div>
              <div class="mb-3">
                <label for="lecture-content" class="form-label">Nội dung bài giảng <span
                    class="text-danger">*</span></label>
                <textarea class="form-control textarea-tiny @error('noi_dung') is-invalid @enderror" name="noi_dung" rows="10"
                  placeholder="Nhập nội dung chi tiết bài giảng" id="lecture-content">
                  {{ old('noi_dung', $baiGiang->noi_dung) }}
                </textarea>
                @error('noi_dung')
                  <div class="invalid-feedback">
                    {{ $message }}
                  </div>
                @enderror
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
                  @forelse ($baiGiang->list_bai_tap as $index => $baiTap)
                    <tr>
                      <td>{{ $index + 1 }}</td>
                      <td>{{ $baiTap->tieu_de }}</td>
                      <td>Trắc nghiệm</td>
                      <td>2023-01-20</td>
                      <td class="text-center">
                        <button class="btn btn-info btn-sm me-1 view-exercise-detail-btn" data-bs-toggle="modal"
                          data-bs-target="#exerciseDetailModal" data-exercise-id="{{ $baiTap->id }}">
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
          <h5 class="modal-title" id="exerciseDetailModalLabel">Chi tiết bài tập: <span id="modalExerciseTitle"></span>
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
                <label for="newExerciseTitle" class="form-label">Tiêu đề bài tập
                  <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="tieu_de" id="newExerciseTitle"
                  placeholder="Nhập tiêu đề bài tập" required>
                <div class="invalid-feedback">
                  Vui lòng nhập tiêu đề bài tập.
                </div>
              </div>
              <div class="col-sm-3 col-lg-2 mt-3 mt-sm-0"> <label for="newExerciseMaxScore" class="form-label">Điểm tối
                  đa</label>
                <input type="number" class="form-control" name="diem_toi_da" id="newExerciseMaxScore"
                  placeholder="100" min="0">
              </div>
            </div>

            <div class="mb-3"> <label for="newExerciseDescription" class="form-label">Mô tả bài tập</label>
              <textarea class="form-control" name="mo_ta" id="newExerciseDescription" rows="3"
                placeholder="Nhập mô tả ngắn gọn về bài tập"></textarea>
            </div>
            <hr>

            <input type="hidden" name="idBaiGiang" id="idBaiGiang" value="{{ $baiGiang->id }}">

            <div id="questionsFormContainer">
              <h6>Danh sách câu hỏi:</h6>
              <div class="question-item mb-4 p-3 border rounded bg-light">
                <h7 class="d-flex justify-content-between align-items-center mb-3">
                  <strong>Câu hỏi 1</strong>
                  <button type="button" class="btn btn-sm btn-outline-danger remove-question-btn">
                    <i class="fas fa-times"></i> Xóa
                  </button>
                </h7>
                <div class="mb-3">
                  <label for="question1Text" class="form-label">Nội dung câu hỏi <span
                      class="text-danger">*</span></label>
                  <textarea class="form-control question-text" id="question1Text" rows="2" placeholder="Nhập nội dung câu hỏi"
                    required></textarea>
                  <div class="invalid-feedback">
                    Vui lòng nhập nội dung câu hỏi.
                  </div>
                </div>
                <div class="row g-2 mb-3">
                  <label class="form-label">Đáp án: <span class="text-danger">*</span></label>
                  <div class="col-md-6">
                    <div class="input-group">
                      <div class="input-group-text">
                        <input class="form-check-input mt-0 correct-answer-radio" type="radio" name="correctAnswer_1"
                          value="optionA" aria-label="Đáp án A" required>
                      </div>
                      <input type="text" class="form-control answer-option" placeholder="Đáp án A" required>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="input-group">
                      <div class="input-group-text">
                        <input class="form-check-input mt-0 correct-answer-radio" type="radio" name="correctAnswer_1"
                          value="optionB" aria-label="Đáp án B" required>
                      </div>
                      <input type="text" class="form-control answer-option" placeholder="Đáp án B" required>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="input-group">
                      <div class="input-group-text">
                        <input class="form-check-input mt-0 correct-answer-radio" type="radio" name="correctAnswer_1"
                          value="optionC" aria-label="Đáp án C" required>
                      </div>
                      <input type="text" class="form-control answer-option" placeholder="Đáp án C" required>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="input-group">
                      <div class="input-group-text">
                        <input class="form-check-input mt-0 correct-answer-radio" type="radio" name="correctAnswer_1"
                          value="optionD" aria-label="Đáp án D" required>
                      </div>
                      <input type="text" class="form-control answer-option" placeholder="Đáp án D" required>
                    </div>
                  </div>
                  <div class="col-12">
                    <div class="invalid-feedback d-block">
                      Vui lòng chọn một đáp án đúng cho câu hỏi này.
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <button type="button" class="btn btn-outline-primary w-100 mb-3" id="addQuestionBtn">
              <i class="fas fa-plus me-2"></i>Thêm câu hỏi mới
            </button>
            <div class="text-center text-muted mb-3" id="noQuestionsMessage" style="display: none;">
              Vui lòng thêm ít nhất một câu hỏi.
            </div>

            <div class="d-flex justify-content-end mt-4">
              <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Hủy</button>
              <button type="submit" class="btn btn-primary">Lưu bài tập</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

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
                <input type="text" class="form-control" id="editExerciseTitle" placeholder="Nhập tiêu đề bài tập"
                  required>
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
  <script src="https://cdn.tiny.cloud/1/49cqngm4aad2mfsqcxldsfyni14qw3mjr893daq7kzrqa40a/tinymce/5/tinymce.min.js"
    referrerpolicy="origin"></script>
@endsection

@section('scripts')
  <script src="{{ asset('modules/bai-tap/js/chinh-sua-bai-tap.js') }}"></script>
@endsection
