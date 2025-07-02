var questionCounter = 0;

var dsTieuDe = [];

let allChiTietTheoKetQua = {};

document.addEventListener("DOMContentLoaded", function () {
    //Cấu hình Flatpickr - START
    flatpickr.localize(flatpickr.l10ns.vn);
    const startPicker = flatpickr("#startTime", {
        enableTime: true,
        dateFormat: "d/m/Y H:i",
        time_24hr: true,
        allowInput: true,
        onChange: function (selectedDates, dateStr, instance) {
            // Gợi ý thời gian kết thúc ≥ thời gian bắt đầu
            if (selectedDates.length > 0) {
                const startDate = selectedDates[0];
                endPicker.set("minDate", startDate);
            }
        },
    });
    const endPicker = flatpickr("#endTime", {
        enableTime: true,
        dateFormat: "d/m/Y H:i",
        time_24hr: true,
        allowInput: true,
    });
    //Cấu hình Flatpickr - END

    //Câu hỏi - START
    // Hàm thêm câu hỏi (thủ công và file)
    function addQuestion(
        questionText = "",
        answers = ["", "", "", ""],
        correct = ""
    ) {
        questionCounter++;
        $("div[id|='questionsFormContainer']").append(
            questionTemplate(questionCounter, questionText, answers, correct)
        );
        updateQuestionNumbers();
    }

    //Cập nhật lại thứ tự câu hỏi cho đúng khi thêm mới hoặc xóa
    function updateQuestionNumbers() {
        const $questionItems = $(
            "div[id|='questionsFormContainer'] .question-item"
        );
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
        console.log(questionCounter);
    }

    //Nhấn nút thêm câu hỏi
    $(document).on("click", "#addQuestionBtn", function () {
        addQuestion();
    });

    //Nhấn nút xóa câu hỏi
    $(document).on("click", ".remove-question-btn", function () {
        const $toRemove = $(this).closest(".question-item");
        if ($("div[id|='questionsFormContainer'] .question-item").length > 1) {
            $toRemove.remove();
            updateQuestionNumbers();
        } else {
            alert("Bạn phải có ít nhất một câu hỏi.");
        }
    });

    //Đọc file được chọn và truyền dữ liệu để tạo html hiển thị danh sách câu hỏi từ file
    $(document).on("change", "#excelFileInput", function (e) {
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
                        A: "A",
                        B: "B",
                        C: "C",
                        D: "D",
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
    //Câu hỏi - END

    //Modal thêm Bài kiểm tra - START
    $("#addNewExerciseBtn").attr("data-bs-toggle", "modal");
    $("#addNewExerciseBtn").attr("data-bs-target", "#addExerciseModal");

    //Khi mở modal thêm bài kiểm tra
    $("#addExerciseModal").on("show.bs.modal", function () {
        $("#newExerciseForm")[0].reset();
        $("#newExerciseForm").removeClass("was-validated");
        $("div[id|='questionsFormContainer']").empty();
        questionCounter = 0;
        $("#noQuestionsMessage").show();
    });
    //Modal thêm Bài kiểm tra - END
});

document
    .querySelector("#newExerciseTitle")
    ?.addEventListener("input", function () {
        const val = this.value.toLowerCase().trim();
        const msg = this.nextElementSibling;

        const isDuplicate = dsTieuDe.includes(val);
        this.classList.toggle("is-invalid", isDuplicate);
        msg.textContent = isDuplicate
            ? "Lớp học đã có bài kiểm tra với tiêu đề này rồi!"
            : "Vui lòng nhập tiêu đề cho bài kiểm tra.";
    });

document.addEventListener("input", function (e) {
    if (e.target && e.target.id === "ExerciseTitle") {
        const giaTriNhap = e.target.value.toLowerCase().trim();

        if (dsTieuDe.includes(giaTriNhap)) {
            e.target.classList.add("is-invalid");
            e.target.nextElementSibling.textContent =
                "Lớp học đã có bài kiểm tra với tiêu đề này rồi!";
        } else {
            e.target.classList.remove("is-invalid");
            e.target.nextElementSibling.textContent =
                "Vui lòng nhập tiêu đề cho bài kiểm tra.";
        }
    }
});

// Khi người dùng chọn thời gian bắt đầu
$("#startTime").on("change input", function () {
    if ($(this).val().trim() !== "") {
        $(this).removeClass("is-invalid");
        $(this).next(".invalid-feedback").hide();
    }
});

// Khi người dùng chọn thời gian kết thúc
$("#endTime").on("change input", function () {
    if ($(this).val().trim() !== "") {
        $(this).removeClass("is-invalid");
        $(this).next(".invalid-feedback").hide();
    }
});

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

//Khi nhấn nút để xem danh sách kết quả của bài kiểm tra
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

//Khi nhấn nút để xem danh sách câu hỏi của bài kiểm tra
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

function formatNgay(ngayGoc) {
    const date = new Date(ngayGoc);
    const gio = String(date.getHours()).padStart(2, "0");
    const phut = String(date.getMinutes()).padStart(2, "0");
    const ngay = String(date.getDate()).padStart(2, "0");
    const thang = String(date.getMonth() + 1).padStart(2, "0");
    const nam = date.getFullYear();

    return `${gio}:${phut} Ngày: ${ngay}/${thang}/${nam}`;
}

function formatDateForFlatpickr(dateTimeStr) {
    const date = new Date(dateTimeStr); // Tự parse từ "Y-m-d H:i:s"
    const day = String(date.getDate()).padStart(2, "0");
    const month = String(date.getMonth() + 1).padStart(2, "0"); // Tháng bắt đầu từ 0
    const year = date.getFullYear();
    const hour = String(date.getHours()).padStart(2, "0");
    const minute = String(date.getMinutes()).padStart(2, "0");

    return `${day}/${month}/${year} ${hour}:${minute}`;
}

function renderChiTietBaiKiemTraGiangVien() {
    const thongTinBaiKT = `
                        <p><strong>Bài kiểm tra:</strong> ${
                            currentBaiKiemTra.tieu_de
                        }</p>
                        <p><strong>Hạn chót nộp bài:</strong> ${formatNgay(
                            currentBaiKiemTra.ngay_ket_thuc
                        )}</p>
                        
                        <p><strong>Điểm tối đa:</strong> ${
                            currentBaiKiemTra.diem_toi_da
                        }</p>

                        <p><strong>Hình thức:</strong> ${
                            currentBaiKiemTra.hinh_thuc ?? "Trắc nghiệm"
                        }</p>
                        <p><strong>Số câu hỏi:</strong> ${
                            currentBaiKiemTra.list_cau_hoi.length
                        }</p>
                    `;

    const nutChucNang = `
                        <div class="mt-3 d-flex justify-content-end gap-2">
                            <button class="btn btn-info" onclick="hienThiCauHoi()">Xem câu hỏi</button>
                            <button class="btn btn-info" onclick="chuyenSangChinhSua()">Chỉnh sửa nội dung</button>
                            <button class="btn btn-primary" onclick="hienThiKetQua()">Kết quả</button>
                        </div>
                    `;
    return thongTinBaiKT + nutChucNang;
}

//Mẫu html để load dữ liệu vào
const questionTemplate = (
    count,
    questionText = "",
    answerOptions = ["", "", "", ""],
    correctAnswer = "",
    questionID = ""
) => {
    const optionLabels = ["A", "B", "C", "D"];
    const optionValues = ["A", "B", "C", "D"];
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
                <input type="hidden" name="idCauHoi" id="idCauHoi"
                    value="${questionID}">
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

const chuyenSangChinhSua = () => {
    const body = document.getElementById("modalChiTietBody");

    let html = `<form id="editExerciseForm">
                    <input type="hidden" name="idBaiKiemTra" id="idBaiKiemTra"
                        value="${currentBaiKiemTra.id}">
                    <div class="row mb-3">
                        <div class="col-sm-9 col-lg-10">
                            <label for="ExerciseTitle" class="form-label">Tiêu đề
                                <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="tieu_de"
                                id="ExerciseTitle" value="${
                                    currentBaiKiemTra.tieu_de
                                }" placeholder="Nhập tiêu đề bài kiểm tra" required>
                            <div class="invalid-feedback fw-bold">
                            Vui lòng nhập tiêu đề cho bài kiểm tra.
                            </div>
                        </div>
                        <div class="col-sm-3 col-lg-2 mt-3 mt-sm-0"> <label
                            for="ExerciseMaxScore" class="form-label">Điểm tối
                            đa</label>
                            <input type="number" class="form-control" name="diem_toi_da" value="${
                                currentBaiKiemTra.diem_toi_da
                            }"
                            id="ExerciseMaxScore" placeholder="100" min="0">
                            <div class="invalid-feedback fw-bold">
                            Vui lòng nhập tiêu đề cho bài kiểm tra.
                            </div>
                        </div>
                        <div class="row mb-3 mt-3 ">
                                                    <div class="col-md-6">
                                                        <label for="editStartTime">Thời gian bắt đầu</label>
                                                        <input type="text" class="form-control" id="editStartTime"
                                                            name="ngay_bat_dau" 
                                                         placeholder="Chọn thời gian">
                                                        <div class="invalid-feedback fw-bold">
                                                            Vui lòng chọn thời gian bắt đầu
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="editEndTime">Thời gian kết thúc</label>
                                                        <input type="text" class="form-control" id="editEndTime"
                                                            name="ngay_ket_thuc" placeholder="Chọn thời gian">
                                                        <div class="invalid-feedback fw-bold">
                                                            Vui lòng chọn thời gian kết thúc
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="d-flex justify-content-center mt-1">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" value="1"
                                                            id="editChoPhepNopTre" name="cho_phep_nop_qua_han"  ${
                                                                currentBaiKiemTra.cho_phep_nop_qua_han
                                                                    ? "checked"
                                                                    : ""
                                                            }>
                                                        <label class="form-check-label" for="choPhepNopTre">
                                                            Cho phép nộp quá hạn
                                                        </label>
                                                    </div>
                                                </div>
                    </div>
                    <div id="questionsFormContainer-sua">`;
    questionCounter = 0;
    // Tạo HTML từ template
    currentBaiKiemTra.list_cau_hoi.forEach((q, index) => {
        questionCounter++;
        html += questionTemplate(
            questionCounter,
            q.tieu_de,
            [q.dap_an_a, q.dap_an_b, q.dap_an_c, q.dap_an_d],
            q.dap_an_dung,
            q.id
        );
    });
    html += `</div>
            <div class="d-flex gap-2 mb-3">
                <!-- Nút Thêm câu hỏi mới -->
                <button type="button" class="btn btn-outline-primary flex-fill"
                    id="addQuestionBtn">
                    <i class="fas fa-plus me-2"></i>Thêm câu hỏi mới
                </button>

                
                <label class="btn btn-outline-secondary flex-fill m-0"
                for="excelFileInput">
                    <i class="fas fa-file-excel me-2"></i>Chọn file Excel
                </label>
                <input type="file" id="excelFileInput" accept=".xlsx, .xls"
                    class="d-none">
            </div>
            <div class="text-end mt-3">
                <button type="submit" class="btn btn-success">Lưu thay đổi</button>
            </div>
        </form>`;
    $("#modalChiTiet .modal-actions").html(`
        <button class="btn btn-secondary" onclick="quayLaiDanhSach(0)">
            ← Quay lại 
        </button>
    `);

    // Gán lại vào modal-body
    body.innerHTML = html;

    //Cấu hình Flatpickr - START
    flatpickr.localize(flatpickr.l10ns.vn);
    const editStartPicker = flatpickr("#editStartTime", {
        enableTime: true,
        dateFormat: "d-m-Y H:i:S",
        time_24hr: true,
        allowInput: true,
        defaultDate: formatDateForFlatpickr(currentBaiKiemTra.ngay_bat_dau),
        onChange: function (selectedDates, dateStr, instance) {
            // Gợi ý thời gian kết thúc ≥ thời gian bắt đầu
            if (selectedDates.length > 0) {
                const startDate = selectedDates[0];
                endPicker.set("minDate", startDate);
            }
        },
    });
    const editEndPicker = flatpickr("#editEndTime", {
        enableTime: true,
        dateFormat: "d-m-Y H:i:S",
        time_24hr: true,
        allowInput: true,
        defaultDate: formatDateForFlatpickr(currentBaiKiemTra.ngay_ket_thuc),
    });
    //Cấu hình Flatpickr - END

    fetch("/server-time")
        .then((response) => response.json())
        .then((data) => {
            const serverNow = new Date(data.now);

            const startTimeStr = currentBaiKiemTra.ngay_bat_dau; // "2025-06-30 15:00:00"
            const startTime = new Date(startTimeStr); // new Date("2025-06-30 15:00:00")

            disableEditingFields(serverNow, startTime);
        });
};

function disableEditingFields(serverNow, startTime) {
    if (serverNow >= startTime) {
        // Disable các input/textarea không được sửa
        document
            .querySelectorAll(
                "#editExerciseForm input, #editExerciseForm textarea"
            )
            .forEach((input) => {
                if (
                    !input.matches("#editEndTime") &&
                    !input.matches("#editChoPhepNopTre") &&
                    !input.classList.contains("correct-answer-radio")
                ) {
                    input.setAttribute("readonly", true);
                    input.classList.add("bg-light");
                }
            });

        // Disable nút xóa câu hỏi
        document.querySelectorAll(".remove-question-btn").forEach((btn) => {
            btn.disabled = true;
            btn.classList.add("disabled");
        });

        // Disable nút thêm câu hỏi
        document.getElementById("addQuestionBtn").disabled = true;
        document
            .querySelector('label[for="excelFileInput"]')
            .classList.add("disabled");
        document.getElementById("excelFileInput").disabled = true;
    }
}

$(document).ready(function () {
    let danhSachBaiKiemTra = [];
    let baiKiemTra = "";
    let dsKetQua = "";

    const infoLopHoc = document.getElementById("info-lop-hoc");
    const idLopHoc = infoLopHoc.dataset.idLopHoc; // Truyền từ Blade

    //Load dữ liệu
    $.ajax({
        url: `/bai-kiem-tra/${idLopHoc}`,
        method: "GET",
        success: function (data) {
            danhSachBaiKiemTra = data;
            renderDanhSach(danhSachBaiKiemTra);
            dsTieuDe = danhSachBaiKiemTra.map((item) =>
                item.tieu_de.toLowerCase().trim()
            );
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
                                <a href="${lamBaiUrl}" target="_blank" class="btn btn-primary">
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
                    questionCounter = 0;
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

    // Xử lý nhấn nút Lưu bài kiểm tra Trong Modal thêm bài kiểm tra
    $("#newExerciseForm").on("submit", function (e) {
        e.preventDefault();

        if (!this.checkValidity()) {
            e.stopPropagation();
            $(this).addClass("was-validated");
            return;
        }

        if (
            $("div[id|='questionsFormContainer'] .question-item").length === 0
        ) {
            $("#noQuestionsMessage").removeClass("d-none"); // Hiện thông báo
            return;
        } else {
            $("#noQuestionsMessage").addClass("d-none"); // Ẩn khi có câu hỏi
        }

        let errorMessages = [];

        const startTime = $("#startTime").val();
        const endTime = $("#endTime").val();

        let isValid = true;

        if (!startTime) {
            $("#startTime").addClass("is-invalid");
            $("#startTime").next(".invalid-feedback").show();
            errorMessages.push(`Vui lòng chọn thời gian bắt đầu!!!`);
            isValid = false;
        } else {
            $("#startTime").removeClass("is-invalid");
            $("#startTime").next(".invalid-feedback").hide();
        }

        if (!endTime) {
            $("#endTime").addClass("is-invalid");
            $("#endTime").next(".invalid-feedback").show();
            errorMessages.push(`Vui lòng chọn thời gian kết thúc!!!`);
            isValid = false;
        } else {
            $("#endTime").removeClass("is-invalid");
            $("#endTime").next(".invalid-feedback").hide();
        }

        const start = new Date(startTime);
        const end = new Date(endTime);

        if (isValid) {
            const start = new Date(startTime);
            const end = new Date(endTime);

            // kiểm tra nếu thời gian kết thúc < bắt đầu
            if (end < start) {
                $("#endTime").addClass("is-invalid");
                $("#endTime")
                    .next(".invalid-feedback")
                    .text("Thời gian kết thúc phải sau thời gian bắt đầu")
                    .show();
                errorMessages.push(`Thời gian kết thúc vô lí!!!`);
                isValid = false;
            }
        }
        // if (start > end) {
        //     Swal.fire({
        //         icon: "warning",
        //         title: "Thời gian không hợp lệ",
        //         text: "Thời gian kết thúc phải sau hoặc bằng thời gian bắt đầu.",
        //         confirmButtonText: "Đóng",
        //     });
        //     return;
        // }

        const newExercise = {
            tieuDe: $("#newExerciseTitle").val(),
            diemToiDa: $("#newExerciseMaxScore").val()
                ? parseInt($("#newExerciseMaxScore").val())
                : null,
            idLopHoc: $("#idLopHoc").val(),
            thoiGianBatDau: startTime,
            thoiGianKetThuc: endTime,
            choPhepNopTre: $("#choPhepNopTre").is(":checked"),
            danhSachCauHoi: [],
        };
        // console.log(newExercise);

        $("div[id|='questionsFormContainer'] .question-item").each(function (
            index
        ) {
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
                            `.correct-answer-radio[value="${String.fromCharCode(
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
                errorMessages.push(
                    `• Câu hỏi ${
                        index + 1
                    }: Thiếu nội dung hoặc chưa chọn đáp án đúng.`
                );
                isValid = false;
            }

            newExercise.danhSachCauHoi.push({
                cauHoi: cauHoi,
                danhSachDapAn: danhSachDapAn,
                dapAnDuocChon: dapAnDuocChon,
            });
        });

        if (!isValid) {
            Swal.fire({
                icon: "error",
                title: "Lỗi khi tạo bài kiểm tra",
                html: errorMessages.join("<br>"),
                confirmButtonText: "Đóng",
            });
            return;
        }

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
                        $("div[id|='questionsFormContainer']").empty();
                        $("#noQuestionsMessage").show();
                        questionCounter = 0;
                        // Cập nhật lại danh sách và hiển thị
                        danhSachBaiTap = response.data;
                        renderDanhSach(danhSachBaiTap);
                    });
                } else {
                    if (response.errors) {
                        Object.keys(response.errors).forEach((fieldName) => {
                            const input = document.querySelector(
                                `[name="${fieldName}"]`
                            );
                            const feedback = input?.nextElementSibling;

                            if (input) {
                                input.classList.add("is-invalid");

                                if (
                                    feedback &&
                                    response.errors[fieldName]?.[0]
                                ) {
                                    feedback.textContent =
                                        response.errors[fieldName][0];
                                }
                            }
                        });

                        // dsTieuDe = response.danh_sach_tieu_de.map((t) =>
                        //     t.toLowerCase().trim()
                        // );
                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "Tạo thất bại",
                            text: response.message,
                            confirmButtonText: "Đóng",
                        });
                    }
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

        Swal.fire({
            title: "Bạn có chắc chắn chấp nhận yêu cầu này không?",
            // text: '',
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Chấp nhận",
            cancelButtonText: "Hủy",
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/thanh-vien-lop/${id}/chap-nhan`,
                    method: "POST",
                    data: {
                        _token: $('meta[name="csrf-token"]').attr("content"),
                    },
                    success: function (res) {
                        if (res.status) {
                            $("#list-thanh-vien").html(res.html);
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
            }
        });
    });

    //Nhấn từ chối yêu cầu tham gia lớp học
    $(".btn-reject-request").click(function () {
        let id = $(this).data("id");

        Swal.fire({
            title: "Bạn có chắc chắn từ chối yêu cầu này không?",
            // text: '',
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Từ chối",
            cancelButtonText: "Hủy",
        }).then((result) => {
            if (result.isConfirmed) {
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
            }
        });
    });

    document.addEventListener("submit", function (e) {
        if (e.target && e.target.id === "editExerciseForm") {
            e.preventDefault();

            const form = e.target;
            const tieuDe = form.querySelector("#ExerciseTitle").value;
            const diemToiDa = form.querySelector("#ExerciseMaxScore").value;
            const idBaiKiemTra = form.querySelector("#idBaiKiemTra").value;
            const ngayBatDau = form.querySelector("#editStartTime").value;
            const ngayKetThuc = form.querySelector("#editEndTime").value;
            const choPhepNopTre = $("#editChoPhepNopTre").is(":checked");

            // Thu thập câu hỏi hiện tại từ giao diện
            const questionEls = form.querySelectorAll(".question-item");
            const questions = [];
            const currentQuestionIDs = [];

            questionEls.forEach((qEl, index) => {
                const id =
                    qEl.querySelector('input[name="idCauHoi"]')?.value || null;
                const noiDung = qEl.querySelector(".question-text").value;
                const answerInputs = qEl.querySelectorAll(".answer-option");
                const answers = Array.from(answerInputs).map((i) => i.value);

                const correct = qEl.querySelector(
                    ".correct-answer-radio:checked"
                )?.value;

                const question = {
                    id: id,
                    tieu_de: noiDung,
                    dap_an_a: answers[0],
                    dap_an_b: answers[1],
                    dap_an_c: answers[2],
                    dap_an_d: answers[3],
                    dap_an_dung: correct,
                };
                questions.push(question);

                if (id) currentQuestionIDs.push(id); // để so sánh xem câu nào bị xóa
            });

            // Xác định ID các câu hỏi cũ bị xóa (không còn trong DOM)
            const deletedQuestionIDs = currentBaiKiemTra.list_cau_hoi
                .map((q) => q.id.toString())
                .filter((id) => !currentQuestionIDs.includes(id));

            const data = {
                id: idBaiKiemTra,
                tieu_de: tieuDe,
                diem_toi_da: diemToiDa,
                ngay_bat_dau: ngayBatDau,
                ngay_ket_thuc: ngayKetThuc,
                cho_phep_nop_qua_han: choPhepNopTre,
                cau_hoi_cap_nhat: questions.filter((q) => q.id),
                cau_hoi_moi: questions.filter((q) => !q.id),
                cau_hoi_xoa: deletedQuestionIDs,
            };
            console.log(data);

            $.ajax({
                url: "/bai-kiem-tra",
                method: "PUT",
                contentType: "application/json",
                data: JSON.stringify(data),

                success: function (response) {
                    if (response.success) {
                        Swal.fire({
                            icon: "success",
                            title: "Thành công",
                            text: response.message,
                            confirmButtonText: "Đóng",
                        }).then(() => {
                            questionCounter = 0;
                            currentBaiKiemTra = response.data;
                            quayLaiDanhSach(0);
                        });
                    } else {
                        if (response.error === "tieu_de") {
                            const input = $(`[name="tieu_de"]`);
                            input.addClass("is-invalid");
                            input
                                .next(".invalid-feedback")
                                .text(response.message)
                                .show();
                            dsTieuDe = response.danh_sach_tieu_de;
                        }
                        Swal.fire({
                            icon: "error",
                            title: "Tạo thất bại",
                            text: response.message,
                            confirmButtonText: "Đóng",
                        });
                    }
                },
                error: function (xhr) {
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;

                        // Xóa lỗi cũ
                        $("#editExerciseForm .is-invalid").removeClass(
                            "is-invalid"
                        );
                        $("#editExerciseForm .invalid-feedback").hide();

                        // Hiển thị lỗi mới
                        for (let name in errors) {
                            const input = $(`[name="${name}"]`);
                            input.addClass("is-invalid");
                            input
                                .next(".invalid-feedback")
                                .text(errors[name][0])
                                .show();
                        }

                        Swal.fire({
                            icon: "error",
                            title: "Lỗi dữ liệu",
                            text: "Vui lòng kiểm tra lại các trường đã nhập.",
                            confirmButtonText: "Đóng",
                        });
                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "Lỗi hệ thống",
                            text: "Đã xảy ra lỗi không mong muốn.",
                            confirmButtonText: "Đóng",
                        });
                    }
                },
            });
        }
    });

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
                position: "top-end",
                width: "auto",
                showConfirmButton: false,
                timer: 3500,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.onmouseenter = Swal.stopTimer;
                    toast.onmouseleave = Swal.resumeTimer;
                },
            });

            Toast.fire({
                icon: response.icon,
                title: response.message,
            });

            $("#accordion-chuong").html(response.html);
            $("#lecture-tab>span").text(response.tongSoBaiCongKhai);
        },
        error: function (xhr) {
            alert("Đã xảy ra lỗi: " + xhr.status + " " + xhr.statusText);
        },
    });
});

