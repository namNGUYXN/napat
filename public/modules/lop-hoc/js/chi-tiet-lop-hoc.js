document.addEventListener("DOMContentLoaded", function () {
    let questionCounter = 0;

    //Mẫu html để load dữ liệu vào
    const questionTemplate = (
        count,
        questionText = "",
        answerOptions = ["", "", "", ""],
        correctAnswer = ""
    ) => {
        const optionLabels = ["A", "B", "C", "D"];
        const optionValues = ["optionA", "optionB", "optionC", "optionD"];
        const optionsHTML = optionValues
            .map(
                (value, index) => `
            <div class="col-md-6">
                <div class="input-group">
                    <div class="input-group-text">
                        <input class="form-check-input mt-0 correct-answer-radio" 
                               type="radio" name="correctAnswer_${count}" 
                               value="${value}" ${
                    correctAnswer === value ? "checked" : ""
                } required>
                    </div>
                    <input type="text" class="form-control answer-option" 
                           value="${
                               answerOptions[index] || ""
                           }" placeholder="Đáp án ${
                    optionLabels[index]
                }" required>
                </div>
            </div>
        `
            )
            .join("");

        return `
            <div class="question-item mb-4 p-3 border rounded bg-light" data-question-index="${count}">
                <h7 class="d-flex justify-content-between align-items-center mb-3">
                    <strong>Câu hỏi ${count}</strong>
                    <button type="button" class="btn btn-sm btn-outline-danger remove-question-btn">
                        <i class="fas fa-times"></i> Xóa
                    </button>
                </h7>
                <div class="mb-3">
                    <label class="form-label">Nội dung câu hỏi <span class="text-danger">*</span></label>
                    <textarea class="form-control question-text" rows="2" required>${questionText}</textarea>
                    <div class="invalid-feedback">Vui lòng nhập nội dung câu hỏi.</div>
                </div>
                <div class="row g-2 mb-3">
                    <label class="form-label">Đáp án: <span class="text-danger">*</span></label>
                    ${optionsHTML}
                    <div class="col-12">
                        <div class="invalid-feedback">
                            Vui lòng chọn một đáp án đúng cho câu hỏi này.
                        </div>
                    </div>
                </div>
            </div>
        `;
    };

    //Thêm câu hỏi(Thủ công và File)
    function addQuestion(
        questionText = "",
        answers = ["", "", "", ""],
        correct = ""
    ) {
        questionCounter++;
        $("#questionsFormContainer").append(
            questionTemplate(questionCounter, questionText, answers, correct)
        );
        updateQuestionNumbers();
    }

    //Cập nhật lại thứ tự câu hỏi cho đúng khi thêm mới hoặc xóa
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
            $this
                .find(".correct-answer-radio")
                .attr("name", `correctAnswer_${index + 1}`);
            if ($questionItems.length === 1) {
                $this.find(".remove-question-btn").hide();
            } else {
                $this.find(".remove-question-btn").show();
            }
        });
    }

    $("#addNewExerciseBtn").attr("data-bs-toggle", "modal");
    $("#addNewExerciseBtn").attr("data-bs-target", "#addExerciseModal");

    //Khi mở modal thêm bài kiểm tra
    $("#addExerciseModal").on("show.bs.modal", function () {
        $("#newExerciseForm")[0].reset();
        $("#newExerciseForm").removeClass("was-validated");
        $("#questionsFormContainer").empty();
        questionCounter = 0;
        addQuestion(); // mặc định 1 câu hỏi
        $("#noQuestionsMessage").show();
    });

    //Nhấn nút thêm câu hỏi
    $("#addQuestionBtn").on("click", function () {
        addQuestion();
    });

    //Nhấn nút xóa câu hỏi
    $(document).on("click", ".remove-question-btn", function () {
        const $toRemove = $(this).closest(".question-item");
        if ($("#questionsFormContainer .question-item").length > 1) {
            $toRemove.remove();
            updateQuestionNumbers();
        } else {
            alert("Bạn phải có ít nhất một câu hỏi.");
        }
    });

    //Đọc file được chọn và truyền dữ liệu để tạo html hiển thị danh sách câu hỏi từ file
    $("#excelFileInput").on("change", function (e) {
        const file = e.target.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function (e) {
            const data = new Uint8Array(e.target.result);
            const workbook = XLSX.read(data, { type: "array" });
            const sheet = workbook.Sheets[workbook.SheetNames[0]];
            const rows = XLSX.utils.sheet_to_json(sheet, { header: 1 });

            for (let i = 1; i < rows.length; i++) {
                const row = rows[i];
                const questionText = row[0] || "";
                const answers = [row[1], row[2], row[3], row[4]];
                const correctLabel = (row[5] || "")
                    .toString()
                    .trim()
                    .toUpperCase();

                const correctValue =
                    {
                        A: "optionA",
                        B: "optionB",
                        C: "optionC",
                        D: "optionD",
                    }[correctLabel] || "";

                if (
                    questionText &&
                    answers.filter((a) => !!a).length >= 2 &&
                    correctValue
                ) {
                    addQuestion(questionText, answers, correctValue);
                }
            }

            $("#excelFileInput").val("");
        };

        reader.readAsArrayBuffer(file);
    });
});

