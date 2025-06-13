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
                  <tr>
                    <td>1</td>
                    <td>Bài tập HTML cơ bản</td>
                    <td>Trắc nghiệm</td>
                    <td>2023-01-20</td>
                    <td class="text-center">
                      <button class="btn btn-info btn-sm me-1 view-exercise-detail-btn" data-bs-toggle="modal"
                        data-bs-target="#exerciseDetailModal" data-exercise-id="1">
                        <i class="fas fa-eye"></i>
                      </button>
                      <button class="btn btn-warning btn-sm me-1 edit-exercise-btn" data-exercise-id="1">
                        <i class="fas fa-edit"></i>
                      </button>
                      <button class="btn btn-danger btn-sm delete-exercise-btn" data-exercise-id="1">
                        <i class="fas fa-trash-alt"></i>
                      </button>
                    </td>
                  </tr>
                  <tr>
                    <td>2</td>
                    <td>Bài tập CSS Flexbox</td>
                    <td>Điền khuyết</td>
                    <td>2023-02-25</td>
                    <td class="text-center">
                      <button class="btn btn-info btn-sm me-1 view-exercise-detail-btn" data-bs-toggle="modal"
                        data-bs-target="#exerciseDetailModal" data-exercise-id="2">
                        <i class="fas fa-eye"></i>
                      </button>
                      <button class="btn btn-warning btn-sm me-1 edit-exercise-btn" data-exercise-id="2">
                        <i class="fas fa-edit"></i>
                      </button>
                      <button class="btn btn-danger btn-sm delete-exercise-btn" data-exercise-id="2">
                        <i class="fas fa-trash-alt"></i>
                      </button>
                    </td>
                  </tr>
                  <tr>
                    <td>3</td>
                    <td>Bài tập JavaScript mảng</td>
                    <td>Tự luận</td>
                    <td>2023-03-15</td>
                    <td class="text-center">
                      <button class="btn btn-info btn-sm me-1 view-exercise-detail-btn" data-bs-toggle="modal"
                        data-bs-target="#exerciseDetailModal" data-exercise-id="3">
                        <i class="fas fa-eye"></i>
                      </button>
                      <button class="btn btn-warning btn-sm me-1 edit-exercise-btn" data-exercise-id="3">
                        <i class="fas fa-edit"></i>
                      </button>
                      <button class="btn btn-danger btn-sm delete-exercise-btn" data-exercise-id="3">
                        <i class="fas fa-trash-alt"></i>
                      </button>
                    </td>
                  </tr>
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
          <p><strong>ID Bài tập:</strong> <span id="exerciseDetailId"></span></p>
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
              <div class="col-sm-9 col-lg-10"> <label for="newExerciseTitle" class="form-label">Tiêu đề bài tập
                  <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="newExerciseTitle" placeholder="Nhập tiêu đề bài tập"
                  required>
                <div class="invalid-feedback">
                  Vui lòng nhập tiêu đề bài tập.
                </div>
              </div>
              <div class="col-sm-3 col-lg-2 mt-3 mt-sm-0"> <label for="newExerciseMaxScore" class="form-label">Điểm tối
                  đa</label>
                <input type="number" class="form-control" id="newExerciseMaxScore" placeholder="100" min="0">
              </div>
            </div>

            <div class="mb-3"> <label for="newExerciseDescription" class="form-label">Mô tả bài tập</label>
              <textarea class="form-control" id="newExerciseDescription" rows="3"
                placeholder="Nhập mô tả ngắn gọn về bài tập"></textarea>
            </div>
            <hr>

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
  <script>
    $(document).ready(function() {
      // --- Dữ liệu mẫu cho Bài tập (ĐÃ CẬP NHẬT CẤU TRÚC) ---
      // const exercisesData = [{
      //     id: 1,
      //     title: "Bài tập HTML cơ bản",
      //     type: "Trắc nghiệm",
      //     date: "2023-01-20",
      //     content: "Nội dung chi tiết của <strong>Bài tập HTML cơ bản</strong>. Bài tập này bao gồm các câu hỏi về cấu trúc HTML và các thẻ cơ bản.",
      //     questions: [ // Thêm mảng câu hỏi
      //       {
      //         id: 1,
      //         questionText: "Thẻ HTML nào dùng để tạo tiêu đề lớn nhất?",
      //         options: ["&lt;h6&gt;", "&lt;head&gt;", "&lt;h1&gt;", "&lt;title&gt;"],
      //         correctAnswer: "&lt;h1&gt;"
      //       },
      //       {
      //         id: 2,
      //         questionText: "Thẻ nào dùng để tạo một danh sách không có thứ tự?",
      //         options: ["&lt;ol&gt;", "&lt;ul&gt;", "&lt;li&gt;", "&lt;dl&gt;"],
      //         correctAnswer: "&lt;ul&gt;"
      //       },
      //       {
      //         id: 3,
      //         questionText: "Thuộc tính HTML nào dùng để định nghĩa CSS nội dòng (inline CSS)?",
      //         options: ["class", "id", "style", "font"],
      //         correctAnswer: "style"
      //       }
      //     ]
      //   },
      //   {
      //     id: 2,
      //     title: "Bài tập CSS Flexbox",
      //     type: "Điền khuyết",
      //     date: "2023-02-25",
      //     content: "Nội dung chi tiết của <em>Bài tập CSS Flexbox</em>. Hoàn thành các đoạn mã CSS còn thiếu để tạo bố cục sử dụng Flexbox.",
      //     questions: [] // Không có câu hỏi trắc nghiệm cho loại này
      //   },
      //   {
      //     id: 3,
      //     title: "Bài tập JavaScript mảng",
      //     type: "Tự luận",
      //     date: "2023-03-15",
      //     content: "Nội dung chi tiết của <strong>Bài tập JavaScript mảng</strong>. Viết một hàm JavaScript để thực hiện các thao tác trên mảng như thêm, xóa, tìm kiếm phần tử.",
      //     questions: [] // Không có câu hỏi trắc nghiệm cho loại này
      //   }
      // ];

      // // --- Hàm tải danh sách Bài tập (giữ nguyên) ---
      // function loadExerciseList(exercises) {
      //   const $exerciseListBody = $('#exerciseListBody');
      //   $exerciseListBody.empty();

      //   if (exercises.length === 0) {
      //     $exerciseListBody.append('<tr><td colspan="5" class="text-center">Không có bài tập nào.</td></tr>');
      //     return;
      //   }

      //   exercises.forEach((exercise, index) => {
      //     const row = `
      //               <tr>
      //                   <td>${index + 1}</td>
      //                   <td>${exercise.title}</td>
      //                   <td>${exercise.type}</td>
      //                   <td>${exercise.date}</td>
      //                   <td class="text-center">
      //                       <button class="btn btn-info btn-sm me-1 view-exercise-detail-btn" data-bs-toggle="modal" data-bs-target="#exerciseDetailModal" data-exercise-id="${exercise.id}">
      //                           <i class="fas fa-eye"></i>
      //                       </button>
      //                       <button class="btn btn-warning btn-sm me-1 edit-exercise-btn" data-exercise-id="${exercise.id}">
      //                           <i class="fas fa-edit"></i>
      //                       </button>
      //                       <button class="btn btn-danger btn-sm delete-exercise-btn" data-exercise-id="${exercise.id}">
      //                           <i class="fas fa-trash-alt"></i>
      //                       </button>
      //                   </td>
      //               </tr>
      //           `;
      //     $exerciseListBody.append(row);
      //   });
      // }

      // // --- Khởi tạo dữ liệu khi trang tải xong ---
      // loadExerciseList(exercisesData);

      // // --- Xử lý sự kiện cho Tab Bài giảng (Form Thêm mới Bài giảng - giữ nguyên) ---
      // $('#addLessonForm').on('submit', function(e) {
      //   e.preventDefault();
      //   if (this.checkValidity()) {
      //     const newLesson = {
      //       title: $('#lessonTitle').val(),
      //       author: $('#lessonAuthor').val(),
      //       date: $('#lessonDate').val(),
      //       content: $('#lessonContent').val()
      //     };
      //     console.log('Thông tin bài giảng mới:', newLesson);
      //     alert('Bài giảng "' + newLesson.title + '" đã được thêm thành công (demo)!');
      //     $('#addLessonForm')[0].reset();
      //     $(this).removeClass('was-validated');
      //   } else {
      //     $(this).addClass('was-validated');
      //   }
      // });

      // $('#addLessonForm').on('reset', function() {
      //   $(this).removeClass('was-validated');
      // });

      // // --- Xử lý sự kiện cho Tab Bài tập ---

      // // --- ĐIỀU CHỈNH: Xử lý khi nhấn nút "Xem chi tiết" (icon mắt) của BÀI TẬP ---
      // $(document).on('click', '.view-exercise-detail-btn', function() {
      //   const exerciseId = $(this).data('exercise-id');
      //   const exercise = exercisesData.find(ex => ex.id === exerciseId);

      //   if (exercise) {
      //     $('#modalExerciseTitle').text(exercise.title);
      //     $('#exerciseDetailId').text(exercise.id);
      //     $('#exerciseDetailTitle').text(exercise.title);
      //     $('#exerciseDetailType').text(exercise.type);
      //     $('#exerciseDetailDate').text(exercise.date);
      //     $('#exerciseDetailContent').html(exercise.content);

      //     const $questionsContainer = $('#exerciseQuestionsContainer');
      //     $questionsContainer.empty(); // Xóa nội dung câu hỏi cũ

      //     if (exercise.type === "Trắc nghiệm" && exercise.questions && exercise.questions.length > 0) {
      //       $questionsContainer.append('<h6 class="mt-3">Câu hỏi trắc nghiệm:</h6>');
      //       exercise.questions.forEach((q, qIndex) => {
      //         const questionHtml = `
      //                       <div class="mb-3 border p-3 rounded bg-light">
      //                           <p><strong>Câu ${qIndex + 1}: ${q.questionText}</strong></p>
      //                           <ul class="list-unstyled">
      //                   `;
      //         let optionsHtml = '';
      //         q.options.forEach((opt, optIndex) => {
      //           optionsHtml += `<li>${String.fromCharCode(65 + optIndex)}. ${opt}</li>`; // A, B, C...
      //         });
      //         const correctAnswerHtml = `
      //                           </ul>
      //                           <p class="text-success fw-bold">Đáp án đúng: ${q.correctAnswer}</p>
      //                       </div>
      //                   `;
      //         $questionsContainer.append(questionHtml + optionsHtml + correctAnswerHtml);
      //       });
      //     } else {
      //       $questionsContainer.append(
      //         '<p class="text-muted mt-3">Không có câu hỏi trắc nghiệm cho bài tập này.</p>');
      //     }
      //   }
      // });

      // // Xử lý khi nhấn nút "Xóa" (icon thùng rác) của BÀI TẬP (giữ nguyên)
      // // $(document).on('click', '.delete-exercise-btn', function () {
      // //   const exerciseId = $(this).data('exercise-id');
      // //   if (confirm('Bạn có chắc chắn muốn xóa bài tập này (ID: ' + exerciseId + ')?')) {
      // //     alert('Đã xóa bài tập có ID: ' + exerciseId + '.');
      // //   }
      // // });




      // let questionCounter = 1; // Biến đếm số câu hỏi

      // // Mẫu HTML cho một câu hỏi mới
      // const questionTemplate = (count) => `
      //       <div class="question-item mb-4 p-3 border rounded bg-light" data-question-index="${count}">
      //           <h7 class="d-flex justify-content-between align-items-center mb-3">
      //               <strong>Câu hỏi ${count}</strong>
      //               <button type="button" class="btn btn-sm btn-outline-danger remove-question-btn">
      //                   <i class="fas fa-times"></i> Xóa
      //               </button>
      //           </h7>
      //           <div class="mb-3">
      //               <label for="question${count}Text" class="form-label">Nội dung câu hỏi <span class="text-danger">*</span></label>
      //               <textarea class="form-control question-text" id="question${count}Text" rows="2" placeholder="Nhập nội dung câu hỏi" required></textarea>
      //               <div class="invalid-feedback">
      //                   Vui lòng nhập nội dung câu hỏi.
      //               </div>
      //           </div>
      //           <div class="row g-2 mb-3">
      //               <label class="form-label">Đáp án: <span class="text-danger">*</span></label>
      //               <div class="col-md-6">
      //                   <div class="input-group">
      //                       <div class="input-group-text">
      //                           <input class="form-check-input mt-0 correct-answer-radio" type="radio" name="correctAnswer_${count}" value="optionA" aria-label="Đáp án A" required>
      //                       </div>
      //                       <input type="text" class="form-control answer-option" placeholder="Đáp án A" required>
      //                   </div>
      //               </div>
      //               <div class="col-md-6">
      //                   <div class="input-group">
      //                       <div class="input-group-text">
      //                           <input class="form-check-input mt-0 correct-answer-radio" type="radio" name="correctAnswer_${count}" value="optionB" aria-label="Đáp án B" required>
      //                       </div>
      //                       <input type="text" class="form-control answer-option" placeholder="Đáp án B" required>
      //                   </div>
      //               </div>
      //               <div class="col-md-6">
      //                   <div class="input-group">
      //                       <div class="input-group-text">
      //                           <input class="form-check-input mt-0 correct-answer-radio" type="radio" name="correctAnswer_${count}" value="optionC" aria-label="Đáp án C" required>
      //                       </div>
      //                       <input type="text" class="form-control answer-option" placeholder="Đáp án C" required>
      //                   </div>
      //               </div>
      //               <div class="col-md-6">
      //                   <div class="input-group">
      //                       <div class="input-group-text">
      //                           <input class="form-check-input mt-0 correct-answer-radio" type="radio" name="correctAnswer_${count}" value="optionD" aria-label="Đáp án D" required>
      //                       </div>
      //                       <input type="text" class="form-control answer-option" placeholder="Đáp án D" required>
      //                   </div>
      //               </div>
      //                <div class="col-12">
      //                   <div class="invalid-feedback d-block">
      //                       Vui lòng chọn một đáp án đúng cho câu hỏi này.
      //                   </div>
      //               </div>
      //           </div>
      //       </div>
      //   `;

      // // Cập nhật số thứ tự câu hỏi và trạng thái nút xóa
      // function updateQuestionNumbers() {
      //   const $questionItems = $('#questionsFormContainer .question-item');
      //   if ($questionItems.length === 0) {
      //     $('#noQuestionsMessage').show();
      //   } else {
      //     $('#noQuestionsMessage').hide();
      //   }

      //   $questionItems.each(function(index) {
      //     const $this = $(this);
      //     $this.find('h7 strong').text(`Câu hỏi ${index + 1}`);
      //     // Cập nhật thuộc tính name của radio button để đảm bảo chúng hoạt động độc lập
      //     $this.find('.correct-answer-radio').attr('name', `correctAnswer_${index + 1}`);

      //     // Ẩn/hiện nút xóa nếu chỉ còn 1 câu hỏi
      //     if ($questionItems.length === 1) {
      //       $this.find('.remove-question-btn').hide();
      //     } else {
      //       $this.find('.remove-question-btn').show();
      //     }
      //   });
      // }

      // // --- Sự kiện khi modal thêm bài tập hiện lên ---
      // $('#addExerciseModal').on('show.bs.modal', function() {
      //   // Đặt lại form khi modal mở
      //   $('#newExerciseForm')[0].reset();
      //   $('#newExerciseForm').removeClass('was-validated');
      //   $('#questionsFormContainer').empty(); // Xóa tất cả câu hỏi cũ
      //   questionCounter = 0; // Đặt lại bộ đếm
      //   $('#noQuestionsMessage').show(); // Hiển thị thông báo khi không có câu hỏi
      // });


      // // --- Thêm câu hỏi mới ---
      // $('#addQuestionBtn').on('click', function() {
      //   questionCounter++;
      //   $('#questionsFormContainer').append(questionTemplate(questionCounter));
      //   updateQuestionNumbers();
      // });

      // // --- Xóa câu hỏi ---
      // // Sử dụng event delegation vì các nút xóa được thêm động
      // $(document).on('click', '.remove-question-btn', function() {
      //   const $questionItemToRemove = $(this).closest('.question-item');
      //   if ($('#questionsFormContainer .question-item').length > 1) { // Chỉ xóa nếu có hơn 1 câu hỏi
      //     $questionItemToRemove.remove();
      //     updateQuestionNumbers();
      //   } else {
      //     alert('Bạn phải có ít nhất một câu hỏi.');
      //   }
      // });

      // // --- Xử lý submit form thêm bài tập mới ---
      // $('#newExerciseForm').on('submit', function(e) {
      //   e.preventDefault();
      //   // Kiểm tra validate của Bootstrap
      //   if (!this.checkValidity()) {
      //     e.stopPropagation();
      //     $(this).addClass('was-validated');
      //     return;
      //   }

      //   // Kiểm tra xem có ít nhất một câu hỏi không
      //   if ($('#questionsFormContainer .question-item').length === 0) {
      //     $('#noQuestionsMessage').show();
      //     alert('Vui lòng thêm ít nhất một câu hỏi cho bài tập.');
      //     return;
      //   } else {
      //     $('#noQuestionsMessage').hide();
      //   }

      //   // Thu thập dữ liệu bài tập
      //   const newExercise = {
      //     title: $('#newExerciseTitle').val(),
      //     maxScore: $('#newExerciseMaxScore').val() ? parseInt($('#newExerciseMaxScore').val()) : null,
      //     description: $('#newExerciseDescription').val(), // THÊM DÒNG NÀY
      //     type: "Trắc nghiệm", // Mặc định là trắc nghiệm vì có form câu hỏi
      //     date: new Date().toISOString().slice(0, 10), // Ngày hiện tại
      //     questions: []
      //   };

      //   // Thu thập dữ liệu câu hỏi
      //   $('#questionsFormContainer .question-item').each(function(index) {
      //     const $thisQuestion = $(this);
      //     const questionText = $thisQuestion.find('.question-text').val();
      //     const options = [];
      //     let correctAnswer = '';

      //     $thisQuestion.find('.answer-option').each(function(optIndex) {
      //       const optionText = $(this).val();
      //       options.push(optionText);

      //       // Kiểm tra radio button nào được chọn
      //       if ($thisQuestion.find(
      //           `.correct-answer-radio[value="option${String.fromCharCode(65 + optIndex)}"]`).is(
      //           ':checked')) {
      //         correctAnswer = optionText;
      //       }
      //     });

      //     // Kiểm tra nếu nội dung câu hỏi hoặc đáp án rỗng
      //     if (!questionText.trim()) {
      //       alert(`Vui lòng nhập nội dung cho Câu hỏi ${index + 1}.`);
      //       return false; // Dừng vòng lặp và không submit
      //     }

      //     if (options.some(opt => !opt.trim())) {
      //       alert(`Vui lòng nhập đầy đủ 4 đáp án cho Câu hỏi ${index + 1}.`);
      //       return false;
      //     }

      //     if (!correctAnswer) {
      //       alert(`Vui lòng chọn đáp án đúng cho Câu hỏi ${index + 1}.`);
      //       return false;
      //     }


      //     newExercise.questions.push({
      //       id: index + 1, // ID tạm thời
      //       questionText: questionText,
      //       options: options,
      //       correctAnswer: correctAnswer
      //     });
      //   });

      //   // Nếu có lỗi trong quá trình thu thập câu hỏi, không submit form
      //   if (newExercise.questions.length !== $('#questionsFormContainer .question-item').length) {
      //     return;
      //   }

      //   console.log('Bài tập mới:', newExercise);
      //   alert('Bài tập "' + newExercise.title + '" đã được tạo thành công (demo)! Xem console để thấy dữ liệu.');

      //   // Đóng modal và reset form
      //   $('#addExerciseModal').modal('hide');
      //   $('#newExerciseForm')[0].reset();
      //   $('#newExerciseForm').removeClass('was-validated');
      //   $('#questionsFormContainer').empty(); // Xóa tất cả câu hỏi đã tạo
      //   questionCounter = 0; // Đặt lại bộ đếm
      //   $('#noQuestionsMessage').show();

      //   // Trong ứng dụng thực tế, bạn sẽ gửi `newExercise` này đến server
      //   // Sau khi thành công, bạn có thể thêm bài tập mới vào danh sách hiển thị
      //   // hoặc tải lại danh sách bài tập.
      // });

      // // Xử lý khi tab "Bài tập" được chọn để tạo một bài tập mới (nếu cần)
      // // Lưu ý: Nếu nút "Tạo mới bài tập" đã có sẵn trong tab, bạn không cần hàm này.
      // // Đây là ví dụ nếu bạn muốn modal tự động hiển thị khi chuyển tab.
      // $('#exercise-tab').on('shown.bs.tab', function(e) {
      //   // Có thể mở modal tự động ở đây nếu bạn muốn, ví dụ:
      //   // $('#addExerciseModal').modal('show');
      //   // Hoặc giữ nguyên logic click vào nút "Tạo mới bài tập" như bạn đã có.
      //   // Về cơ bản, đoạn mã JS này sẽ giúp nút "Tạo mới bài tập" trong tab Exercise gọi modal
      //   // mà không cần sửa đổi thêm, miễn là data-bs-target của nút trỏ đúng đến #addExerciseModal.
      // });

      // // Kích hoạt nút "Tạo mới bài tập" để mở modal này
      // $('#addNewExerciseBtn').attr('data-bs-toggle', 'modal');
      // $('#addNewExerciseBtn').attr('data-bs-target', '#addExerciseModal');

      // // Khởi tạo trạng thái ban đầu cho nút xóa (khi modal mở)
      // // Nếu bạn muốn modal luôn có sẵn 1 câu hỏi khi mở:
      // // questionCounter = 1;
      // // $('#questionsFormContainer').append(questionTemplate(questionCounter));
      // updateQuestionNumbers(); // Gọi lần đầu để xử lý trạng thái ban đầu








      // // // --- Dữ liệu giả định cho bài tập (thay thế bằng dữ liệu từ API của bạn) ---
      // const exercises = [{
      //     id: 1,
      //     title: "Bài tập HTML cơ bản",
      //     type: "Trắc nghiệm",
      //     date: "2023-01-20",
      //     maxScore: 100,
      //     description: "Tổng hợp các kiến thức cơ bản về HTML.",
      //     questions: [{
      //         id: 1,
      //         questionText: "Thẻ nào được dùng để tạo tiêu đề lớn nhất trong HTML?",
      //         options: ["<h1>", "<h2>", "<p>", "<div>"],
      //         correctAnswer: "<h1>"
      //       },
      //       {
      //         id: 2,
      //         questionText: "Phần tử HTML nào dùng để tạo một đoạn văn bản?",
      //         options: ["<a>", "<p>", "<span>", "<div>"],
      //         correctAnswer: "<p>"
      //       }
      //     ]
      //   },
      //   {
      //     id: 2,
      //     title: "Bài tập CSS Flexbox",
      //     type: "Điền khuyết", // Loại bài tập này sẽ không có câu hỏi trắc nghiệm trong modal này
      //     date: "2023-02-25",
      //     maxScore: 50,
      //     description: "Kiểm tra hiểu biết về Flexbox trong CSS.",
      //     questions: [] // Không có câu hỏi trắc nghiệm
      //   },
      //   {
      //     id: 3,
      //     title: "Bài tập JavaScript mảng",
      //     type: "Tự luận",
      //     date: "2023-03-15",
      //     maxScore: 80,
      //     description: "Các câu hỏi tự luận về thao tác với mảng trong JavaScript.",
      //     questions: [] // Không có câu hỏi trắc nghiệm
      //   }
      // ];

      // // Hàm để tạo HTML cho một câu hỏi trong modal chỉnh sửa
      // const editQuestionTemplate = (question, index, totalQuestions) => `
      //       <div class="question-item mb-4 p-3 border rounded bg-light" data-question-index="${index}">
      //           <h7 class="d-flex justify-content-between align-items-center mb-3">
      //               <strong>Câu hỏi ${index + 1}</strong>
      //               <button type="button" class="btn btn-sm btn-outline-danger remove-edit-question-btn" ${totalQuestions <= 1 ? 'style="display: none;"' : ''}>
      //                   <i class="fas fa-times"></i> Xóa
      //               </button>
      //           </h7>
      //           <div class="mb-3">
      //               <label for="editQuestion${index}Text" class="form-label">Nội dung câu hỏi <span class="text-danger">*</span></label>
      //               <textarea class="form-control question-text" id="editQuestion${index}Text" rows="2" placeholder="Nhập nội dung câu hỏi" required>${question.questionText}</textarea>
      //               <div class="invalid-feedback">
      //                   Vui lòng nhập nội dung câu hỏi.
      //               </div>
      //           </div>
      //           <div class="row g-2 mb-3">
      //               <label class="form-label">Đáp án: <span class="text-danger">*</span></label>
      //               <div class="col-md-6">
      //                   <div class="input-group">
      //                       <div class="input-group-text">
      //                           <input class="form-check-input mt-0 correct-answer-radio" type="radio" name="editCorrectAnswer_${index}" value="optionA" aria-label="Đáp án A" ${question.correctAnswer === question.options[0] ? 'checked' : ''} required>
      //                       </div>
      //                       <input type="text" class="form-control answer-option" placeholder="Đáp án A" value="${question.options[0] || ''}" required>
      //                   </div>
      //               </div>
      //               <div class="col-md-6">
      //                   <div class="input-group">
      //                       <div class="input-group-text">
      //                           <input class="form-check-input mt-0 correct-answer-radio" type="radio" name="editCorrectAnswer_${index}" value="optionB" aria-label="Đáp án B" ${question.correctAnswer === question.options[1] ? 'checked' : ''} required>
      //                       </div>
      //                       <input type="text" class="form-control answer-option" placeholder="Đáp án B" value="${question.options[1] || ''}" required>
      //                   </div>
      //               </div>
      //               <div class="col-md-6">
      //                   <div class="input-group">
      //                       <div class="input-group-text">
      //                           <input class="form-check-input mt-0 correct-answer-radio" type="radio" name="editCorrectAnswer_${index}" value="optionC" aria-label="Đáp án C" ${question.correctAnswer === question.options[2] ? 'checked' : ''} required>
      //                       </div>
      //                       <input type="text" class="form-control answer-option" placeholder="Đáp án C" value="${question.options[2] || ''}" required>
      //                   </div>
      //               </div>
      //               <div class="col-md-6">
      //                   <div class="input-group">
      //                       <div class="input-group-text">
      //                           <input class="form-check-input mt-0 correct-answer-radio" type="radio" name="editCorrectAnswer_${index}" value="optionD" aria-label="Đáp án D" ${question.correctAnswer === question.options[3] ? 'checked' : ''} required>
      //                       </div>
      //                       <input type="text" class="form-control answer-option" placeholder="Đáp án D" value="${question.options[3] || ''}" required>
      //                   </div>
      //               </div>
      //                <div class="col-12">
      //                   <div class="invalid-feedback d-block">
      //                       Vui lòng chọn một đáp án đúng cho câu hỏi này.
      //                   </div>
      //               </div>
      //           </div>
      //       </div>
      //   `;

      // // Hàm để tạo HTML cho một câu hỏi TRỐNG mới (dùng khi thêm mới trong modal chỉnh sửa)
      // const newEmptyQuestionTemplate = (count) => `
      //       <div class="question-item mb-4 p-3 border rounded bg-light" data-question-index="${count}">
      //           <h7 class="d-flex justify-content-between align-items-center mb-3">
      //               <strong>Câu hỏi ${count + 1}</strong>
      //               <button type="button" class="btn btn-sm btn-outline-danger remove-edit-question-btn">
      //                   <i class="fas fa-times"></i> Xóa
      //               </button>
      //           </h7>
      //           <div class="mb-3">
      //               <label for="editQuestion${count}Text" class="form-label">Nội dung câu hỏi <span class="text-danger">*</span></label>
      //               <textarea class="form-control question-text" id="editQuestion${count}Text" rows="2" placeholder="Nhập nội dung câu hỏi" required></textarea>
      //               <div class="invalid-feedback">
      //                   Vui lòng nhập nội dung câu hỏi.
      //               </div>
      //           </div>
      //           <div class="row g-2 mb-3">
      //               <label class="form-label">Đáp án: <span class="text-danger">*</span></label>
      //               <div class="col-md-6">
      //                   <div class="input-group">
      //                       <div class="input-group-text">
      //                           <input class="form-check-input mt-0 correct-answer-radio" type="radio" name="editCorrectAnswer_${count}" value="optionA" aria-label="Đáp án A" required>
      //                       </div>
      //                       <input type="text" class="form-control answer-option" placeholder="Đáp án A" required>
      //                   </div>
      //               </div>
      //               <div class="col-md-6">
      //                   <div class="input-group">
      //                       <div class="input-group-text">
      //                           <input class="form-check-input mt-0 correct-answer-radio" type="radio" name="editCorrectAnswer_${count}" value="optionB" aria-label="Đáp án B" required>
      //                       </div>
      //                       <input type="text" class="form-control answer-option" placeholder="Đáp án B" required>
      //                   </div>
      //               </div>
      //               <div class="col-md-6">
      //                   <div class="input-group">
      //                       <div class="input-group-text">
      //                           <input class="form-check-input mt-0 correct-answer-radio" type="radio" name="editCorrectAnswer_${count}" value="optionC" aria-label="Đáp án C" required>
      //                       </div>
      //                       <input type="text" class="form-control answer-option" placeholder="Đáp án C" required>
      //                   </div>
      //               </div>
      //               <div class="col-md-6">
      //                   <div class="input-group">
      //                       <div class="input-group-text">
      //                           <input class="form-check-input mt-0 correct-answer-radio" type="radio" name="editCorrectAnswer_${count}" value="optionD" aria-label="Đáp án D" required>
      //                       </div>
      //                       <input type="text" class="form-control answer-option" placeholder="Đáp án D" required>
      //                   </div>
      //               </div>
      //                <div class="col-12">
      //                   <div class="invalid-feedback d-block">
      //                       Vui lòng chọn một đáp án đúng cho câu hỏi này.
      //                   </div>
      //               </div>
      //           </div>
      //       </div>
      //   `;


      // // Cập nhật số thứ tự câu hỏi và trạng thái nút xóa trong modal chỉnh sửa
      // function updateEditQuestionNumbers() {
      //   const $questionItems = $('#editQuestionsFormContainer .question-item');
      //   if ($questionItems.length === 0) {
      //     $('#noEditQuestionsMessage').show();
      //   } else {
      //     $('#noEditQuestionsMessage').hide();
      //   }

      //   $questionItems.each(function(index) {
      //     const $this = $(this);
      //     $this.find('h7 strong').text(`Câu hỏi ${index + 1}`);
      //     // Cập nhật thuộc tính name của radio button để đảm bảo chúng hoạt động độc lập
      //     $this.find('.correct-answer-radio').attr('name', `editCorrectAnswer_${index}`);

      //     // Ẩn/hiện nút xóa nếu chỉ còn 1 câu hỏi
      //     if ($questionItems.length === 1) {
      //       $this.find('.remove-edit-question-btn').hide();
      //     } else {
      //       $this.find('.remove-edit-question-btn').show();
      //     }
      //   });
      // }

      // // --- Xử lý khi nút "Chỉnh sửa" được nhấn ---
      // $(document).on('click', '.edit-exercise-btn', function() {
      //   const exerciseId = $(this).data('exercise-id');
      //   const exerciseToEdit = exercises.find(ex => ex.id === exerciseId);

      //   if (exerciseToEdit) {
      //     // Đổ dữ liệu bài tập vào modal
      //     $('#editExerciseId').val(exerciseToEdit.id);
      //     $('#currentExerciseTitle').text(exerciseToEdit.title); // Hiển thị tiêu đề trên header modal
      //     $('#editExerciseTitle').val(exerciseToEdit.title);
      //     $('#editExerciseMaxScore').val(exerciseToEdit.maxScore);
      //     $('#editExerciseDescription').val(exerciseToEdit.description);

      //     // Xóa các câu hỏi cũ và tải lại
      //     $('#editQuestionsFormContainer').empty();
      //     if (exerciseToEdit.questions && exerciseToEdit.questions.length > 0) {
      //       exerciseToEdit.questions.forEach((question, index) => {
      //         $('#editQuestionsFormContainer').append(editQuestionTemplate(question, index, exerciseToEdit
      //           .questions.length));
      //       });
      //       updateEditQuestionNumbers(); // Cập nhật lại số thứ tự và nút xóa
      //     } else {
      //       // Nếu không có câu hỏi, hiển thị thông báo và có thể thêm 1 câu hỏi trống
      //       $('#noEditQuestionsMessage').show();
      //     }

      //     // Hiển thị modal chỉnh sửa
      //     $('#editExerciseModal').modal('show');
      //     $('#editExerciseForm').removeClass('was-validated'); // Xóa trạng thái validate cũ
      //   } else {
      //     alert('Không tìm thấy bài tập để chỉnh sửa.');
      //   }
      // });

      // // --- Thêm câu hỏi mới trong modal chỉnh sửa ---
      // $('#addEditQuestionBtn').on('click', function() {
      //   let currentQuestionCount = $('#editQuestionsFormContainer .question-item').length;
      //   $('#editQuestionsFormContainer').append(newEmptyQuestionTemplate(currentQuestionCount));
      //   updateEditQuestionNumbers();
      // });

      // // --- Xóa câu hỏi trong modal chỉnh sửa (sử dụng event delegation) ---
      // $(document).on('click', '.remove-edit-question-btn', function() {
      //   const $questionItemToRemove = $(this).closest('.question-item');
      //   if ($('#editQuestionsFormContainer .question-item').length > 1) {
      //     $questionItemToRemove.remove();
      //     updateEditQuestionNumbers();
      //   } else {
      //     alert('Bạn phải có ít nhất một câu hỏi trong bài tập trắc nghiệm.');
      //   }
      // });


      // // --- Xử lý submit form chỉnh sửa bài tập ---
      // $('#editExerciseForm').on('submit', function(e) {
      //   e.preventDefault();

      //   // Kiểm tra validate của Bootstrap
      //   if (!this.checkValidity()) {
      //     e.stopPropagation();
      //     $(this).addClass('was-validated');
      //     return;
      //   }

      //   // Kiểm tra xem có ít nhất một câu hỏi không nếu loại là trắc nghiệm
      //   // Trong ví dụ này, chúng ta chỉ cho phép chỉnh sửa câu hỏi cho loại "Trắc nghiệm"
      //   const currentQuestionsCount = $('#editQuestionsFormContainer .question-item').length;
      //   if (currentQuestionsCount === 0) {
      //     $('#noEditQuestionsMessage').show();
      //     alert('Vui lòng thêm ít nhất một câu hỏi cho bài tập trắc nghiệm.');
      //     return;
      //   } else {
      //     $('#noEditQuestionsMessage').hide();
      //   }

      //   const editedExercise = {
      //     id: parseInt($('#editExerciseId').val()),
      //     title: $('#editExerciseTitle').val(),
      //     maxScore: $('#editExerciseMaxScore').val() ? parseInt($('#editExerciseMaxScore').val()) : null,
      //     description: $('#editExerciseDescription').val(),
      //     type: "Trắc nghiệm", // Giả định luôn là trắc nghiệm khi có câu hỏi
      //     date: exercises.find(ex => ex.id === parseInt($('#editExerciseId').val()))
      //     .date, // Giữ nguyên ngày tạo
      //     questions: []
      //   };

      //   // Thu thập dữ liệu câu hỏi đã chỉnh sửa
      //   let hasErrorInQuestions = false;
      //   $('#editQuestionsFormContainer .question-item').each(function(index) {
      //     const $thisQuestion = $(this);
      //     const questionText = $thisQuestion.find('.question-text').val();
      //     const options = [];
      //     let correctAnswer = '';

      //     $thisQuestion.find('.answer-option').each(function(optIndex) {
      //       const optionText = $(this).val();
      //       options.push(optionText);

      //       if ($thisQuestion.find(
      //           `.correct-answer-radio[value="option${String.fromCharCode(65 + optIndex)}"]`).is(
      //           ':checked')) {
      //         correctAnswer = optionText;
      //       }
      //     });

      //     if (!questionText.trim()) {
      //       alert(`Vui lòng nhập nội dung cho Câu hỏi ${index + 1}.`);
      //       hasErrorInQuestions = true;
      //       return false; // Dừng vòng lặp each
      //     }

      //     if (options.some(opt => !opt.trim())) {
      //       alert(`Vui lòng nhập đầy đủ 4 đáp án cho Câu hỏi ${index + 1}.`);
      //       hasErrorInQuestions = true;
      //       return false;
      //     }

      //     if (!correctAnswer) {
      //       alert(`Vui lòng chọn đáp án đúng cho Câu hỏi ${index + 1}.`);
      //       hasErrorInQuestions = true;
      //       return false;
      //     }

      //     editedExercise.questions.push({
      //       id: index + 1, // Gán lại ID tạm thời hoặc giữ ID gốc nếu có
      //       questionText: questionText,
      //       options: options,
      //       correctAnswer: correctAnswer
      //     });
      //   });

      //   if (hasErrorInQuestions) {
      //     return; // Dừng submit nếu có lỗi trong câu hỏi
      //   }

      //   console.log('Bài tập đã chỉnh sửa:', editedExercise);
      //   alert('Bài tập "' + editedExercise.title +
      //     '" đã được lưu thay đổi thành công (demo)! Xem console để thấy dữ liệu.');

      //   // Cập nhật dữ liệu trong mảng exercises (demo)
      //   const index = exercises.findIndex(ex => ex.id === editedExercise.id);
      //   if (index !== -1) {
      //     exercises[index] = editedExercise;
      //     // Có thể cập nhật lại hiển thị bảng tại đây
      //     // Ví dụ: refreshExerciseListTable();
      //   }

      //   // Đóng modal
      //   $('#editExerciseModal').modal('hide');
      // });

      // --- Cập nhật nút "Chỉnh sửa" để mở modal này ---
      // Đảm bảo nút "Chỉnh sửa" trong bảng của bạn đã có class 'edit-exercise-btn' và data-exercise-id
      // (Điều này đã có trong mã HTML của bạn, nên không cần thay đổi thêm)

      // Khởi tạo và hiển thị danh sách bài tập (ví dụ)
      // function renderExerciseList() {
      //   $('#exerciseListBody').empty();
      //   exercises.forEach(ex => {
      //     const row = `
    //               <tr>
    //                   <td>${ex.id}</td>
    //                   <td>${ex.title}</td>
    //                   <td>${ex.type}</td>
    //                   <td>${ex.date}</td>
    //                   <td class="text-center">
    //                       <button class="btn btn-info btn-sm me-1 view-exercise-detail-btn" data-bs-toggle="modal"
    //                           data-bs-target="#exerciseDetailModal" data-exercise-id="${ex.id}">
    //                           <i class="fas fa-eye"></i>
    //                       </button>
    //                       <button class="btn btn-warning btn-sm me-1 edit-exercise-btn" data-exercise-id="${ex.id}">
    //                           <i class="fas fa-edit"></i>
    //                       </button>
    //                       <button class="btn btn-danger btn-sm delete-exercise-btn" data-exercise-id="${ex.id}">
    //                           <i class="fas fa-trash-alt"></i>
    //                       </button>
    //                   </td>
    //               </tr>
    //           `;
      //     $('#exerciseListBody').append(row);
      //   });
      // }
      // renderExerciseList(); // Gọi hàm này để hiển thị danh sách bài tập khi trang tải

      // // Khi modal chi tiết bài tập được mở, hiển thị thông tin (dùng dữ liệu giả định)
      // $(document).on('click', '.view-exercise-detail-btn', function () {
      //   const exerciseId = $(this).data('exercise-id');
      //   const exercise = exercises.find(ex => ex.id === exerciseId);

      //   if (exercise) {
      //     $('#modalExerciseTitle').text(exercise.title);
      //     $('#exerciseDetailId').text(exercise.id);
      //     $('#exerciseDetailTitle').text(exercise.title);
      //     $('#exerciseDetailType').text(exercise.type);
      //     $('#exerciseDetailDate').text(exercise.date);
      //     $('#exerciseDetailContent').html(exercise.description ? `<p>${exercise.description}</p>` : '<i>Không có mô tả.</i>');

      //     $('#exerciseQuestionsContainer').empty();
      //     if (exercise.questions && exercise.questions.length > 0) {
      //       let questionsHtml = '<h6>Câu hỏi chi tiết:</h6>';
      //       exercise.questions.forEach((q, qIndex) => {
      //         questionsHtml += `
    //                       <div class="mb-3 p-3 border rounded bg-light">
    //                           <p><strong>Câu hỏi ${qIndex + 1}:</strong> ${q.questionText}</p>
    //                           <ul class="list-unstyled">
    //                   `;
      //         q.options.forEach((opt, optIndex) => {
      //           questionsHtml += `
    //                               <li class="${q.correctAnswer === opt ? 'text-success fw-bold' : ''}">
    //                                   ${String.fromCharCode(65 + optIndex)}. ${opt}
    //                                   ${q.correctAnswer === opt ? '<i class="fas fa-check-circle ms-2"></i>' : ''}
    //                               </li>
    //                       `;
      //         });
      //         questionsHtml += `
    //                           </ul>
    //                       </div>
    //                   `;
      //       });
      //       $('#exerciseQuestionsContainer').html(questionsHtml);
      //     } else {
      //       $('#exerciseQuestionsContainer').html('<p><i>Không có câu hỏi chi tiết.</i></p>');
      //     }
      //   }
      // });




      // // Hàm renderExerciseList để cập nhật bảng sau khi xóa (hoặc thêm/sửa)
      // function renderExerciseList() {
      //   $('#exerciseListBody').empty(); // Xóa nội dung cũ
      //   if (exercises.length === 0) {
      //     $('#exerciseListBody').append('<tr><td colspan="5" class="text-center">Không có bài tập nào.</td></tr>');
      //   } else {
      //     exercises.forEach(ex => {
      //       const row = `
    //                   <tr>
    //                       <td>${ex.id}</td>
    //                       <td>${ex.title}</td>
    //                       <td>${ex.type}</td>
    //                       <td>${ex.date}</td>
    //                       <td class="text-center">
    //                           <button class="btn btn-info btn-sm me-1 view-exercise-detail-btn" data-bs-toggle="modal"
    //                               data-bs-target="#exerciseDetailModal" data-exercise-id="${ex.id}">
    //                               <i class="fas fa-eye"></i>
    //                           </button>
    //                           <button class="btn btn-warning btn-sm me-1 edit-exercise-btn" data-exercise-id="${ex.id}">
    //                               <i class="fas fa-edit"></i>
    //                           </button>
    //                           <button class="btn btn-danger btn-sm delete-exercise-btn" data-exercise-id="${ex.id}">
    //                               <i class="fas fa-trash-alt"></i>
    //                           </button>
    //                       </td>
    //                   </tr>
    //               `;
      //       $('#exerciseListBody').append(row);
      //     });
      //   }
      // }
      // renderExerciseList(); // Gọi lần đầu để hiển thị danh sách khi tải trang

      // // --- Bắt sự kiện click vào nút "Xóa" (icon thùng rác) trong bảng ---
      // $(document).on('click', '.delete-exercise-btn', function () {
      //   const exerciseId = $(this).data('exercise-id');
      //   const exerciseToDelete = exercises.find(ex => ex.id === exerciseId);

      //   if (exerciseToDelete) {
      //     // Đổ ID và tiêu đề bài tập vào modal xác nhận
      //     $('#exerciseToDeleteId').val(exerciseToDelete.id);
      //     $('#exerciseToDeleteTitle').text(exerciseToDelete.title);

      //     // Hiển thị modal xác nhận
      //     $('#deleteExerciseConfirmModal').modal('show');
      //   } else {
      //     alert('Không tìm thấy bài tập để xóa.');
      //   }
      // });

      // // --- Bắt sự kiện click vào nút "Xóa bài tập" trong modal xác nhận ---
      // $('#confirmDeleteExerciseBtn').on('click', function () {
      //   const idToDelete = parseInt($('#exerciseToDeleteId').val());

      //   // Tìm và xóa bài tập khỏi mảng (dữ liệu giả định)
      //   const initialLength = exercises.length;
      //   exercises = exercises.filter(ex => ex.id !== idToDelete);

      //   if (exercises.length < initialLength) {
      //     console.log('Bài tập có ID:', idToDelete, 'đã được xóa.');
      //     alert('Bài tập đã được xóa thành công!');
      //     renderExerciseList(); // Cập nhật lại bảng danh sách
      //   } else {
      //     alert('Có lỗi xảy ra khi xóa bài tập.');
      //   }

      //   // Đóng modal xác nhận
      //   $('#deleteExerciseConfirmModal').modal('hide');

      //   // Trong ứng dụng thực tế:
      //   // - Gửi yêu cầu DELETE đến API với idToDelete
      //   // - Sau khi nhận được phản hồi thành công từ server, cập nhật UI (gọi renderExerciseList())
      //   // - Xử lý lỗi nếu server trả về lỗi.
      // });

      // // (Thêm các mã JavaScript khác của bạn ở đây, ví dụ: xử lý add/edit modal)
      // // ... (Mã xử lý addExerciseModal và editExerciseModal từ các câu trả lời trước) ...

      // // Example for addExerciseModal submit (simplified for this context)
      // $('#newExerciseForm').on('submit', function (e) {
      //   e.preventDefault();
      //   // ... (Your existing validation and data collection logic) ...

      //   // For demo: create a new exercise object
      //   const newExercise = {
      //     id: exercises.length > 0 ? Math.max(...exercises.map(ex => ex.id)) + 1 : 1, // Generate new ID
      //     title: $('#newExerciseTitle').val(),
      //     maxScore: $('#newExerciseMaxScore').val() ? parseInt($('#newExerciseMaxScore').val()) : null,
      //     description: $('#newExerciseDescription').val(),
      //     type: "Trắc nghiệm",
      //     date: new Date().toISOString().slice(0, 10),
      //     questions: [] // Collect questions here from the form
      //   };

      //   // Add dummy questions for demo if none added in form
      //   if (newExercise.questions.length === 0) {
      //     newExercise.questions.push({
      //       id: 1,
      //       questionText: "Câu hỏi mẫu 1",
      //       options: ["A", "B", "C", "D"],
      //       correctAnswer: "A"
      //     });
      //   }

      //   exercises.push(newExercise); // Add to our dummy data
      //   renderExerciseList(); // Update the table
      //   $('#addExerciseModal').modal('hide');
      //   $('#newExerciseForm')[0].reset();
      //   $('#newExerciseForm').removeClass('was-validated');
      //   $('#questionsFormContainer').empty();
      //   // Reset questionCounter for add modal if you keep it global or pass it around
      //   // For now, assume a fresh state for questionsFormContainer in show.bs.modal for addExerciseModal
      // });

      // // Example for editExerciseModal submit (simplified for this context)
      // $('#editExerciseForm').on('submit', function (e) {
      //   e.preventDefault();
      //   // ... (Your existing validation and data collection logic) ...

      //   const editedExercise = {
      //     id: parseInt($('#editExerciseId').val()),
      //     title: $('#editExerciseTitle').val(),
      //     maxScore: $('#editExerciseMaxScore').val() ? parseInt($('#editExerciseMaxScore').val()) : null,
      //     description: $('#editExerciseDescription').val(),
      //     type: "Trắc nghiệm",
      //     date: exercises.find(ex => ex.id === parseInt($('#editExerciseId').val())).date,
      //     questions: [] // Collect questions here from the form
      //   };

      //   // For demo: Assume questions are correctly collected and added to editedExercise.questions
      //   // (You'd have your existing logic here)

      //   const index = exercises.findIndex(ex => ex.id === editedExercise.id);
      //   if (index !== -1) {
      //     exercises[index] = editedExercise; // Update in our dummy data
      //     renderExerciseList(); // Update the table
      //   }
      //   $('#editExerciseModal').modal('hide');
      // });


      var editor_config = {
        path_absolute: "/",
        selector: '.textarea-tiny',
        relative_urls: false,
        plugins: [
          'advlist autolink link image lists charmap print preview hr anchor pagebreak',
          'searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking',
          'table emoticons template paste help'
        ],
        toolbar: 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | ' +
          'bullist numlist outdent indent | link image | print preview media fullscreen | ' +
          'forecolor backcolor emoticons | help',
        menu: {
          favs: {
            title: 'My Favorites',
            items: 'code visualaid | searchreplace | emoticons'
          }
        },
        menubar: 'favs file edit view insert format tools table help',
        content_css: 'css/content.css',
        file_picker_callback: function(callback, value, meta) {
          var x = window.innerWidth || document.documentElement.clientWidth || document.getElementsByTagName(
            'body')[0].clientWidth;
          var y = window.innerHeight || document.documentElement.clientHeight || document
            .getElementsByTagName('body')[0].clientHeight;

          var cmsURL = editor_config.path_absolute + 'laravel-filemanager?editor=' + meta.fieldname;
          if (meta.filetype == 'image') {
            cmsURL = cmsURL + "&type=Images";
          } else {
            cmsURL = cmsURL + "&type=Files";
          }

          tinyMCE.activeEditor.windowManager.openUrl({
            url: cmsURL,
            title: 'Filemanager',
            width: x * 0.8,
            height: y * 0.8,
            resizable: "yes",
            close_previous: "no",
            onMessage: (api, message) => {
              callback(message.content);
            }
          });
        }
      };

      tinymce.init(editor_config);
    });
  </script>
@endsection