let banTinCache = {};

// Xử lý click nút chỉnh sửa bản tin
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
            form.data("url-detail", urlDetail);
            $("#modal-chinh-sua-ban-tin").modal("show");
        },
        error: function (xhr) {
            alert("Đã xảy ra lỗi: " + xhr.status + " " + xhr.statusText);
        },
    });
});

// Xử lý model thêm bản tin
$("#modal-them-ban-tin")
    .parents("form")
    .on("submit", function (e) {
        e.preventDefault();

        const form = $(this);
        const actionUrl = form.attr("action");
        const noiDung = tinymce.get("noi-dung-ban-tin-them").getContent();
        const token = $('meta[name="csrf-token"]').attr("content");

        // console.log(noiDung);

        $.ajax({
            url: actionUrl,
            type: "POST",
            data: {
                _token: token,
                noi_dung: noiDung,
            },
            dataType: "json",
            success: function (response) {
                const Toast = Swal.mixin({
                    toast: true,
                    position: "top-end",
                    width: "auto",
                    showConfirmButton: false,
                    timer: 3500,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.onmouseenter = Swal.stopTimer;
                        toast.onmouseleave = Swal.resumeTimer;
                    },
                });

                Toast.fire({
                    icon: response.icon,
                    title: response.message,
                });

                $("#wp-list-ban-tin").html(response.html);

                $("#modal-them-ban-tin").modal("hide");

                // Reset form
                form[0].reset();
            },
            error: function (xhr) {
                alert("Đã xảy ra lỗi: " + xhr.status + " " + xhr.statusText);
            },
        });
    });