let allChiTietTheoKetQua = {};

function xemChiTietKetQua(idKetQua, tenNguoiDung, diem, tieuDeBaiKT) {
    const chiTiet = allChiTietTheoKetQua[idKetQua];

    let soCauDung = 0;
    let tongCau = 0;

    if (chiTiet && chiTiet.cauHoiVaDapAn) {
        tongCau = chiTiet.cauHoiVaDapAn.length;
        chiTiet.cauHoiVaDapAn.forEach((item) => {
            item.danh_sach_dap_an.forEach((dapAn) => {
                if (dapAn.la_dap_an_dung && dapAn.duoc_chon) {
                    soCauDung++;
                }
            });
        });
    }
    const title = `${tieuDeBaiKT} - ${tenNguoiDung} - ${soCauDung}/${tongCau} câu đúng`;
    const content = renderChiTietBaiKiemTra({}, chiTiet);

    // Cập nhật tiêu đề
    $("#modalChiTiet .modal-title").text(title);

    // Thay nút đóng thành nút quay lại
    $("#modalChiTiet .modal-actions").html(`
        <button class="btn btn-secondary" onclick="quayLaiDanhSach(2)">
            ← Quay lại danh sách
        </button>
    `);

    // Gán nội dung vào modal body
    $("#modalChiTiet .modal-body").html(content);
}

function renderChiTietBaiKiemTra(baiKiemTra, chiTiet) {
    if (!chiTiet || !chiTiet.cauHoiVaDapAn) {
        return `<p>Bạn chưa làm bài kiểm tra này.</p>`;
    }

    let html = "";
    chiTiet.cauHoiVaDapAn.forEach((item, index) => {
        html += `<div class="mb-4 border p-3 rounded bg-light">
            <strong>Câu ${index + 1}: ${item.cau_hoi}</strong>
            <ul class="mt-2">`;

        let dapAnDung = "";
        let dapAnChon = "";

        item.danh_sach_dap_an.forEach((dapAn) => {
            let classes = [];
            let icon = "";

            if (dapAn.la_dap_an_dung) {
                classes.push("text-primary", "fw-bold");
                dapAnDung = dapAn.ma;
            }

            if (dapAn.duoc_chon) {
                dapAnChon = dapAn.ma;

                if (dapAn.la_dap_an_dung) {
                    // Trả lời đúng
                    icon = `<span class="ms-2 text-success">✔️</span>`;
                } else {
                    // Trả lời sai
                    classes.push("text-danger", "fw-bold");
                    icon = `<span class="ms-2 text-danger">❌</span>`;
                }
            }

            html += `<li class="${classes.join(" ")}">
                <strong>${dapAn.ma}.</strong> ${dapAn.noi_dung} ${icon}
            </li>`;
        });

        html += `</ul>
            <div class="mt-2">
                <small><strong>Đáp án đúng:</strong> ${dapAnDung}</small><br/>
                <small><strong>Đáp án chọn:</strong> ${
                    dapAnChon || "<i>Không chọn</i>"
                }</small>
            </div>
        </div>`;
    });

    return html;
}

