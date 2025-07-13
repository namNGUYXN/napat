//Biến lưu số câu hỏi khi tạo bài kiểm tra mới hoặc chỉnh sửa
var questionCounter = 0;

//Biến lưu tiêu đề bài kiểm tra đã có trong lớp học phần
var dsTieuDe = [];

//Danh sách chi tiết kết quả làm bài
let allChiTietTheoKetQua = {};

//Danh sách thống kê theo từng câu hỏi
let currentThongKe = [];

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

document.addEventListener("DOMContentLoaded", function () {
    //Cấu hình Flatpickr - START
    flatpickr.localize(flatpickr.l10ns.vn);
    const startPicker = flatpickr("#startTime", {
        enableTime: true,
        dateFormat: "d/m/Y H:i",
        time_24hr: true,
        allowInput: true,
        minDate: new Date(),
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

//Sự kiện khi nhập tên bài kiểm tra
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

function renderChiTietBaiKiemTraGiangVien() {
    const thongTinBaiKT = `
                         <div class="card p-3 shadow-sm rounded-3" style="font-family: 'Segoe UI', sans-serif; font-size: 16px;">
            <h5 class="mb-3"><i class="bi bi-journal-check me-2 text-primary"></i><strong>${
                currentBaiKiemTra.tieu_de
            }</strong></h5>
            <p><i class="bi bi-calendar-event me-2 text-secondary"></i><strong>Hạn chót nộp bài:</strong> ${formatNgay(
                currentBaiKiemTra.ngay_ket_thuc
            )}</p>
            <p><i class="bi bi-star me-2 text-warning"></i><strong>Điểm tối đa:</strong> ${
                currentBaiKiemTra.diem_toi_da
            }</p>
            <p><i class="bi bi-file-earmark-text me-2 text-info"></i><strong>Hình thức:</strong> ${
                currentBaiKiemTra.hinh_thuc ?? "Trắc nghiệm"
            }</p>
            <p><i class="bi bi-list-ol me-2 text-success"></i><strong>Số câu hỏi:</strong> ${
                currentBaiKiemTra.list_cau_hoi.length
            }</p>
        </div>
                    `;
    return thongTinBaiKT;
}

//Khi nhấn nút để xem danh sách câu hỏi của bài kiểm tra
function hienThiCauHoi() {
    const dsCauHoi = currentBaiKiemTra.list_cau_hoi || [];

    if (dsCauHoi.length === 0) {
        $("#modalChiTiet .modal-body").html(`
        <div class="alert alert-warning text-center">
            <i class="bi bi-exclamation-circle me-2"></i> Không có câu hỏi nào trong bài kiểm tra này.
        </div>
    `);
        return;
    }

    let html = "";

    dsCauHoi.forEach((cau, index) => {
        const dapAn = {
            A: cau.dap_an_a,
            B: cau.dap_an_b,
            C: cau.dap_an_c,
            D: cau.dap_an_d,
        };

        let listDapAnHtml = "";

        Object.entries(dapAn).forEach(([key, value]) => {
            const isCorrect = key === cau.dap_an_dung;
            listDapAnHtml += `
            <li class="${isCorrect ? "text-success fw-bold" : ""}">
                <span class="fw-semibold">${key}.</span> ${value}
                ${isCorrect ? '<i class="bi bi-check-circle ms-1"></i>' : ""}
            </li>
        `;
        });

        html += `
        <div class="mb-4 p-3 border rounded shadow-sm bg-white question-block" style="cursor: pointer;" data-id="${
            cau.id
        }">
            <h6 class="mb-2 text-primary fw-bold">
                <i class="bi bi-question-circle me-2"></i> Câu ${index + 1}: ${
            cau.tieu_de
        }
            </h6>
            <div class="ps-3">
                <ul class="list-unstyled">
                    ${listDapAnHtml}
                </ul>
                <div class="mt-2">
                    <span class="badge bg-success fw-bold">
                        <i class="bi bi-check-circle me-1"></i> Đáp án đúng: ${
                            cau.dap_an_dung
                        }
                    </span>
                </div>
            </div>
        </div>
    `;
    });
    // Thay nút đóng thành nút quay lại
    $("#modalChiTiet .modal-actions").html(`
        <button class="btn btn-outline-primary rounded-pill px-3 py-2 shadow-sm     d-flex  align-items-center gap-1"
                    onclick="quayLaiDanhSach(0)">
                    <i class="bi bi-arrow-left"></i> Quay lại
                </button>
    `);
    $("#modalChiTiet .modal-body").html(html);

    $("#modalChiTiet .modal-footer").html(`      
            `);
}

// Lưu trữ biểu đồ hiện tại
let currentChart = null;

function taoBangThongKe(thongKe, index) {
    const canvasId = `chart-thong-ke-${index}`;

    // HTML biểu đồ
    const chartHtml = `
        <div class="thong-ke-bieu-do mt-3" id="chart-container">
            <div style="position: relative; width: 100%; max-width: 400px; margin: auto; min-height: 250px;">
                <canvas id="${canvasId}"></canvas>
            </div>
        </div>
    `;

    setTimeout(() => {
        const ctx = document.getElementById(canvasId);

        // Nếu có biểu đồ trước đó thì huỷ trước khi vẽ biểu đồ mới
        if (currentChart) {
            currentChart.destroy();
            currentChart = null;
        }

        if (ctx) {
            currentChart = new Chart(ctx, {
                type: "pie",
                data: {
                    labels: ["Đúng", "Sai", "Không trả lời"],
                    datasets: [
                        {
                            data: [
                                thongKe.so_dung,
                                thongKe.so_sai,
                                thongKe.so_khong_tra_loi,
                            ],
                            backgroundColor: ["#28a745", "#dc3545", "#6c757d"],
                            borderColor: "#fff",
                            borderWidth: 1,
                        },
                    ],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    devicePixelRatio: 2,
                    plugins: {
                        legend: {
                            position: "bottom",
                            labels: {
                                font: {
                                    size: 16,
                                    weight: "bold",
                                },
                                color: "#000",
                            },
                        },
                        tooltip: {
                            backgroundColor: "#fff",
                            titleColor: "#000",
                            bodyColor: "#000",
                            borderColor: "#ccc",
                            borderWidth: 1,
                            titleFont: { size: 16, weight: "bold" },
                            bodyFont: { size: 14 },
                            callbacks: {
                                label: function (context) {
                                    const label = context.label || "";
                                    const value = context.raw;
                                    const total = thongKe.so_nguoi_dung;
                                    const percent = (
                                        (value / total) *
                                        100
                                    ).toFixed(1);
                                    return `${label}: ${value} (${percent}%)`;
                                },
                            },
                        },
                    },
                    layout: { padding: 10 },
                },
            });
        }
    }, 40);

    return chartHtml;
}

// Toggle biểu đồ khi bấm vào block câu hỏi
$(document).on("click", ".question-block", function () {
    const idCauHoi = $(this).data("id");
    const thongKe = currentThongKe.find((item) => item.id === idCauHoi);
    if (!thongKe) return;

    const $this = $(this);
    const isSameChart = $this.next("#chart-container").length > 0;

    // Xoá biểu đồ DOM và biểu đồ Chart.js
    $("#chart-container").remove();
    if (currentChart) {
        currentChart.destroy();
        currentChart = null;
    }

    // Nếu chưa hiển thị biểu đồ thì thêm mới
    if (!isSameChart) {
        const htmlThongKe = taoBangThongKe(thongKe, idCauHoi);
        $this.after(htmlThongKe);
    }
});

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

//Khi nhấn nút để xem danh sách kết quả của bài kiểm tra
function hienThiKetQua() {
    const html = renderDanhSachKetQua(currentBaiKiemTra, currentKetQuaList);
    $("#modalChiTiet .modal-body").html(html);
    // Thay nút đóng thành nút quay lại
    $("#modalChiTiet .modal-actions").html(`
        <button class="btn btn-outline-primary rounded-pill px-3 py-2 shadow-sm     d-flex  align-items-center gap-1"
                    onclick="quayLaiDanhSach(0)">
                    <i class="bi bi-arrow-left"></i> Quay lại
                </button>
    `);
    // Xử lý phần footer
    let footerHtml = "";

    if (currentBaiKiemTra.cong_khai) {
        footerHtml = `
            <div class="d-flex justify-content-between align-items-center w-100">
                <div class="text-success fw-bold d-flex align-items-center gap-1">
                    <i class="bi bi-check-circle-fill"></i> Đã công khai
                </div>
                <div></div>
            </div>
        `;
    } else {
        footerHtml = `
            <div class="d-flex justify-content-end w-100">
                <button class="btn btn-success rounded-pill px-3 py-2 shadow-sm d-flex align-items-center gap-1"
                        onclick="congKhaiKetQua(${currentBaiKiemTra.id})">
                    <i class="bi bi-globe"></i> Công khai kết quả
                </button>
            </div>
        `;
    }

    $("#modalChiTiet .modal-footer").html(footerHtml);
}

//Danh sách kết quả làm bài của các sinh viên trong lớp
function renderDanhSachKetQua(baiKiemTra, dsKetQua) {
    let html = `
    <div class="table-responsive">
        <table class="table table-bordered table-hover table-sm table-nowrap align-middle shadow-sm rounded">
            <thead class="table-primary text-center">
                <tr>
                    <th>STT</th>
                    <th>Họ tên</th>
                    <th>Email</th>
                    <th>Ngày nộp</th>
                    <th>Trạng thái</th>
                    <th>Điểm</th>
                    <th>Chi tiết</th>
                </tr>
            </thead>
            <tbody>`;

    dsKetQua.forEach((item, index) => {
        const user = item.sinh_vien;
        const ketQua = item.ket_qua;
        const idKetQua = ketQua?.id || 0;

        // Ngày nộp
        let ngayNop = `<span class="text-muted fst-italic">Chưa nộp</span>`;
        if (ketQua?.ngay_lam) {
            ngayNop = `<span class="text-dark">${formatNgay(
                ketQua.ngay_lam
            )}</span>`;
        }

        // Trạng thái nộp
        let trangThai = `<span class="badge bg-secondary">Chưa nộp</span>`;
        if (ketQua?.ngay_lam !== null && ketQua?.ngay_lam !== undefined) {
            if (ketQua.nop_qua_han === 1) {
                trangThai = `<span class="badge bg-danger"><i class="fas fa-clock me-1"></i> Nộp trễ</span>`;
            } else if (ketQua.nop_qua_han === 0) {
                trangThai = `<span class="badge bg-success"><i class="fas fa-check-circle me-1"></i> Đúng hạn</span>`;
            }
        }

        // Nút xem chi tiết
        const nutChiTiet =
            item.diem !== null
                ? `<button class="btn btn-sm btn-outline-primary"
                    onclick="xemChiTietKetQua('${idKetQua}', '${user.ten}', ${item.diem}, '${baiKiemTra.tieu_de}')"
                    title="Xem chi tiết bài làm">
                    <i class="fas fa-eye me-1"></i> Xem
                </button>`
                : `<span class="text-muted">-</span>`;

        html += `
        <tr>
            <td class="text-center fw-bold">${index + 1}</td>
            <td class="text-nowrap">${user.ten}</td>
            <td class="text-nowrap">${user.email}</td>
            <td class="text-center">${ngayNop}</td>
            <td class="text-center">${trangThai}</td>
            <td class="text-center fw-semibold">${item.diem ?? "-"}</td>
            <td class="text-center">${nutChiTiet}</td>
        </tr>`;
    });

    html += `
            </tbody>
        </table>
    </div>`;

    return html;
}

function congKhaiKetQua(idBaiKiemTra) {
    Swal.fire({
        title: "Bạn có chắc?",
        text: "Công khai kết quả sẽ cho phép sinh viên xem điểm!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Có, công khai!",
        cancelButtonText: "Hủy",
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/bai-kiem-tra/${idBaiKiemTra}/cong-khai`,
                type: "POST",
                data: {
                    _token: $('meta[name="csrf-token"]').attr("content"),
                },
                success: function (res) {
                    Swal.fire({
                        icon: "success",
                        title: "Thành công!",
                        text: "Kết quả đã được công khai cho sinh viên.",
                        timer: 2000,
                        showConfirmButton: false,
                    });
                    $("#modalChiTiet .modal-footer").html(`
            <div class="d-flex justify-content-between align-items-center w-100">
                <div class="text-success fw-bold d-flex align-items-center gap-1">
                    <i class="bi bi-check-circle-fill"></i> Đã công khai
                </div>
                <div></div>
            </div>
        `);
                },
                error: function () {
                    Swal.fire({
                        icon: "error",
                        title: "Lỗi!",
                        text: "Có lỗi xảy ra. Vui lòng thử lại.",
                    });
                },
            });
        }
    });
}

//Xem bài làm cụ thể của từng sinh viên
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
    $("#modalChiTiet .modal-title").html(
        `<span class="text-primary fw-bold fs-5">
                        <i class="bi bi-journal-text me-2"></i> ${currentBaiKiemTra.tieu_de}
                </span>`
    );

    // Thay nút đóng thành nút quay lại
    $("#modalChiTiet .modal-actions").html(`
        <button class="btn btn-outline-primary rounded-pill px-3 py-2 shadow-sm     d-flex  align-items-center gap-1"
                    onclick="quayLaiDanhSach(2)">
                    <i class="bi bi-arrow-left"></i> Quay lại
                </button>
    `);

    // Gán nội dung vào modal body
    $("#modalChiTiet .modal-body").html(content);
}

function quayLaiDanhSach($action) {
    if ($action) {
        if (currentBaiKiemTra && currentKetQuaList) {
            const html = renderDanhSachKetQua(
                currentBaiKiemTra,
                currentKetQuaList
            );
            $("#modalChiTiet .modal-title").html(
                `<span class="text-primary fw-bold fs-5">
                        <i class="bi bi-journal-text me-2"></i> ${currentBaiKiemTra.tieu_de}
                </span>`
            );
            $("#modalChiTiet .modal-body").html(html);

            /// Thay nút đóng thành nút quay lại
            $("#modalChiTiet .modal-actions").html(`
                <button class="btn btn-outline-primary rounded-pill px-3 py-2 shadow-sm     d-flex  align-items-center gap-1"
                    onclick="quayLaiDanhSach(0)">
                    <i class="bi bi-arrow-left"></i> Quay lại
                </button>
            `);
        }
    } else {
        if (currentBaiKiemTra && currentKetQuaList) {
            $("#modalChiTiet .modal-title").html(
                `<span class="text-primary fw-bold fs-5">
                        <i class="bi bi-journal-text me-2"></i>${currentBaiKiemTra.tieu_de}
                </span>`
            );
            $("#modalChiTiet .modal-body").html(
                renderChiTietBaiKiemTraGiangVien()
            );
            $("#modalChiTiet .modal-footer").html(`                   
                <div class="d-flex justify-content-end gap-2">
                    <button class="btn btn-info rounded-pill shadow-sm px-3 py-2 d-flex align-items-center" onclick="hienThiCauHoi()">
                        <i class="bi bi-eye me-2"></i> Xem câu hỏi
                    </button>

                    <button class="btn btn-warning rounded-pill shadow-sm px-3 py-2 d-flex align-items-center text-dark" onclick="chuyenSangChinhSua()">
                        <i class="bi bi-pencil-square me-2"></i> Chỉnh sửa
                    </button>

                    <button class="btn btn-success rounded-pill shadow-sm px-3 py-2 d-flex align-items-center" onclick="hienThiKetQua()">
                        <i class="bi bi-bar-chart-line me-2"></i> Kết quả
                    </button>
                </div>
            `);

            // Khôi phục lại nút đóng mặc định
            $("#modalChiTiet .modal-actions").html(`
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        `);
        }
    }
}

const chuyenSangChinhSua = () => {
    const body = document.getElementById("modalChiTietBody");

    let html = `<form id="editExerciseForm">
                    <input type="hidden" name="idBaiKiemTra" id="idBaiKiemTra"
                        value="${currentBaiKiemTra.id}">
                    <div class="row g-3 mb-4">
                        <!-- Tiêu đề -->
                        <div class="col-lg-10 col-sm-9">
                            <label for="ExerciseTitle" class="form-label fw-semibold">Tiêu đề <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-lg shadow-sm rounded-3"
                                name="tieu_de"
                                id="ExerciseTitle"
                                value="${currentBaiKiemTra.tieu_de}"
                                placeholder="Nhập tiêu đề bài kiểm tra"
                                required>
                            <div class="invalid-feedback fw-bold">
                                Vui lòng nhập tiêu đề cho bài kiểm tra.
                            </div>
                        </div>

                        <!-- Điểm tối đa -->
                        <div class="col-lg-2 col-sm-3">
                            <label for="ExerciseMaxScore" class="form-label fw-semibold">Điểm tối đa</label>
                            <input type="number" class="form-control form-control-lg shadow-sm rounded-3"
                                name="diem_toi_da"
                                value="${currentBaiKiemTra.diem_toi_da}"
                                id="ExerciseMaxScore"
                                placeholder="100"
                                min="0">
                            <div class="invalid-feedback fw-bold">
                                Vui lòng nhập điểm tối đa cho bài kiểm tra.
                            </div>
                        </div>
                    </div>

                    <!-- Thời gian -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="editStartTime" class="form-label fw-semibold">Thời gian bắt đầu</label>
                            <input type="text" class="form-control shadow-sm rounded-3"
                                id="editStartTime"
                                name="ngay_bat_dau"
                                placeholder="Chọn thời gian">
                            <div class="invalid-feedback fw-bold">
                                Vui lòng chọn thời gian bắt đầu
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="editEndTime" class="form-label fw-semibold">Thời gian kết thúc</label>
                            <input type="text" class="form-control shadow-sm rounded-3"
                                id="editEndTime"
                                name="ngay_ket_thuc"
                                placeholder="Chọn thời gian">
                            <div class="invalid-feedback fw-bold">
                                Vui lòng chọn thời gian kết thúc
                            </div>
                        </div>
                    </div>

                    <!-- Cho phép nộp quá hạn -->
                    <div class="text-center mt-2 mb-4">
                        <div class="form-check form-switch d-inline-flex align-items-center gap-2">
                            <input class="form-check-input" type="checkbox"
                                value="1"
                                id="editChoPhepNopTre"
                                name="cho_phep_nop_qua_han"
                                ${
                                    currentBaiKiemTra.cho_phep_nop_qua_han
                                        ? "checked"
                                        : ""
                                }>
                            <label class="form-check-label fw-semibold" for="editChoPhepNopTre">
                                Cho phép nộp quá hạn
                            </label>
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
            
        </form>`;
    $("#modalChiTiet .modal-actions").html(`
        <button class="btn btn-outline-primary rounded-pill px-3 py-2 shadow-sm     d-flex  align-items-center gap-1"
                    onclick="quayLaiDanhSach(0)">
                    <i class="bi bi-arrow-left"></i> Quay lại
                </button>
    `);

    $("#modalChiTiet .modal-footer").html(`
        <div class="d-flex flex-wrap gap-2 mb-4">
            <!-- Nút Thêm câu hỏi mới -->
            <button type="button" class="btn btn-primary d-flex align-items-center px-4 py-2 shadow-sm rounded-3"
                id="addQuestionBtn">
                <i class="fas fa-plus me-2"></i> Thêm câu hỏi
            </button>

            <!-- Nút chọn file Excel -->
            <label class="btn btn-outline-success d-flex align-items-center px-4 py-2 shadow-sm rounded-3 m-0"
                for="excelFileInput">
                <i class="fas fa-file-excel me-2"></i> Chọn file Excel
            </label>
            <input type="file" id="excelFileInput" accept=".xlsx, .xls" class="d-none">

            <!-- Nút lưu thay đổi -->
            <button type="button" class="btn btn-success d-flex align-items-center px-4 py-2 shadow-sm rounded-3"
                id="submitEditFormBtn">
                <i class="fas fa-save me-2"></i> Lưu thay đổi
            </button>
        </div>
        
    `);

    // Gán lại vào modal-body
    body.innerHTML = html;

    //Cấu hình Flatpickr - START
    flatpickr.localize(flatpickr.l10ns.vn);
    const startDate = new Date(currentBaiKiemTra.ngay_bat_dau);
    const now = new Date();

    const minStartDate = startDate < now ? null : now;

    const editStartPicker = flatpickr("#editStartTime", {
        enableTime: true,
        dateFormat: "d-m-Y H:i",
        time_24hr: true,
        allowInput: true,
        defaultDate: startDate,
        minDate: minStartDate,
        onChange: function (selectedDates, dateStr, instance) {
            // Gợi ý thời gian kết thúc ≥ thời gian bắt đầu
            if (selectedDates.length > 0) {
                const startDate = selectedDates[0];
                editEndPicker.set("minDate", startDate);
            }
        },
    });

    const editEndPicker = flatpickr("#editEndTime", {
        enableTime: true,
        dateFormat: "d-m-Y H:i",
        time_24hr: true,
        allowInput: true,
        defaultDate: formatDateForFlatpickr(currentBaiKiemTra.ngay_ket_thuc),
    });
    //Cấu hình Flatpickr - END

    fetch("/server-time")
        .then((response) => response.json())
        .then((data) => {
            const serverNow = new Date(data.now);

            const startTimeStr = currentBaiKiemTra.ngay_bat_dau;
            const startTime = new Date(startTimeStr);

            disableEditingFields(serverNow, startTime);
        });

    // Gắn sự kiện cho nút "Lưu thay đổi"
    setTimeout(() => {
        const submitBtn = document.getElementById("submitEditFormBtn");
        if (submitBtn) {
            submitBtn.addEventListener("click", function () {
                const form = document.getElementById("editExerciseForm");
                if (form) {
                    form.requestSubmit();
                }
            });
        }
    }, 0);
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
    const idLopHoc = infoLopHoc.dataset.idLopHoc;

    //Load dữ liệu
    $.ajax({
        url: `/bai-kiem-tra/${idLopHoc}`,
        method: "GET",
        success: function (data) {
            danhSachBaiKiemTra = data;
            // Cập nhật số bài kiểm tra
            const examTabButton = document.getElementById("exam-tab");
            if (examTabButton) {
                const badge = examTabButton.querySelector("span");
                if (badge) {
                    badge.textContent = danhSachBaiKiemTra.length;
                }
            }
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
                        <div class="card-body d-flex flex-column justify-content-center">
                            <h5 class="card-title">
                                <i class="bi bi-journal-text icon-exercise"></i> ${item.tieu_de}
                            </h5>
                            <p class="card-text mb-0">
                                <i class="bi bi-calendar-check"></i> Ngày đăng: ${item.ngay_tao}
                            </p>
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

                    //  Đếm số câu đúng
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
                            ? baiKiemTra.ngay_ket_thuc
                            : "Không có";

                        $("#modalChiTiet .modal-title").html(
                            `<span class="text-primary fw-bold fs-5">
                                <i class="bi bi-journal-text me-2"></i> ${baiKiemTra.tieu_de}
                            </span>`
                        );

                        const lamBaiUrl = `/lam-bai/${baiKiemTra.id}`;

                        $("#modalChiTiet .modal-body").html(`
                            <div class="card shadow-sm border-0 rounded-4 p-3 text-center bg-light-subtle">
                                <h4 class="mb-3 text-primary fw-bold">
                                    <i class="fas fa-clipboard-list me-2"></i> Thông tin bài kiểm tra
                                </h4>

                                <div class="row justify-content-center mb-3">
                                    <div class="col-md-4 col-6 mb-2">
                                        <div class="bg-white border rounded-3 p-3 h-100">
                                            <i class="fas fa-question-circle fa-lg text-info mb-1"></i>
                                            <p class="mb-0 fw-semibold">Số câu hỏi</p>
                                            <span class="text-dark fs-5">${tongCau}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-6 mb-2">
                                        <div class="bg-white border rounded-3 p-3 h-100">
                                            <i class="fas fa-clock fa-lg text-warning mb-1"></i>
                                            <p class="mb-0 fw-semibold">Hạn cuối</p>
                                            <span class="text-dark fs-6">${formatNgay(
                                                ngayDenHan
                                            )}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-6 mb-2">
                                        <div class="bg-white border rounded-3 p-3 h-100">
                                            <i class="fas fa-star fa-lg text-danger mb-1"></i>
                                            <p class="mb-0 fw-semibold">Điểm tối đa</p>
                                            <span class="text-dark fs-5">${
                                                baiKiemTra.diem_toi_da
                                            }</span>
                                        </div>
                                    </div>
                                </div>

                                <p class="text-muted mb-4 fw-bold">Bạn chưa làm bài kiểm tra này.</p>

                                <a href="${lamBaiUrl}" target="_blank" class="btn btn-lg btn-success px-4 shadow">
                                    <i class="fas fa-pen-nib me-2"></i> Làm bài ngay
                                </a>
                            </div>
                        `);

                        $("#modalChiTiet").modal("show");
                    } else if (!res.duoc_xem_ket_qua) {
                        // Đã làm bài nhưng chưa được công khai kết quả
                        $("#modalChiTiet .modal-title").html(`
                            <span class="text-primary fw-bold fs-5">
                                <i class="bi bi-journal-text me-2"></i> ${baiKiemTra.tieu_de}
                            </span>
                        `);

                        $("#modalChiTiet .modal-body").html(`
                            <div class="alert alert-info text-center rounded-4 shadow-sm p-4">
                                <h5 class="mb-3 text-primary fw-bold">Bạn đã hoàn thành bài kiểm tra này.</h5>
                                <p class="mb-0">Vui lòng chờ giảng viên công bố kết quả.</p>
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
                        //  Hiển thị tiêu đề modal gồm tiêu đề + số câu đúng / tổng câu
                        $("#modalChiTiet .modal-title").html(
                            `<span class="text-primary fw-bold fs-5">
                                <i class="bi bi-journal-text me-2"></i> ${baiKiemTra.tieu_de} - Kết quả: ${soCauDung}/${tongCau} câu đúng
                        </span>`
                        );

                        //  Render nội dung chi tiết
                        $("#modalChiTiet .modal-body").html(
                            renderChiTietBaiKiemTra(baiKiemTra, chiTiet)
                        );

                        //  Hiển thị modal
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
                    currentThongKe = res.thong_ke_cau_hoi;

                    dsKetQua.forEach((item) => {
                        if (item.ket_qua?.id) {
                            allChiTietTheoKetQua[item.ket_qua.id] =
                                item.chi_tiet;
                        }
                    });

                    $("#modalChiTiet .modal-title").html(`
                        <span class="text-primary fw-bold fs-5">
                        <i class="bi bi-journal-text me-2"></i> ${baiKiemTra.tieu_de}
                    </span>
                    `);
                    $("#modalChiTiet .modal-body").html(
                        renderChiTietBaiKiemTraGiangVien()
                    );
                    $("#modalChiTiet .modal-footer").html(`                   
                        <div class="d-flex justify-content-end gap-2">
                            <button class="btn btn-info rounded-pill shadow-sm px-3 py-2 d-flex align-items-center" onclick="hienThiCauHoi()">
                                <i class="bi bi-eye me-2"></i> Xem câu hỏi
                            </button>

                            <button class="btn btn-warning rounded-pill shadow-sm px-3 py-2 d-flex align-items-center text-dark" onclick="chuyenSangChinhSua()">
                                <i class="bi bi-pencil-square me-2"></i> Chỉnh sửa
                            </button>

                            <button class="btn btn-success rounded-pill shadow-sm px-3 py-2 d-flex align-items-center" onclick="hienThiKetQua()">
                                <i class="bi bi-bar-chart-line me-2"></i> Kết quả
                            </button>
                        </div>
                    `);

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

    // Thêm bài kiểm tra SUBMIT
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
        if (start > end) {
            Swal.fire({
                icon: "warning",
                title: "Thời gian không hợp lệ",
                text: "Thời gian kết thúc phải sau hoặc bằng thời gian bắt đầu.",
                confirmButtonText: "Đóng",
            });
            return;
        }

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
                        danhSachBaiKiemTra = response.data;
                        // Cập nhật số bài kiểm tra
                        const examTabButton =
                            document.getElementById("exam-tab");
                        if (examTabButton) {
                            const badge = examTabButton.querySelector("span");
                            if (badge) {
                                badge.textContent = danhSachBaiKiemTra.length;
                            }
                        }
                        renderDanhSach(danhSachBaiKiemTra);
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

    //Chỉnh sửa bài kiểm tra SUBMIT
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

            const start = new Date(ngayBatDau);
            const end = new Date(ngayKetThuc);

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
                            // Tìm thẻ bài kiểm tra có id tương ứng
                            const card = $(
                                `.item-bai-kiem-tra[data-id="${currentBaiKiemTra.id}"]`
                            );

                            // Cập nhật lại tiêu đề
                            card.find(".card-title").html(`
                                <i class="bi bi-journal-text icon-exercise"></i> ${currentBaiKiemTra.tieu_de}
                            `);
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
});