// Xử lý model chỉnh sửa bản tin
$("#modal-chinh-sua-ban-tin")
    .parents("form")
    .on("submit", function (e) {
        e.preventDefault();

        const form = $(this);
        const actionUrl = form.attr("action");
        const noiDung = tinymce.get("noi-dung-ban-tin-chinh-sua").getContent();
        const token = $('meta[name="csrf-token"]').attr("content");

        $.ajax({
            url: actionUrl,
            type: "PUT",
            data: {
                _token: token,
                noi_dung: noiDung,
            },
            dataType: "json",
            success: function (response) {
                const Toast = Swal.mixin({
                    toast: true,
                    position: "top-end",
                    width: "auto",
                    showConfirmButton: false,
                    timer: 3500,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.onmouseenter = Swal.stopTimer;
                        toast.onmouseleave = Swal.resumeTimer;
                    },
                });

                Toast.fire({
                    icon: response.icon,
                    title: response.message,
                });

                $("#wp-list-ban-tin").html(response.html);

                $("#modal-chinh-sua-ban-tin").modal("hide");

                // Reset form
                form[0].reset();

                delete banTinCache[form.data("url-detail")];
            },
            error: function (xhr) {
                alert("Đã xảy ra lỗi: " + xhr.status + " " + xhr.statusText);
            },
        });
    });