function renderDanhSachKetQua(baiKiemTra, dsKetQua) {
    let html = "<table class='table table-bordered'>";
    html += `
        <thead>
            <tr>
                <th>STT</th>
                <th>Họ tên</th>
                <th>Email</th>
                <th>Điểm</th>
                <th>Chi tiết</th>
            </tr>
        </thead><tbody>`;

    dsKetQua.forEach((item, index) => {
        const user = item.sinh_vien;
        const ketQua = item.ket_qua;
        const idKetQua = ketQua?.id || 0;

        html += `
        <tr>
            <td>${index + 1}</td>
            <td>${user.ten}</td>
            <td>${user.email}</td>
            <td>${item.diem ?? "Chưa làm"}</td>
            <td>
                ${
                    item.diem !== null
                        ? `<button class="btn btn-sm btn-primary"
                            onclick="xemChiTietKetQua('${idKetQua}', '${user.ten}', ${item.diem}, '${baiKiemTra.tieu_de}')">
                            Xem
                        </button>`
                        : ""
                }
            </td>

        </tr>`;
    });

    html += "</tbody></table>";
    return html;
}

//Khi nhấn nút để xem danh sách kết quả của bài tập
function hienThiKetQua() {
    const html = renderDanhSachKetQua(currentBaiKiemTra, currentKetQuaList);
    $("#modalChiTiet .modal-body").html(html);
    // Thay nút đóng thành nút quay lại
    $("#modalChiTiet .modal-actions").html(`
        <button class="btn btn-secondary" onclick="quayLaiDanhSach(0)">
            ← Quay lại 
        </button>
    `);
}

//Khi nhấn nút để xem danh sách câu hỏi của bài tập
function hienThiCauHoi() {
    const dsCauHoi = currentBaiKiemTra.list_cau_hoi || [];

    if (dsCauHoi.length === 0) {
        $("#modalChiTiet .modal-body").html("<p>Không có câu hỏi nào.</p>");
        return;
    }

    let html = "<h5 class='mb-3'>Danh sách câu hỏi:</h5>";

    dsCauHoi.forEach((cau, index) => {
        html += `
            <div class="mb-3 border rounded p-3 bg-light">
                <strong>Câu ${index + 1}: ${cau.tieu_de}</strong>
                <ul class="mt-2">
                    <li>A. ${cau.dap_an_a}</li>
                    <li>B. ${cau.dap_an_b}</li>
                    <li>C. ${cau.dap_an_c}</li>
                    <li>D. ${cau.dap_an_d}</li>
                </ul>
                <p><strong>Đáp án đúng:</strong> ${cau.dap_an_dung}</p>
            </div>
        `;
    });
    // Thay nút đóng thành nút quay lại
    $("#modalChiTiet .modal-actions").html(`
        <button class="btn btn-secondary" onclick="quayLaiDanhSach(0)">
            ← Quay lại 
        </button>
    `);
    $("#modalChiTiet .modal-body").html(html);
}

