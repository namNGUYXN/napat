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

$.ajaxSetup({
    headers: {
        "X-CSRF-TOKEN": $('#csrfForm input[name="_token"]').val(),
    },
});

// Xử lý bài giảng
let cacheListBaiGiang = [];
let listBaiGiangSelected = [];

// Sự kiện khi chọn mục bài giảng
$("#select-muc-bai-giang").on("change", function () {
    const urlListBaiGiang = $(this).val();

    const alertKoMucBaiGiang = $("#alert-ko-muc-bai-giang");
    const sectionListBaiGiang = $("#section-list-bai-giang");

    // Khi select mục bài giảng được chọn
    if (urlListBaiGiang) {
        // alert(urlListBaiGiang);

        // Hiển thị list bài giảng và ẩn cảnh báo
        alertKoMucBaiGiang.hide();

        if (cacheListBaiGiang[urlListBaiGiang]) {
            const listBaiGiang = cacheListBaiGiang[urlListBaiGiang];
            renderListBaiGiangTheoHocPhan(listBaiGiang, urlListBaiGiang);
            $("#input-search-bai-giang").val(""); // Reset nội dung search khi đổi mục bg
            return;
        }

        $.ajax({
            url: urlListBaiGiang,
            type: "POST",
            dataType: "json",
            success: function (response) {
                const listBaiGiang = response.data;
                cacheListBaiGiang[urlListBaiGiang] = listBaiGiang;
                renderListBaiGiangTheoHocPhan(listBaiGiang, urlListBaiGiang);
                $("#input-search-bai-giang").val(""); // Reset nội dung search khi đổi mục bg
                // console.log(cacheListBaiGiang);
            },
            error: function (xhr) {
                alert("Đã xảy ra lỗi: " + xhr.status + " " + xhr.statusText);
            },
        });
    } else {
        // Ẩn phần list bài giảng và hiển thị cảnh báo
        alertKoMucBaiGiang.show();
        $("#body-table-list-bai-giang")
            .empty()
            .append(
                '<tr><td colspan="4" class="text-center">Chọn mục bài giảng để hiển thị bài giảng.</td></tr>'
            );
    }
});

function renderListBaiGiangTheoHocPhan(listBaiGiang, url) {
    const idLopHoc = $("#info-lop-hoc").data("id-lop-hoc");
    const idChuong = $("#selected-lecture-insert-btn").data("id-chuong");
    const urlListBaiGiangTrongChuong = `/lop-hoc/${idLopHoc}/chuong/${idChuong}/bai-giang/list`;
    const bodyTableListBaiGiang = $("#body-table-list-bai-giang");

    bodyTableListBaiGiang.empty(); // Xóa nội dung cũ

    if (listBaiGiang.length === 0) {
        bodyTableListBaiGiang.append(
            '<tr><td colspan="4" class="text-center">Không có bài giảng nào trong mục bài giảng này hoặc không tìm thấy kết quả.</td></tr>'
        );
        return;
    }

    $.ajax({
        url: urlListBaiGiangTrongChuong,
        type: "POST",
        dataType: "json",
        success: function (response) {
            const listBaiGiangTuDB = response.data;
            // console.log(listBaiGiang);

            listBaiGiang.forEach((baiGiang, index) => {
                // Kiểm tra xem bài giảng đã được chọn trước đó trong mảng listBaiGiangSelected chưa
                const isChecked = listBaiGiangSelected.some(
                    (id) => id === baiGiang.id
                );
                const isExists = listBaiGiangTuDB.some(
                    (element) => element.id_bai_giang === baiGiang.id
                );
                const row = `
        <tr>
          <td>
            <div class="form-check">
              <input class="form-check-input lecture-checkbox" type="checkbox" 
                  value="${baiGiang.id}" ${isChecked ? "checked" : ""}>
            </div>
          </td>
          <td>
            ${
                isExists
                    ? '<small class="fst-italic text-muted me-2">(Đã có trong chương)</small>'
                    : ""
            }
            ${baiGiang.tieu_de}
          </td>
          <td class="text-end">
            <button type="button" class="btn btn-sm btn-info lecture-detail-btn" 
                data-bs-toggle="modal" data-bs-target="#modal-chi-tiet-bai-giang" 
                data-index="${index}"
                data-url="${url}">
                <i class="fas fa-eye"></i> </button>
          </td>
        </tr>
    `;
                bodyTableListBaiGiang.append(row);
            });
        },
        error: function (xhr) {
            alert("Đã xảy ra lỗi: " + xhr.status + " " + xhr.statusText);
        },
    });
}