// Xử lý xóa dữ liệu khi modal ẩn
$("#modal-them-ban-tin").on("hidden.bs.modal", function () {
    tinymce.get("noi-dung-ban-tin-them").setContent("");
});

$("#modal-chinh-sua-ban-tin").on("hidden.bs.modal", function () {
    tinymce.get("noi-dung-ban-tin-chinh-sua").setContent("");
    $("#modal-chinh-sua-ban-tin").parents("form").attr("action", "");
});

// Xử lý xóa bản tin
$(document).on("click", ".btn-delete-ban-tin", function () {
    const urlDelete = $(this).data("url-delete");
    const type = $(this).data("type");

    Swal.fire({
        title: `Bạn có chắc chắn xóa ${type} này không?`,
        text: `Bạn sẽ không thể khôi phục ${type} này!`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Xóa",
        cancelButtonText: "Hủy",
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: urlDelete,
                type: "DELETE",
                dataType: "json",
                success: function (response) {
                    const Toast = Swal.mixin({
                        toast: true,
                        position: "top-end",
                        width: "auto",
                        showConfirmButton: false,
                        timer: 3500,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.onmouseenter = Swal.stopTimer;
                            toast.onmouseleave = Swal.resumeTimer;
                        },
                    });

                    Toast.fire({
                        icon: response.icon,
                        title: response.message,
                    });

                    $("#wp-list-ban-tin").html(response.html);

                    $("#modal-chinh-sua-ban-tin").modal("hide");
                },
                error: function (xhr) {
                    alert(
                        "Đã xảy ra lỗi: " + xhr.status + " " + xhr.statusText
                    );
                },
            });
        }
    });
});