function quayLaiDanhSach($action) {
    if ($action) {
        if (currentBaiKiemTra && currentKetQuaList) {
            const html = renderDanhSachKetQua(
                currentBaiKiemTra,
                currentKetQuaList
            );
            $("#modalChiTiet .modal-title").text(
                `Kết quả bài: ${currentBaiKiemTra.tieu_de}`
            );
            $("#modalChiTiet .modal-body").html(html);

            /// Thay nút đóng thành nút quay lại
            $("#modalChiTiet .modal-actions").html(`
                <button class="btn btn-secondary" onclick="quayLaiDanhSach(0)">
                    ← Quay lại 
                </button>
            `);
        }
    } else {
        if (currentBaiKiemTra && currentKetQuaList) {
            $("#modalChiTiet .modal-title").text(
                `Bài kiểm tra: ${currentBaiKiemTra.tieu_de}`
            );
            $("#modalChiTiet .modal-body").html(
                renderChiTietBaiKiemTraGiangVien()
            );

            // Khôi phục lại nút đóng mặc định
            $("#modalChiTiet .modal-actions").html(`
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        `);
        }
    }
}

function renderChiTietBaiKiemTraGiangVien() {
    const thongTinBaiKT = `
                        <p><strong>Bài kiểm tra:</strong> ${
                            currentBaiKiemTra.tieu_de
                        }</p>
                        <p><strong>Hạn chót nộp bài:</strong> ${
                            currentBaiKiemTra.ngay_ket_thuc
                        }</p>
                        <p><strong>Điểm tối đa:</strong> ${
                            currentBaiKiemTra.diem_toi_da
                        }</p>

                        <p><strong>Hình thức:</strong> ${
                            currentBaiKiemTra.hinh_thuc ?? "Trắc nghiệm"
                        }</p>
                    `;

    const nutChucNang = `
                        <div class="mt-3 d-flex justify-content-end gap-2">
                            <button class="btn btn-info" onclick="hienThiCauHoi()">Xem câu hỏi</button>
                            <button class="btn btn-primary" onclick="hienThiKetQua()">Kết quả</button>
                        </div>
                    `;
    return thongTinBaiKT + nutChucNang;
}

