$(document).ready(function () {
    let danhSachBaiKiemTra = [];

    const infoLopHoc = document.getElementById("info-lop-hoc");
    const idLopHoc = infoLopHoc.dataset.idLopHoc; // Truyền từ Blade

    $.ajax({
        url: `/bai-kiem-tra/${idLopHoc}`,
        method: "GET",
        success: function (data) {
            danhSachBaiKiemTra = data;
            renderDanhSach(danhSachBaiKiemTra);
        },
        error: function () {
            console.error("Lỗi khi tải danh sách bài kiểm tra");
        },
    });

    function renderDanhSach(data) {
        const container = $("#danhSachBaiKiemTra");
        container.empty();

        data.forEach((item) => {
            const html = `
                <div class="col mb-3">
                    <div class="card shadow-sm h-100 cursor-pointer item-bai-kiem-tra" data-id="${item.id}">
                        <div class="card-body">
                            <h5 class="card-title">${item.tieu_de}</h5>
                            <p class="card-text mb-1"><i class="bi bi-calendar-check"></i> Ngày đăng: ${item.ngay_tao}</p>
                        </div>
                    </div>
                </div>`;
            container.append(html);
        });
    }

    function openModalChiTiet(id) {
        const item = danhSachBaiKiemTra.find((b) => b.id === id);
        if (!item) return;

        $("#modalTieuDe").text(item.tieu_de);
        $("#modalSoCau").text(item.list_cau_hoi?.length ?? 0);
        $("#modalHanChot").text(item.ngay_ket_thuc);
        $("#btnLamBai").attr("href", `/lam-bai/${item.id}`);

        const modal = new bootstrap.Modal(
            document.getElementById("baiKiemTraModal")
        );
        modal.show();
    }

    $(document).on("click", ".item-bai-kiem-tra", function () {
        const id = $(this).data("id");
        openModalChiTiet(id);
    });

    // Kích hoạt nút "Tạo mới bài tập" để mở modal này
    $("#addNewExerciseBtn").attr("data-bs-toggle", "modal");
    $("#addNewExerciseBtn").attr("data-bs-target", "#addExerciseModal");

    let questionCounter = 1; // Biến đếm số câu hỏi

    // --- Sự kiện khi modal thêm bài tập hiện lên ---
    $("#addExerciseModal").on("show.bs.modal", function () {
        // Đặt lại form khi modal mở
        $("#newExerciseForm")[0].reset();
        $("#newExerciseForm").removeClass("was-validated");
        $("#questionsFormContainer").empty(); // Xóa tất cả câu hỏi cũ
        questionCounter = 1; // Đặt lại bộ đếm
        $("#questionsFormContainer").append(questionTemplate(questionCounter));
        $("#noQuestionsMessage").show(); // Hiển thị thông báo khi không có câu hỏi
    });

    // Mẫu HTML cho một câu hỏi mới
    const questionTemplate = (count) => `
            <div class="question-item mb-4 p-3 border rounded bg-light" data-question-index="${count}">
                <h7 class="d-flex justify-content-between align-items-center mb-3">
                    <strong>Câu hỏi ${count}</strong>
                    <button type="button" class="btn btn-sm btn-outline-danger remove-question-btn">
                        <i class="fas fa-times"></i> Xóa
                    </button>
                </h7>
                <div class="mb-3">
                    <label for="question${count}Text" class="form-label">Nội dung câu hỏi <span class="text-danger">*</span></label>
                    <textarea class="form-control question-text" id="question${count}Text" rows="2" placeholder="Nhập nội dung câu hỏi" required></textarea>
                    <div class="invalid-feedback">
                        Vui lòng nhập nội dung câu hỏi.
                    </div>
                </div>
                <div class="row g-2 mb-3">
                    <label class="form-label">Đáp án: <span class="text-danger">*</span></label>
                    <div class="col-md-6">
                        <div class="input-group">
                            <div class="input-group-text">
                                <input class="form-check-input mt-0 correct-answer-radio" type="radio" name="correctAnswer_${count}" value="optionA" aria-label="Đáp án A" required>
                            </div>
                            <input type="text" class="form-control answer-option" placeholder="Đáp án A" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="input-group">
                            <div class="input-group-text">
                                <input class="form-check-input mt-0 correct-answer-radio" type="radio" name="correctAnswer_${count}" value="optionB" aria-label="Đáp án B" required>
                            </div>
                            <input type="text" class="form-control answer-option" placeholder="Đáp án B" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="input-group">
                            <div class="input-group-text">
                                <input class="form-check-input mt-0 correct-answer-radio" type="radio" name="correctAnswer_${count}" value="optionC" aria-label="Đáp án C" required>
                            </div>
                            <input type="text" class="form-control answer-option" placeholder="Đáp án C" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="input-group">
                            <div class="input-group-text">
                                <input class="form-check-input mt-0 correct-answer-radio" type="radio" name="correctAnswer_${count}" value="optionD" aria-label="Đáp án D" required>
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
        `;

    // Cập nhật số thứ tự câu hỏi và trạng thái nút xóa
    function updateQuestionNumbers() {
        const $questionItems = $("#questionsFormContainer .question-item");
        if ($questionItems.length === 0) {
            $("#noQuestionsMessage").show();
        } else {
            $("#noQuestionsMessage").hide();
        }

        $questionItems.each(function (index) {
            const $this = $(this);
            $this.find("h7 strong").text(`Câu hỏi ${index + 1}`);
            // Cập nhật thuộc tính name của radio button để đảm bảo chúng hoạt động độc lập
            $this
                .find(".correct-answer-radio")
                .attr("name", `correctAnswer_${index + 1}`);

            // Ẩn/hiện nút xóa nếu chỉ còn 1 câu hỏi
            if ($questionItems.length === 1) {
                $this.find(".remove-question-btn").hide();
            } else {
                $this.find(".remove-question-btn").show();
            }
        });
    }

    // --- Xóa câu hỏi ---
    // Sử dụng event delegation vì các nút xóa được thêm động
    $(document).on("click", ".remove-question-btn", function () {
        const $questionItemToRemove = $(this).closest(".question-item");
        if ($("#questionsFormContainer .question-item").length > 1) {
            // Chỉ xóa nếu có hơn 1 câu hỏi
            $questionItemToRemove.remove();
            updateQuestionNumbers();
        } else {
            alert("Bạn phải có ít nhất một câu hỏi.");
        }
    });

    // Thêm câu hỏi mới
    $("#addQuestionBtn").on("click", function () {
        questionCounter++;
        $("#questionsFormContainer").append(questionTemplate(questionCounter));
        updateQuestionNumbers();
    });

    // Xử lý nhấn nút Lưu bài kiểm tra Trong Modal thêm bài kiểm tra
    $("#newExerciseForm").on("submit", function (e) {
        e.preventDefault();

        if (!this.checkValidity()) {
            e.stopPropagation();
            $(this).addClass("was-validated");
            return;
        }

        if ($("#questionsFormContainer .question-item").length === 0) {
            $("#noQuestionsMessage").show();
            alert("Vui lòng thêm ít nhất một câu hỏi cho bài kiểm tra.");
            return;
        } else {
            $("#noQuestionsMessage").hide();
        }

        const newExercise = {
            tieuDe: $("#newExerciseTitle").val(),
            diemToiDa: $("#newExerciseMaxScore").val()
                ? parseInt($("#newExerciseMaxScore").val())
                : null,
            idLopHoc: $("#idLopHoc").val(),
            danhSachCauHoi: [],
        };

        let isValid = true;

        $("#questionsFormContainer .question-item").each(function (index) {
            const $thisQuestion = $(this);
            const cauHoi = $thisQuestion.find(".question-text").val();
            const danhSachDapAn = [];
            let dapAnDuocChon = null;

            $thisQuestion.find(".answer-option").each(function (optIndex) {
                const dapAn = $(this).val();
                danhSachDapAn.push(dapAn);

                if (
                    $thisQuestion
                        .find(
                            `.correct-answer-radio[value="option${String.fromCharCode(
                                65 + optIndex
                            )}"]`
                        )
                        .is(":checked")
                ) {
                    dapAnDuocChon = optIndex;
                }
            });

            if (
                !cauHoi.trim() ||
                danhSachDapAn.some((opt) => !opt.trim()) ||
                dapAnDuocChon === null
            ) {
                alert(
                    `Vui lòng điền đầy đủ nội dung và chọn đáp án đúng cho Câu hỏi ${
                        index + 1
                    }.`
                );
                isValid = false;
                return false;
            }

            newExercise.danhSachCauHoi.push({
                cauHoi: cauHoi,
                danhSachDapAn: danhSachDapAn,
                dapAnDuocChon: dapAnDuocChon,
            });
        });

        if (!isValid) return;

        // Gửi AJAX
        $.ajax({
            url: "/bai-kiem-tra",
            method: "POST",
            contentType: "application/json",
            data: JSON.stringify(newExercise),
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (response) {
                if (response.success) {
                    alert(response.message);
                    $("#addExerciseModal").modal("hide");
                    $("#newExerciseForm")[0].reset();
                    $("#newExerciseForm").removeClass("was-validated");
                    $("#questionsFormContainer").empty();
                    $("#noQuestionsMessage").show();
                    questionCounter = 1;
                    // Cập nhật lại danh sách và hiển thị
                    danhSachBaiTap = response.data;
                    loadExerciseList(danhSachBaiTap);
                } else {
                    alert("Tạo thất bại: " + response.message);
                }
            },
            error: function (xhr) {
                alert("Đã xảy ra lỗi phía server.");
                console.error(xhr.responseText);
            },
        });
    });

    $(".btn-accept-request").click(function () {
        let id = $(this).data("id");

    $.ajax({
      url: `/thanh-vien-lop/${id}/chap-nhan`,
      method: "POST",
      data: {
        _token: $('meta[name="csrf-token"]').attr("content"),
      },
      success: function (res) {
        if (res.status) {
          $(".card-body").html(res.html);
        } else {
          Swal.fire({
            icon: "error",
            title: "Thất bại",
            text: res.message || "Đã xảy ra lỗi",
          });
        }
      },
      error: function () {
        Swal.fire({
          icon: "error",
          title: "Lỗi hệ thống",
          text: "Không thể kết nối đến máy chủ.",
        });
      },
    });
  });

  $(".btn-reject-request").click(function () {
    let id = $(this).data("id");

    $.ajax({
      url: `/thanh-vien-lop/${id}/tu-choi`,
      method: "POST",
      data: {
        _token: $('meta[name="csrf-token"]').attr("content"),
      },
      success: function (res) {
        if (res.status) {
          $(".yeuCau").html(res.html);
        } else {
          Swal.fire({
            icon: "error",
            title: "Thất bại",
            text: res.message || "Đã xảy ra lỗi",
          });
        }
      },
      error: function () {
        Swal.fire({
          icon: "error",
          title: "Lỗi hệ thống",
          text: "Không thể kết nối đến máy chủ.",
        });
      },
    });
  });

    // --- Sự kiện click nút "Chèn bài giảng đã chọn" ---
    // $('#insertSelectedLecturesBtn').on('click', function () {
    //   const chuongId = $('#chuongIdToInsert').val();

    //   if (!currentSelectedLectures.length) {
    //     alert('Vui lòng chọn ít nhất một bài giảng để chèn.');
    //     return;
    //   }

    //   console.log('Chèn bài giảng vào chương ID:', chuongId);
    //   console.log('Các bài giảng sẽ được chèn:', currentSelectedLectures);

    //   // --- Logic gửi dữ liệu lên server (API call) ---
    //   alert(`Đã chèn ${currentSelectedLectures.length} bài giảng vào chương ID ${chuongId}.`);
    //   $('#addLectureModal').modal('hide');
    // });

    const $addMemberModal = $("#addMemberModal");
    const $studentSearchInput = $("#studentSearch");
    const $searchStudentBtn = $("#searchStudentBtn");
    const $studentListBody = $("#studentListBody");
    const $noStudentsFoundAlert = $("#noStudentsFoundAlert");
    const $addSelectedMembersBtn = $("#addSelectedMembersBtn");

    let allStudents = []; // Biến để lưu trữ tất cả sinh viên (hoặc kết quả tìm kiếm mới nhất)

    // --- Hàm giả lập tìm kiếm sinh viên (thay thế bằng API thực tế) ---
    function searchStudents(searchTerm) {
        // Trong thực tế, bạn sẽ gọi API ở đây
        // $.ajax({
        //     url: 'YOUR_API_ENDPOINT_FOR_SEARCH_STUDENTS',
        //     method: 'GET',
        //     data: { query: searchTerm },
        //     success: function(response) {
        //         allStudents = response.data; // Giả sử API trả về { data: [...] }
        //         renderStudentList(allStudents);
        //     },
        //     error: function(error) {
        //         console.error('Lỗi tìm kiếm sinh viên:', error);
        //         $studentListBody.html('<tr><td colspan="4" class="text-center text-danger">Lỗi khi tìm kiếm sinh viên.</td></tr>');
        //     }
        // });

        // --- Dữ liệu sinh viên giả định ---
        const mockStudents = [
            {
                id: 1,
                name: "Nguyễn Văn A",
                email: "a@example.com",
                phone: "0901234567",
            },
            {
                id: 2,
                name: "Trần Thị B",
                email: "b@example.com",
                phone: "0909876543",
            },
            {
                id: 3,
                name: "Lê Công C",
                email: "c@example.com",
                phone: "0911223344",
            },
            {
                id: 4,
                name: "Phạm Thị D",
                email: "d@example.com",
                phone: "0988776655",
            },
            {
                id: 5,
                name: "Hoàng Văn E",
                email: "e@example.com",
                phone: "0933445566",
            },
        ];

        const filteredStudents = mockStudents.filter(
            (student) =>
                student.name.toLowerCase().includes(searchTerm.toLowerCase()) ||
                student.email.toLowerCase().includes(searchTerm.toLowerCase())
        );
        allStudents = filteredStudents;
        renderStudentList(allStudents);
    }

    // --- Hàm hiển thị danh sách sinh viên ---
    function renderStudentList(students) {
        $studentListBody.empty();
        $noStudentsFoundAlert.hide();

        if (students.length === 0) {
            $studentListBody.html(
                '<tr><td colspan="4" class="text-center">Không có sinh viên nào phù hợp.</td></tr>'
            );
            $noStudentsFoundAlert.show();
            return;
        }

        students.forEach((student) => {
            const row = `
                <tr>
                    <td>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input student-checkbox" value="${
                                student.id
                            }">
                        </div>
                    </td>
                    <td><span class="math-inline">${student.name}</td>
                    <td><span>${student.email}</span></td>
                    <td>${student.phone || "N/A"}</td>
                </tr>
            `;
            $studentListBody.append(row);
        });
    }

    // --- Sự kiện click nút tìm kiếm ---
    $searchStudentBtn.on("click", function () {
        const searchTerm = $studentSearchInput.val().trim();
        searchStudents(searchTerm);
    });

    // --- Sự kiện nhấn Enter trong ô tìm kiếm ---
    $studentSearchInput.on("keypress", function (event) {
        if (event.key === "Enter") {
            $searchStudentBtn.click();
        }
    });

    // --- Sự kiện hiển thị modal (reset trạng thái) ---
    $addMemberModal.on("show.bs.modal", function () {
        $studentSearchInput.val("");
        $studentListBody.html(
            '<tr><td colspan="4" class="text-center">Nhập thông tin để tìm kiếm sinh viên.</td></tr>'
        );
        $noStudentsFoundAlert.hide();
        allStudents = []; // Reset danh sách sinh viên
    });

    // --- Xử lý sự kiện click nút "Thêm vào lớp" ---
    $addSelectedMembersBtn.on("click", function () {
        const selectedStudents = [];
        $(".student-checkbox:checked").each(function () {
            const studentData = $(this).data("student");
            selectedStudents.push(studentData.id); // Hoặc toàn bộ đối tượng studentData
        });

        if (selectedStudents.length > 0) {
            console.log("Sinh viên được chọn để thêm:", selectedStudents);

            // --- Gọi API để thêm sinh viên vào lớp (thay thế bằng API thực tế) ---
            // $.ajax({
            //     url: 'YOUR_API_ENDPOINT_FOR_ADD_STUDENTS',
            //     method: 'POST',
            //     contentType: 'application/json',
            //     data: JSON.stringify({ studentIds: selectedStudents }),
            //     success: function(response) {
            //         if (response.success) {
            //             alert('Đã thêm sinh viên vào lớp thành công!');
            //             $addMemberModal.modal('hide');
            //             // Tùy chọn: Cập nhật lại danh sách thành viên hiện tại trên trang
            //         } else {
            //             alert('Lỗi khi thêm sinh viên vào lớp: ' + response.message);
            //         }
            //     },
            //     error: function(error) {
            //         console.error('Lỗi thêm sinh viên vào lớp:', error);
            //         alert('Có lỗi xảy ra khi thêm sinh viên vào lớp. Vui lòng thử lại.');
            //     }
            // });

            // --- Giả lập thành công ---
            alert(
                `Đã chọn ${selectedStudents.length} sinh viên để thêm vào lớp.`
            );
            $addMemberModal.modal("hide");
            // Tùy chọn: Cập nhật lại danh sách thành viên trên trang
        } else {
            alert("Vui lòng chọn ít nhất một sinh viên để thêm vào lớp.");
        }
    });

    // --- Sự kiện hiển thị modal khi nút "Thêm vào lớp" được click ---
    $("#addNewLessonBtn").on("click", function () {
        $addMemberModal.modal("show");
    });
});