$("#modal-chinh-sua-ban-tin").on("hidden.bs.modal", function () {
    tinymce.get("noi-dung-ban-tin-chinh-sua").setContent("");
    $("#modal-chinh-sua-ban-tin").parents("form").attr("action", "");
});

// Phản hồi bản tin
$(document).on("submit", ".form-reply", function (e) {
    e.preventDefault();

    const form = $(this);
    const actionUrl = form.attr("action");
    const noiDung = form.find('input[name="noi_dung"]').val();
    const token = $('meta[name="csrf-token"]').attr("content");

    // Optional: disable button trong khi gửi
    const btn = form.find('button[type="submit"]');
    btn.prop("disabled", true).text("Đang gửi...");

    $.ajax({
        url: actionUrl,
        type: "POST",
        data: {
            _token: token,
            noi_dung: noiDung,
        },
        success: function (response) {
            const Toast = Swal.mixin({
                toast: true,
                position: "top-end",
                width: "auto",
                showConfirmButton: false,
                timer: 3500,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.onmouseenter = Swal.stopTimer;
                    toast.onmouseleave = Swal.resumeTimer;
                },
            });

            Toast.fire({
                icon: response.icon,
                title: response.message,
            });

            $("#wp-list-ban-tin").html(response.html);

            // Reset form
            form[0].reset();
        },
        error: function (xhr) {
            alert("Đã xảy ra lỗi: " + xhr.status + " " + xhr.statusText);
        },
        complete: function () {
            // Kích hoạt lại nút
            btn.prop("disabled", false).text("Gửi");
        },
    });
});