$(document).ready(function () {
    let danhSachBaiKiemTra = [];
    let baiKiemTra = "";
    let dsKetQua = "";

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
        $.ajax({
            url: `/bai-kiem-tra/${id}/chi-tiet`,
            method: "GET",
            success: function (res) {
                if (res.role == "sinh_vien") {
                    const baiKiemTra = res.bai_kiem_tra;
                    const ketQua = res.ket_qua;
                    const chiTiet = res.chi_tiet;

                    // ✅ Đếm số câu đúng
                    let soCauDung = 0;
                    let tongCau = 0;

                    // Nếu chưa làm bài => chỉ hiện nút làm bài
                    if (
                        !chiTiet ||
                        !chiTiet.cauHoiVaDapAn ||
                        chiTiet.cauHoiVaDapAn.length === 0
                    ) {
                        const tongCau = baiKiemTra.list_cau_hoi.length;
                        const ngayDenHan = baiKiemTra.ngay_ket_thuc
                            ? new Date(
                                  baiKiemTra.ngay_ket_thuc
                              ).toLocaleDateString("vi-VN")
                            : "Không có";

                        $("#modalChiTiet .modal-title").text(
                            baiKiemTra.tieu_de
                        );

                        const lamBaiUrl = `/lam-bai/${baiKiemTra.id}`;

                        $("#modalChiTiet .modal-body").html(`
                            <div class="text-center py-4">
                                <p><strong>Số câu hỏi:</strong> ${tongCau}</p>
                                <p><strong>Hạn cuối làm bài:</strong> ${ngayDenHan}</p>
                                <p><strong>Điểm tối đa:</strong> ${baiKiemTra.diem_toi_da}</p>
                                <p>Bạn chưa làm bài kiểm tra này.</p>
                                <a href="${lamBaiUrl}" class="btn btn-primary">
                                    Làm bài ngay
                                </a>
                            </div>
                        `);

                        $("#modalChiTiet").modal("show");
                    } else {
                        tongCau = chiTiet.cauHoiVaDapAn.length;

                        chiTiet.cauHoiVaDapAn.forEach((item) => {
                            item.danh_sach_dap_an.forEach((dapAn) => {
                                if (dapAn.la_dap_an_dung && dapAn.duoc_chon) {
                                    soCauDung++;
                                }
                            });
                        });
                        // ✅ Hiển thị tiêu đề modal gồm tiêu đề + số câu đúng / tổng câu
                        $("#modalChiTiet .modal-title").text(
                            `${baiKiemTra.tieu_de} - Kết quả: ${soCauDung}/${tongCau} câu đúng`
                        );

                        // ✅ Render nội dung chi tiết
                        $("#modalChiTiet .modal-body").html(
                            renderChiTietBaiKiemTra(baiKiemTra, chiTiet)
                        );

                        // ✅ Hiển thị modal
                        $("#modalChiTiet").modal("show");
                    }
                }
                if (res.role === "giang_vien") {
                    const baiKiemTra = res.bai_kiem_tra;
                    const dsKetQua = res.danh_sach_ket_qua;

                    // Reset object chi tiết
                    allChiTietTheoKetQua = {};
                    currentBaiKiemTra = baiKiemTra;
                    currentKetQuaList = dsKetQua;

                    dsKetQua.forEach((item) => {
                        if (item.ket_qua?.id) {
                            allChiTietTheoKetQua[item.ket_qua.id] =
                                item.chi_tiet;
                        }
                    });

                    $("#modalChiTiet .modal-title").text(
                        `Bài kiểm tra: ${baiKiemTra.tieu_de}`
                    );

                    $("#modalChiTiet .modal-body").html(
                        renderChiTietBaiKiemTraGiangVien()
                    );
                    $("#modalChiTiet .modal-actions").html(`
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    `);
                    $("#modalChiTiet").modal("show");
                }
            },
            error: function () {
                alert("Không lấy được dữ liệu bài kiểm tra.");
            },
        });
    }

    $(document).on("click", ".item-bai-kiem-tra", function () {
        const id = $(this).data("id");
        openModalChiTiet(id);
    });

    // Kích hoạt nút "Tạo mới bài tập" để mở modal này
    $("#addNewExerciseBtn").attr("data-bs-toggle", "modal");
    $("#addNewExerciseBtn").attr("data-bs-target", "#addExerciseModal");

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
                    Swal.fire({
                        icon: "success",
                        title: "Thành công",
                        text: response.message,
                        confirmButtonText: "Đóng",
                    }).then(() => {
                        $("#addExerciseModal").modal("hide");
                        $("#newExerciseForm")[0].reset();
                        $("#newExerciseForm").removeClass("was-validated");
                        $("#questionsFormContainer").empty();
                        $("#noQuestionsMessage").show();
                        questionCounter = 1;
                        // Cập nhật lại danh sách và hiển thị
                        danhSachBaiTap = response.data;
                        renderDanhSach(danhSachBaiTap);
                    });
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Tạo thất bại",
                        text: response.message,
                        confirmButtonText: "Đóng",
                    });
                }
            },
            error: function (xhr) {
                Swal.fire({
                    icon: "error",
                    title: "Lỗi server",
                    text: "Đã xảy ra lỗi phía server.",
                    confirmButtonText: "Đóng",
                });
                console.error(xhr.responseText);
            },
        });
    });

    //Nhấn chấp nhận yêu cầu tham gia lớp học
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

    //Nhấn từ chối yêu cầu tham gia lớp học
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

let listBaiTrongLop = {};

// Xử lý check tất cả bản ghi
$(document).on("change", ".check-all", function () {
    const isChecked = $(this).is(":checked");
    const tbody = $(this).parents("thead").next("tbody");

    tbody.find(".row-checkbox").prop("checked", isChecked);
});

handleCheckAllSelected($(".row-checkbox:checked"));

$(document).on("change", ".row-checkbox", function () {
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
        const tbody = $(element).parents("tbody");
        const thead = tbody.prev("thead");
        const total = tbody.find(".row-checkbox").length;
        const checked = tbody.find(".row-checkbox:checked").length;

        thead.find(".check-all").prop("checked", total === checked);
    });
}