// Enable cho tooltips (bootstrap 5)
const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))


let listBaiTrongLop = {};

// Xử lý check tất cả bản ghi
$(document).on('change', '.check-all', function () {
  const isChecked = $(this).is(':checked');
  const tbody = $(this).parents('thead').next('tbody');

  tbody.find('.row-checkbox').prop('checked', isChecked);
});


handleCheckAllSelected($('.row-checkbox:checked'));

$(document).on('change', '.row-checkbox', function () {
  // const tbody = $(this).parents('tbody');
  // const thead = tbody.prev('thead');
  // const total = tbody.find('.row-checkbox').length;
  // const checked = tbody.find('.row-checkbox:checked').length;

  // thead.find('.check-all').prop('checked', total === checked);

  handleCheckAllSelected($(this));
});

function handleCheckAllSelected(checkbox) {
  checkbox.each(function (index, element) {
    // console.log(element.parents());
    const tbody = $(element).parents('tbody');
    const thead = tbody.prev('thead');
    const total = tbody.find('.row-checkbox').length;
    const checked = tbody.find('.row-checkbox:checked').length;

    thead.find('.check-all').prop('checked', total === checked);
  });
}


$.ajaxSetup({
    headers: {
        "X-CSRF-TOKEN": $('#csrfForm input[name="_token"]').val(),
    },
});


$(document).on('click', '.btn-public-bai', function (e) {
  const checkbox = $('.row-checkbox');
  checkbox.each(function (index, element) {
    const idBai = parseInt($(element).data('id'));
    const congKhai = $(element).is(':checked') ? '1' : '0';

    listBaiTrongLop[idBai] = congKhai;
  });

  const url = window.location.pathname;

  $.ajax({
    url: `${url}/bai/cong-khai`,
    type: 'POST',
    data: {
      listBaiTrongLop: listBaiTrongLop
    },
    dataType: 'json',
    success: function (response) {
      window.location.reload();
      alert(response.message);
    },
    error: function (xhr) {
      alert("Đã xảy ra lỗi: " + xhr.status + ' ' + xhr.statusText);
    }
  });
});