// Sự kiện xem chi tiết bài giảng trong modal
$(document).on("click", ".lecture-detail-btn", function () {
    const btn = $(this);
    const urlListBaiGiang = btn.data("url");
    const index = btn.data("index");
    const baiGiang = cacheListBaiGiang[urlListBaiGiang][index];
    // console.log(baiGiang);

    $("#tieu-de-bai-giang").text(baiGiang.tieu_de);
    $("#noi-dung-bai-giang").html(baiGiang.noi_dung);
});

// Sự kiện tìm kiếm bài giảng
let debounceTimer;

$("#input-search-bai-giang").on("keyup", function () {
    clearTimeout(debounceTimer);

    debounceTimer = setTimeout(function () {
        handleSearchBaiGiang(); // Gọi sau khi dừng gõ 300ms
    }, 300);
});

function handleSearchBaiGiang() {
    const urlListBaiGiang = $("#select-muc-bai-giang").val();
    if (!urlListBaiGiang) return; // Không tìm kiếm nếu chưa chọn mục bg

    const noiDungTimKiem = $("#input-search-bai-giang")
        .val()
        .toLowerCase()
        .trim();
    const listBaiGiang = cacheListBaiGiang[urlListBaiGiang];

    const listBaiGiangSearch = listBaiGiang.filter((lecture) =>
        lecture.tieu_de.toLowerCase().includes(noiDungTimKiem)
    );
    renderListBaiGiangTheoHocPhan(listBaiGiangSearch, urlListBaiGiang);
}

$(document).on("click", ".lecture-insert-btn", function () {
    resetModalGanBaiGiang();

    const btnGanBaiGiang = $("#selected-lecture-insert-btn");
    const urlGanBaiGiang = $(this).data("url");
    const idChuong = $(this).data("id-chuong");
    btnGanBaiGiang.data("url", urlGanBaiGiang);
    btnGanBaiGiang.data("id-chuong", idChuong);

    $("#modal-gan-bai-giang").modal("show");
});

function resetModalGanBaiGiang() {
    $("#select-muc-bai-giang").val("");
    $("#input-search-bai-giang").val("");
    $("#body-table-list-bai-giang")
        .empty()
        .append(
            '<tr><td colspan="4" class="text-center">Chọn mục bài giảng để hiển thị bài giảng.</td></tr>'
        );
    $("#alert-ko-muc-bai-giang").show();
    listBaiGiangSelected = [];
}

// Sự khiện check nút chọn bài giảng để insert
$(document).on("change", ".lecture-checkbox", function () {
    const lectureId = parseInt($(this).val());
    const isChecked = $(this).is(":checked");

    if (isChecked) {
        listBaiGiangSelected.push(lectureId);
    } else {
        listBaiGiangSelected = listBaiGiangSelected.filter(
            (l) => l.id !== lectureId
        );
    }
});

// Load chương vào lớp
loadListChuong();