$(document).on("click", ".btn-update-phan-hoi", function () {
    const idFormReply = $(this).data("form-reply");
    const idFormUpdateReply = $(this).data("form-update-reply");
    const formReply = $(idFormReply);
    const formUpdateReply = $(idFormUpdateReply);
    const urlUpdate = $(this).data("url-update");
    const noiDungPhanHoi = $(this)
        .parents(".child-news-action-btn")
        .prev(".noi-dung-phan-hoi")
        .text();

    // Toggle 2 form phản hồi và cập nhật phản hồi
    formReply.css("display", "none");
    formUpdateReply.css("display", "block");

    // Gán action form cập nhật
    formUpdateReply.attr("action", urlUpdate);
    // Gán giá trị input nội dung
    formUpdateReply.find('input[name="noi_dung"]').val(noiDungPhanHoi);
});

$(document).on("click", ".btn-cancel-update-reply", function () {
    const idFormReply = $(this).data("form-reply");
    const formReply = $(idFormReply);

    formReply.css("display", "block");

    $(this).prev('input[name="noi_dung"]').val("");
    $(this).parents('form[id|="form-update-reply"]').css("display", "none");
});

$(document).on("submit", 'form[id|="form-update-reply"]', function (e) {
    e.preventDefault();

    const urlUpdate = $(this).attr("action");
    const noiDung = $(this).find('input[name="noi_dung"]').val();
    const token = $('meta[name="csrf-token"]').attr("content");

    // alert($(this).attr('action'));

    $.ajax({
        url: urlUpdate,
        type: "PUT",
        data: {
            _token: token,
            noi_dung: noiDung,
        },
        dataType: "json",
        success: function (response) {
            // alert(response.message);
            const Toast = Swal.mixin({
                toast: true,
                position: "top-end",
                width: "auto",
                showConfirmButton: false,
                timer: 3500,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.onmouseenter = Swal.stopTimer;
                    toast.onmouseleave = Swal.resumeTimer;
                },
            });

            Toast.fire({
                icon: response.icon,
                title: response.message,
            });

            $("#wp-list-ban-tin").html(response.html);

            $(this).find('input[name="noi_dung"]').val("");
        },
        error: function (xhr) {
            alert("Đã xảy ra lỗi: " + xhr.status + " " + xhr.statusText);
        },
    });
});