$.ajaxSetup({
    headers: {
        "X-CSRF-TOKEN": $('#csrfForm input[name="_token"]').val(),
    },
});

$(document).on("click", ".btn-public-bai", function (e) {
    const checkbox = $(".row-checkbox");
    checkbox.each(function (index, element) {
        const idBai = parseInt($(element).data("id"));
        const congKhai = $(element).is(":checked") ? "1" : "0";

        listBaiTrongLop[idBai] = congKhai;
    });

    const url = window.location.pathname;

    $.ajax({
        url: `${url}/bai/cong-khai`,
        type: "POST",
        data: {
            listBaiTrongLop: listBaiTrongLop,
        },
        dataType: "json",
        success: function (response) {
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
                icon: response.icon,
                title: response.message
            });

            $('#accordion-chuong').html(response.html);
            $('#lecture-tab>span').text(response.tongSoBaiCongKhai);
        },
        error: function (xhr) {
            alert("Đã xảy ra lỗi: " + xhr.status + " " + xhr.statusText);
        },
    });
});

let banTinCache = {};

// Xử lý modal chỉnh sửa bản tin
$(document).on("click", ".btn-update-ban-tin", function () {
    const urlDetail = $(this).data("url-detail");
    const urlUpdate = $(this).data("url-update");
    const form = $("#modal-chinh-sua-ban-tin").parents("form");

    if (banTinCache[urlDetail]) {
        const banTin = banTinCache[urlDetail];

        tinymce.get("noi-dung-ban-tin-chinh-sua").setContent(banTin.noi_dung);
        form.attr("action", urlUpdate);
        $("#modal-chinh-sua-ban-tin").modal("show");
        return;
    }

    $.ajax({
        url: urlDetail,
        type: "POST",
        dataType: "json",
        success: function (response) {
            const banTin = response.data;
            banTinCache[urlDetail] = banTin;

            tinymce
                .get("noi-dung-ban-tin-chinh-sua")
                .setContent(banTin.noi_dung);
            form.attr("action", urlUpdate);
            $("#modal-chinh-sua-ban-tin").modal("show");
        },
        error: function (xhr) {
            alert("Đã xảy ra lỗi: " + xhr.status + " " + xhr.statusText);
        },
    });
});

// Xử lý xóa dữ liệu khi modal ẩn
$('#modal-them-ban-tin').on('hidden.bs.modal', function () {
    tinymce.get('noi-dung-ban-tin-them').setContent('');
})

$('#modal-chinh-sua-ban-tin').on('hidden.bs.modal', function () {
    tinymce.get('noi-dung-ban-tin-chinh-sua').setContent('');
    $('#modal-chinh-sua-ban-tin').parents('form').attr('action', '');
})




// Bản tin
$(document).on('submit', '.form-reply', function (e) {
    e.preventDefault();

    const form = $(this);
    const actionUrl = form.attr('action');
    const noiDung = form.find('input[name="noi_dung"]').val();
    const token = $('meta[name="csrf-token"]').attr('content');

    // Optional: disable button trong khi gửi
    const btn = form.find('button[type="submit"]');
    btn.prop('disabled', true).text('Đang gửi...');

    $.ajax({
        url: actionUrl,
        type: 'POST',
        data: {
            _token: token,
            noi_dung: noiDung
        },
        success: function (response) {
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
                icon: response.icon,
                title: response.message
            });

            $('#wp-list-ban-tin').html(response.html);

            // Reset form
            form[0].reset();
        },
        error: function (xhr) {
            alert('Đã xảy ra lỗi: ' + xhr.status + ' ' + xhr.statusText);
        },
        complete: function () {
            // Kích hoạt lại nút
            btn.prop('disabled', false).text('Gửi');
        }
    });
});