function loadListChuong() {
    const idHocPhan = $("#info-lop-hoc").data("id-hoc-phan");
    const idLopHoc = $("#info-lop-hoc").data("id-lop-hoc");

    loadListBaiGiang((listBaiGiang) => {
        $.ajax({
            url: `/hoc-phan/${idHocPhan}/chuong/list`,
            type: "POST",
            dataType: "json",
            success: function (response) {
                const listChuong = response.data;

                const htmlListChuong = listChuong.map((chuong, index) => {
                    // Lấy các bài giảng theo chương trong lớp
                    const listBaiGiangTheoChuong = listBaiGiang.filter(
                        (baiGiang) => baiGiang.chuong.id == chuong.id
                    );

                    const htmlListBaiGiang = listBaiGiangTheoChuong
                        .map((baiGiang) => {
                            const urlGoBaiGiang =
                                window.location.origin +
                                `/lop-hoc/${idLopHoc}/chuong/${chuong.id}/bai-giang/${baiGiang.bai_giang.id}/go`;

                            return `
              <div class="list-group-item list-group-item-action list-group-item-info d-flex justify-content-between align-items-center">
                <a href="#" class="text-decoration-none text-info-emphasis flex-grow-1">
                  ${baiGiang.bai_giang.tieu_de}
                </a>
                <button type="button" class="btn btn-sm btn-outline-danger lecture-remove-btn"
                  data-url="${urlGoBaiGiang}">
                  <i class="fas fa-times"></i>
                </button>
              </div>
            `;
                        })
                        .join("");

                    return renderListChuongVaBaiGiang(
                        index,
                        chuong,
                        htmlListBaiGiang
                    );
                });

                $("#accordion-chuong").html(htmlListChuong.join(""));
            },
            error: function (xhr) {
                alert("Đã xảy ra lỗi: " + xhr.status + " " + xhr.statusText);
            },
        });
    });
}

function loadListBaiGiang(callback) {
    const idLopHoc = $("#info-lop-hoc").data("id-lop-hoc");

    $.ajax({
        url: `/lop-hoc/${idLopHoc}/bai-giang/list`,
        type: "POST",
        dataType: "json",
        success: function (response) {
            const listBaiGiang = response.data;

            if (callback) callback(listBaiGiang.bai_giang_lop);
            else alert("OK");
        },
        error: function (xhr) {
            alert("Đã xảy ra lỗi: " + xhr.status + " " + xhr.statusText);
        },
    });
}

function renderListChuongVaBaiGiang(index, chuong, htmlListBaiGiang) {
    const idLopHoc = $("#info-lop-hoc").data("id-lop-hoc");
    const url =
        window.location.origin +
        `/lop-hoc/${idLopHoc}/chuong/${chuong.id}/bai-giang/gan`;

    return `
    <div class="accordion-item">
      <h2 class="accordion-header" id="heading-${index}">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
          data-bs-target="#collapse-${index}" aria-expanded="false" aria-controls="collapse-${index}">
          ${chuong.tieu_de}
        </button>
      </h2>
      <div id="collapse-${index}" class="accordion-collapse collapse" aria-labelledby="heading-${index}">
        <div class="accordion-body">
          <div class="list-group">

            ${htmlListBaiGiang}

          </div>
          <div class="text-center mt-3">
            <button class="btn btn-sm btn-outline-primary lecture-insert-btn"
              data-url="${url}" data-id-chuong="${chuong.id}">
              <i class="fas fa-plus"></i> Chèn bài giảng
            </button>
          </div>
        </div>
      </div>
    </div>
  `;
}

// $('#modal-gan-bai-giang').on('hidden.bs.modal', function () {
//   console.log(listBaiGiangSelected);
// });

$("#selected-lecture-insert-btn").on("click", function (e) {
    const urlGanBaiGiang = $(this).data("url");

    $.ajax({
        url: urlGanBaiGiang,
        type: "POST",
        data: {
            listIdBaiGiang: listBaiGiangSelected,
        },
        dataType: "json",
        success: function (response) {
            alert(response.message);

            if (response.success) {
                loadListChuong();
            }
        },
        error: function (xhr) {
            alert("Đã xảy ra lỗi: " + xhr.status + " " + xhr.statusText);
        },
    });

    resetModalGanBaiGiang();
    $("#modal-gan-bai-giang").modal("hide");
});

// Sự kiện xóa bài giảng khỏi lớp
$(document).on("click", ".lecture-remove-btn", function () {
    const urlGoBaiGiang = $(this).data("url");

    $.ajax({
        url: urlGoBaiGiang,
        type: "DELETE",
        dataType: "json",
        success: function (response) {
            alert(response.message);

            if (response.success) {
                loadListChuong();
            }
        },
        error: function (xhr) {
            alert("Đã xảy ra lỗi: " + xhr.status + " " + xhr.statusText);
        },
    });
});
