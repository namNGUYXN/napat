$(document).ready(function () {
    const $toggleMenu = $("#toggleMenu");
    const $slideMenu = $("#slideMenu");

    // Toggle menu khi click nút
    $toggleMenu.on("click", function (e) {
        e.stopPropagation(); // Ngăn sự kiện lan ra window
        $slideMenu.toggleClass("show");
    });

    // Tự ẩn menu khi click bên ngoài
    $(window).on("click", function (e) {
        if (
            !$slideMenu.is(e.target) &&
            $slideMenu.has(e.target).length === 0 &&
            !$toggleMenu.is(e.target) &&
            $toggleMenu.has(e.target).length === 0
        ) {
            $slideMenu.removeClass("show");
        }
    });
});

const currentUser = "userA";
const MAX_NESTING_LEVEL = 4;

function createCommentHtml(comment, currentLevel = 0) {
    const isOwner = comment.ownerId === currentUser;
    const commentId = comment.id;
    const commentAuthor = comment.author;
    const commentContent = comment.content;
    const timeAgo = comment.timeAgo;

    const dropdownHtml = isOwner
        ? `
      <div class="dropdown comment-actions-dropdown">
          <button class="btn btn-transparent dropdown-toggle hide-arrow-down" type="button" data-bs-toggle="dropdown" aria-expanded="false">
              <i class="fas fa-ellipsis-v"></i>
          </button>
          <ul class="dropdown-menu dropdown-menu-end">
              <li><a class="dropdown-item edit-comment-btn" href="#" data-comment-id="${commentId}">Chỉnh sửa</a></li>
              <li><a class="dropdown-item delete-comment-btn" href="#" data-comment-id="${commentId}">Xóa</a></li>
          </ul>
      </div>
  `
        : "";

    return `
      <div class="list-group-item comment-item" data-comment-id="${commentId}" data-comment-owner-id="${comment.ownerId}" data-comment-level="${currentLevel}">
          <div class="d-flex w-100 justify-content-between align-items-center">
              <h6 class="mb-1 me-auto">${commentAuthor}</h6>
              <small class="text-muted me-2">${timeAgo}</small>
              ${dropdownHtml}
          </div>
          <p class="mb-1 comment-content-text">${commentContent}</p>
          
          <div class="edit-form-container mt-2" style="display: none;">
              <form class="edit-comment-form d-flex align-items-end">
                  <div class="flex-grow-1 me-2">
                      <textarea class="form-control form-control-sm" rows="2" required>${commentContent}</textarea>
                  </div>
                  <button type="submit" class="btn btn-sm btn-success me-1">Lưu</button>
                  <button type="button" class="btn btn-sm btn-secondary cancel-edit-btn">Hủy</button>
              </form>
          </div>
      </div>
  `;
}

$("#commentForm").on("submit", function (e) {
    e.preventDefault();

    const urlCreate = $(this).attr("action");
    const form = $(this);
    const formData = new FormData(this);

    $.ajax({
        url: urlCreate,
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
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

            $("#commentsList").html(response.html);

            form.find(".binh-luan-error").text("");

            form[0].reset();
        },
        error: function (xhr) {
            if (xhr.status === 422) {
                const errors = xhr.responseJSON.errors;

                if (errors.noi_dung) {
                    form.find(".binh-luan-error").text(errors.noi_dung[0]);
                }
            } else {
                alert("Đã xảy ra lỗi: " + xhr.status + " " + xhr.statusText);
            }
        },
    });
});

$(document).on("click", ".reply-btn", function (e) {
    e.preventDefault();
    $(".comment-item .binh-luan-error").text("");

    const $replyBtn = $(this);
    const $commentItem = $replyBtn.closest(".comment-item");
    const $replyFormContainer = $commentItem.find(
        ".reply-form-container:first"
    );
    const commentAuthor = $replyBtn.data("comment-author");

    $replyFormContainer
        .find("textarea")
        .attr("placeholder", `Phản hồi lại ${commentAuthor}...`);

    $(".reply-form-container").not($replyFormContainer).slideUp(200);
    $(".edit-form-container").slideUp(200);

    // Đóng các replies-container đang mở, trừ container cha của bình luận hiện tại và trừ chính nó
    // Tìm tất cả các replies-container đang hiển thị
    $(".replies-container:not(.hidden-replies)").each(function () {
        const $openContainer = $(this);
        // Kiểm tra xem container đang mở có phải là tổ tiên của bình luận hiện tại ko
        const isAncestor = $.contains($openContainer[0], $commentItem[0]);
        // Nếu ko phải tổ tiên (và ko phải chính $repliesContainer của $commentItem), thì đóng nó
        if (
            !isAncestor &&
            !$openContainer.is($commentItem.find(".replies-container:first"))
        ) {
            $openContainer
                .slideUp(200)
                .addClass("hidden-replies")
                .prevAll(".toggle-replies-btn:first")
                .find("i")
                .removeClass("fa-caret-up")
                .addClass("fa-caret-down");
            const currentText = $openContainer
                .prevAll(".toggle-replies-btn:first")
                .text();
            $openContainer
                .prevAll(".toggle-replies-btn:first")
                .html(
                    currentText.replace("Ẩn ", "Có ") +
                        ' <i class="fas fa-caret-down"></i>'
                );
        }
    });

    $replyFormContainer.slideToggle(200, function () {
        if ($replyFormContainer.is(":visible")) {
            $replyFormContainer.find("textarea").focus();
        }
    });
});

$(document).on("submit", ".reply-form", function (e) {
    e.preventDefault();

    const form = $(this);
    const formData = new FormData(this);
    const urlReply = form.attr("action");

    $.ajax({
        url: urlReply,
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
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

            const $parentCommentItem = form.closest(".comment-item");
            const parentCommentId = $parentCommentItem.data("comment-id");

            const newReplyHtml = response.html;
            const itemBinhLuanCon = $parentCommentItem.find(
                ".item-binh-luan-con"
            );
            const $repliesContainer =
                itemBinhLuanCon.parent(".replies-container");

            // Thêm phản hồi mới vào container
            itemBinhLuanCon.prepend(newReplyHtml);

            // Cập nhật số lượng và trạng thái nút toggle
            let $toggleBtn = $parentCommentItem.find(
                ".toggle-replies-btn:first"
            );
            if (!$toggleBtn.length) {
                // Nếu chưa có nút toggle (trước đó không có phản hồi)
                const count = itemBinhLuanCon.children(".comment-item").length; // Đếm lại số con
                const newToggleHtml = `
                <small>
                    <a href="#" class="toggle-replies-btn text-muted" data-comment-id="${parentCommentId}" data-has-replies="true" data-toggle-state="shown">
                        Có ${count} phản hồi <i class="fas fa-caret-up"></i>
                    </a>
                </small>`;
                // Thêm nút toggle vào sau comment-action-links
                $parentCommentItem
                    .find(".comment-action-links")
                    .after(newToggleHtml);
                $toggleBtn = $parentCommentItem.find(
                    ".toggle-replies-btn:first"
                ); // Lấy lại tham chiếu đến nút mới
            } else {
                const currentCount =
                    parseInt($toggleBtn.text().match(/\d+/)) || 0;
                $toggleBtn
                    .html(
                        `Có ${
                            currentCount + 1
                        } phản hồi <i class="fas fa-caret-up"></i>`
                    )
                    .data("toggle-state", "shown");
            }

            // Đảm bảo container hiển thị khi có phản hồi mới
            if ($repliesContainer.hasClass("hidden-replies")) {
                $repliesContainer.slideDown(200).removeClass("hidden-replies");
                $toggleBtn
                    .find("i")
                    .removeClass("fa-caret-down")
                    .addClass("fa-caret-up");
                const currentText = $toggleBtn.text();
                $toggleBtn.html(
                    currentText.replace("Có ", "Ẩn ") +
                        ' <i class="fas fa-caret-up"></i>'
                );
            }

            form.find("textarea").val("");
            form.closest(".reply-form-container").slideUp(200);
        },
        error: function (xhr) {
            if (xhr.status === 422) {
                const errors = xhr.responseJSON.errors;

                if (errors.noi_dung) {
                    form.find(".binh-luan-error").text(errors.noi_dung[0]);
                }
            } else {
                alert("Đã xảy ra lỗi: " + xhr.status + " " + xhr.statusText);
            }
        },
    });
});

$(document).on("click", ".edit-comment-btn", function () {
    $(".comment-item .binh-luan-error").text("");

    const $editBtn = $(this);
    const $commentItem = $editBtn.closest(".comment-item");

    const $commentContentText = $commentItem.find(
        ".comment-content-text:first"
    );
    const $editFormContainer = $commentItem.find(".edit-form-container:first");
    const $editTextArea = $editFormContainer.find("textarea");

    $(".reply-form-container").slideUp(200);
    $(".edit-form-container").not($editFormContainer).slideUp(200);

    // Đóng các replies-container đang mở, trừ container cha của bình luận hiện tại và trừ chính nó
    $(".replies-container:not(.hidden-replies)").each(function () {
        const $openContainer = $(this);
        const isAncestor = $.contains($openContainer[0], $commentItem[0]);
        if (
            !isAncestor &&
            !$openContainer.is($commentItem.find(".replies-container:first"))
        ) {
            $openContainer
                .slideUp(200)
                .addClass("hidden-replies")
                .prevAll(".toggle-replies-btn:first")
                .find("i")
                .removeClass("fa-caret-up")
                .addClass("fa-caret-down");
            const currentText = $openContainer
                .prevAll(".toggle-replies-btn:first")
                .text();
            $openContainer
                .prevAll(".toggle-replies-btn:first")
                .html(
                    currentText.replace("Ẩn ", "Có ") +
                        ' <i class="fas fa-caret-down"></i>'
                );
        }
    });

    if ($editFormContainer.is(":visible")) {
        $editFormContainer.slideUp(200);
        $commentContentText.show();
    } else {
        $editTextArea.val($commentContentText.text().trim());
        $commentContentText.hide();
        $editFormContainer.slideDown(200, function () {
            $editTextArea.focus();
        });
    }
});

$(document).on("click", ".cancel-edit-btn", function () {
    const $cancelBtn = $(this);
    const $editFormContainer = $cancelBtn.closest(".edit-form-container");
    const $commentItem = $cancelBtn.closest(".comment-item");
    const $commentContentText = $commentItem.find(
        ".comment-content-text:first"
    );
    $editFormContainer.slideUp(200, function () {
        $commentContentText.show();
    });

    $(".comment-item .binh-luan-error").text("");
});

$(document).on("submit", ".edit-comment-form", function (e) {
    e.preventDefault();

    const form = $(this);
    const formData = new FormData(this);
    const urlUpdate = form.attr("action");
    const $commentItem = form.closest(".comment-item");
    const inputNoiDung = form.find('textarea[name="noi_dung"]');
    // alert(urlUpdate);
    // return;

    $.ajax({
        url: urlUpdate,
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function (response) {
            const binhLuan = response.binhLuan;
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

            $commentItem
                .find(".comment-content-text:first")
                .text(binhLuan.noi_dung)
                .show();
            form.closest(".edit-form-container").slideUp(200);
            inputNoiDung.val(binhLuan.noi_dung);
        },
        error: function (xhr) {
            if (xhr.status === 422) {
                const errors = xhr.responseJSON.errors;

                if (errors.noi_dung) {
                    form.find(".binh-luan-error").text(errors.noi_dung[0]);
                }
            } else {
                alert("Đã xảy ra lỗi: " + xhr.status + " " + xhr.statusText);
            }
        },
    });
});

$(document).on("click", ".delete-comment-btn", function () {
    const token = $('meta[name="csrf-token"]').attr("content");
    const $deleteBtn = $(this);
    const $commentItem = $deleteBtn.closest(".comment-item");
    const $parentRepliesContainer = $commentItem.parent(
        ".replies-container .item-binh-luan-con"
    );
    const urlDelete = $deleteBtn.data("url-delete");

    Swal.fire({
        title: `Bạn có chắc chắn xóa bình luận này không?`,
        // text: `Bạn sẽ không thể khôi phục bình luận này!`,
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
                type: "POST",
                data: {
                    _token: token,
                    _method: "DELETE",
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

                    $commentItem.slideUp(300, function () {
                        $(this).remove();

                        if ($parentRepliesContainer.length) {
                            const $parentCommentItem =
                                $parentRepliesContainer.closest(
                                    ".comment-item"
                                );
                            const $toggleBtn = $parentCommentItem.find(
                                ".toggle-replies-btn:first"
                            );
                            // console.log($toggleBtn);
                            if ($toggleBtn.length) {
                                const newCount =
                                    $parentRepliesContainer.children(
                                        ".comment-item"
                                    ).length; // Đếm lại số con
                                // console.log(newCount);
                                if (newCount === 0) {
                                    $toggleBtn.remove();
                                } else {
                                    $toggleBtn.html(
                                        `Có ${newCount} phản hồi <i class="fas fa-caret-up"></i>`
                                    );
                                }
                            }
                        }
                    });

                    const itemBinhLuanThu2 = $(
                        "#list-binh-luan>.comment-item:nth-child(2)"
                    );
                    if (!itemBinhLuanThu2.length) {
                        $("#list-binh-luan-card").remove();
                    }
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

$(document).on("click", ".toggle-replies-btn", function (e) {
    e.preventDefault();
    $(".comment-item .binh-luan-error").text("");
    const $toggleBtn = $(this);
    const $commentItem = $toggleBtn.closest(".comment-item");
    const $repliesContainer = $commentItem.find(".replies-container:first");
    const $icon = $toggleBtn.find("i");

    // Đóng tất cả các form phản hồi và chỉnh sửa đang mở
    $(".reply-form-container").slideUp(200);
    $(".edit-form-container").slideUp(200);

    // Logic chính để đóng các container khác:
    // Đóng tất cả các replies-container đang mở (không có class hidden-replies)
    // ngoại trừ container của chính bình luận cha mà bạn đang click vào,
    // và ngoại trừ các container là tổ tiên của nó.
    $(".replies-container:not(.hidden-replies)").each(function () {
        const $openContainer = $(this);
        // Kiểm tra xem $openContainer có phải là container của $commentItem hay không
        const isThisCommentContainer = $openContainer.is($repliesContainer);
        // Kiểm tra xem $openContainer có phải là tổ tiên của $commentItem hay không
        const isAncestorOfThisComment = $.contains(
            $openContainer[0],
            $commentItem[0]
        );

        if (!isThisCommentContainer && !isAncestorOfThisComment) {
            $openContainer.slideUp(200).addClass("hidden-replies");
            const $associatedToggleBtn = $openContainer.prevAll(
                ".toggle-replies-btn:first"
            );
            if ($associatedToggleBtn.length) {
                $associatedToggleBtn
                    .find("i")
                    .removeClass("fa-caret-up")
                    .addClass("fa-caret-down");
                const currentText = $associatedToggleBtn.text();
                $associatedToggleBtn.html(
                    currentText.replace("Ẩn ", "Có ") +
                        ' <i class="fas fa-caret-down"></i>'
                );
            }
        }
    });

    // Bây giờ, xử lý hiển thị/ẩn container của bình luận hiện tại
    if ($repliesContainer.hasClass("hidden-replies")) {
        $repliesContainer.slideDown(200).removeClass("hidden-replies");
        $icon.removeClass("fa-caret-down").addClass("fa-caret-up");
        const currentText = $toggleBtn.text();
        $toggleBtn.html(
            currentText.replace("Có ", "Ẩn ") +
                ' <i class="fas fa-caret-up"></i>'
        );
    } else {
        $repliesContainer.slideUp(200).addClass("hidden-replies");
        $icon.removeClass("fa-caret-up").addClass("fa-caret-down");
        const currentText = $toggleBtn.text();
        $toggleBtn.html(
            currentText.replace("Ẩn ", "Có ") +
                ' <i class="fas fa-caret-down"></i>'
        );
    }
});

$(document).on("submit", "#form-search-bai", function (e) {
    e.preventDefault();

    const urlSearch = $(this).attr("action");
    let queryString = $(this).serialize();

    if (queryString == "search=") queryString = "search=all";

    $.ajax({
        url: urlSearch,
        type: "GET",
        data: queryString,
        dataType: "json",
        success: function (response) {
            $("#list-bai").html(response.html);
        },
        error: function (xhr) {
            alert("Đã xảy ra lỗi: " + xhr.status + " " + xhr.statusText);
        },
    });
});

function hienThiCauHoi() {
    const dsCauHoi = currentBaiKiemTra.list_cau_hoi || [];

    if (dsCauHoi.length === 0) {
        $("#modalChiTiet .modal-body").html(`
            <div class="text-center py-4 text-muted">
                <i class="bi bi-exclamation-circle fs-1 mb-2"></i>
                <p class="fs-5">Không có câu hỏi nào trong bài tập này.</p>
            </div>
        `);
        return;
    }

    let html = `
        <div class="d-flex flex-column gap-3">
    `;

    dsCauHoi.forEach((cau, index) => {
        const dapAnDung = cau.dap_an_dung?.trim().toUpperCase();

        html += `
            <div class="border rounded p-3 bg-light shadow-sm">
                <div class="mb-2">
                    <strong class="text-dark">Câu ${index + 1}:</strong> ${
            cau.tieu_de
        }
                </div>
                <div class="row row-cols-2 g-2 ps-3 mt-2">
                    ${["A", "B", "C", "D"]
                        .map(
                            (k) => `
                        <div class="col">
                            <div class="p-2 rounded ${
                                dapAnDung === k
                                    ? "bg-success text-white fw-bold"
                                    : "bg-white border"
                            }">
                                ${k}. ${cau[`dap_an_${k.toLowerCase()}`]}
                            </div>
                        </div>
                    `
                        )
                        .join("")}
                </div>
            </div>
        `;
    });

    html += `</div>`;

    $("#modalChiTiet .modal-title").html(
        `
            <i class="bi bi-list-task text-primary me-2 fs-4"></i> Danh sách câu hỏi
        `
    );

    $("#modalChiTiet .modal-body").html(html);

    // Nút quay lại
    $("#modalChiTiet .modal-actions").html(`
            <button class="btn btn-outline-secondary" onclick="quayLaiDanhSach(0)">
                <i class="bi bi-arrow-left"></i> Quay lại
            </button>
    `);

    // Xoá footer nếu có
    $("#modalChiTiet .modal-footer").html("");
}

function quayLaiDanhSach($action) {
    if ($action) {
        if (currentBaiKiemTra && currentKetQuaList) {
            const html = renderDanhSachKetQua(
                currentBaiKiemTra,
                currentKetQuaList
            );
            $("#modalChiTiet .modal-title").html(`
                    <i class="bi bi-journal-text text-primary me-2 fs-4"></i>
                    <span class="fw-semibold">${currentBaiKiemTra.tieu_de}</span>
                `);
            $("#modalChiTiet .modal-body").html(html);

            $("#modalChiTiet .modal-footer").html(`      
            `);
            /// Thay nút đóng thành nút quay lại
            $("#modalChiTiet .modal-actions").html(`
                <button class="btn btn-secondary" onclick="quayLaiDanhSach(0)">
                    ← Quay lại 
                </button>
            `);
        }
    } else {
        if (currentBaiKiemTra && currentKetQuaList) {
            $("#modalChiTiet .modal-title").html(`
                    <i class="bi bi-journal-text text-primary me-2 fs-4"></i>
                    <span class="fw-semibold">${currentBaiKiemTra.tieu_de}</span>
                `);
            $("#modalChiTiet .modal-body").html(renderChiTietBaiTapGiangVien());
            $("#modalChiTiet .modal-footer").html(`                   
                <div class=" d-flex justify-content-end gap-2">
                        <button class="btn btn-outline-info d-flex align-items-center px-3 py-2 fw-semibold" onclick="hienThiCauHoi()">
                            <i class="bi bi-question-circle me-2 fs-5"></i> Xem câu hỏi
                        </button>
                        <button class="btn btn-primary d-flex align-items-center px-3 py-2 fw-semibold" onclick="hienThiKetQua()">
                            <i class="bi bi-bar-chart-line-fill me-2 fs-5"></i> Kết quả
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

function renderDanhSachKetQua(baiKiemTra, dsKetQua) {
    let html = `
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover table-sm table-nowrap align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>STT</th>
                        <th>Họ tên</th>
                        <th>Email</th>
                        <th>Ngày nộp</th>
                        <th>Điểm</th>
                        <th>Chi tiết</th>
                    </tr>
                </thead>
                <tbody>`;

    dsKetQua.forEach((item, index) => {
        const user = item.sinh_vien;
        const ketQua = item.ket_qua;
        const idKetQua = ketQua?.id || 0;

        // Xử lý ngày nộp
        let ngayNop = "Chưa nộp";
        if (ketQua?.ngay_lam) {
            ngayNop = formatNgay(ketQua.ngay_lam);
        }

        html += `
        <tr>
            <td>${index + 1}</td>
            <td class="text-nowrap">${user.ten}</td>
            <td class="text-nowrap">${user.email}</td>
            <td class="text-nowrap">${ngayNop}</td>
            <td>${item.diem ?? ""}</td>
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

    html += `
        </tbody>
    </table>
</div>`;
    return html;
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
    $("#modalChiTiet .modal-footer").html(`      
            `);
}

function renderChiTietBaiTapGiangVien() {
    const thongTinBaiKT = `
        <div class="px-2 text-center">
            <h5 class="fw-bold text-primary mb-4 d-flex justify-content-center align-items-center">
                <i class="bi bi-journal-text me-2 fs-4"></i> Thông tin bài tập
            </h5>

            <div class="d-flex flex-wrap justify-content-center gap-4">
                <div class="text-center">
                    <i class="bi bi-bookmark-fill text-danger fs-3 mb-1"></i>
                    <div><strong>Bài tập</strong></div>
                    <div>${currentBaiKiemTra.tieu_de}</div>
                </div>

                <div class="text-center">
                    <i class="bi bi-star-fill text-warning fs-3 mb-1"></i>
                    <div><strong>Điểm tối đa</strong></div>
                    <div>${currentBaiKiemTra.diem_toi_da}</div>
                </div>

                <div class="text-center">
                    <i class="bi bi-ui-checks text-success fs-3 mb-1"></i>
                    <div><strong>Hình thức</strong></div>
                    <div>${currentBaiKiemTra.hinh_thuc ?? "Trắc nghiệm"}</div>
                </div>

                <div class="text-center">
                    <i class="bi bi-list-ol text-primary fs-3 mb-1"></i>
                    <div><strong>Số câu hỏi</strong></div>
                    <div>${currentBaiKiemTra.list_cau_hoi.length}</div>
                </div>
            </div>
        </div>
    `;
    return thongTinBaiKT;
}

$(document).ready(function () {
    $("#danhSachBaiTap").on("click", ".item-bai-kiem-tra", function () {
        const id = $(this).data("id");
        const lopId = $("#danhSachBaiTap").data("lop-id");

        openModalChiTiet(id, lopId);
    });
    $("#formDanhDauHoanThanh").on("submit", function (e) {
        e.preventDefault();

        let form = $(this);
        let url = form.data("url");
        let token = form.find('input[name="_token"]').val();

        $.ajax({
            url: url,
            type: "POST",
            data: { _token: token },
            success: function (response) {
                // Xóa form và thay thế bằng thông báo thành công
                $("#formWrapper").html(`
                <div class="alert alert-success mt-4 d-inline-flex align-items-center" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    Bạn đã hoàn thành bài học này.
                </div>
            `);
            },
            error: function (xhr) {
                alert("Đánh dấu thất bại. Vui lòng thử lại!");
                console.error(xhr.responseText);
            },
        });
    });
    $("#formHoanThanhBai").on("submit", function (e) {
        e.preventDefault();

        let form = $(this);
        let url = form.data("url");
        let token = form.find('input[name="_token"]').val();
        let hoanThanhKhi = form
            .find('input[name="hoan_thanh_khi"]:checked')
            .val();

        $.ajax({
            url: url,
            type: "POST",
            data: {
                _token: token,
                hoan_thanh_khi: hoanThanhKhi,
            },
            success: function (response) {
                Swal.fire({
                    icon: "success",
                    title: "Thành công",
                    text: "Cập nhật cách hoàn thành bài học thành công.",
                    timer: 2000,
                    showConfirmButton: false,
                });
            },
            error: function (xhr) {
                Swal.fire({
                    icon: "error",
                    title: "Thất bại",
                    text: "Không thể lưu thay đổi. Vui lòng thử lại.",
                });
                console.error(xhr.responseText);
            },
        });
    });
});

function openModalChiTiet(id, lopId) {
    $.ajax({
        url: `/lop-hoc-phan/${lopId}/bai-tap/${id}/chi-tiet`,
        method: "GET",
        success: function (res) {
            console.log(res);
            if (res.role == "sinh_vien") {
                const baiKiemTra = res.bai_tap;
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

                    $("#modalChiTiet .modal-title").text(baiKiemTra.tieu_de);

                    const lamBaiUrl = `/lop-hoc-phan/${lopId}/lam-bai/${baiKiemTra.id}`;

                    $("#modalChiTiet .modal-body").html(`
                        <div class="text-center py-4">
                            <div class="mb-4">
                                <i class="bi bi-journal-text text-primary" style="font-size: 3rem;"></i>
                            </div>
                            <h4 class="fw-bold mb-3 text-dark">Thông tin bài tập</h4>

                            <div class="mb-2">
                                <i class="bi bi-list-ol text-success me-2"></i>
                                <strong>Số câu hỏi:</strong> ${tongCau}
                            </div>
                            <div class="mb-3">
                                <i class="bi bi-star-fill text-warning me-2"></i>
                                <strong>Điểm tối đa:</strong> ${baiKiemTra.diem_toi_da}
                            </div>

                            <p class="text-muted mt-3 mb-4 fs-6">
                                <i class="bi bi-info-circle text-secondary me-1"></i>
                                Bạn chưa làm bài này.
                            </p>

                            <a href="${lamBaiUrl}" target="_blank" class="btn btn-lg btn-success px-4">
                                <i class="bi bi-pencil-square me-2"></i> Làm bài ngay
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
                const baiTap = res.bai_tap;
                const dsKetQua = res.danh_sach_ket_qua;

                // Reset object chi tiết
                allChiTietTheoKetQua = {};
                currentBaiKiemTra = baiTap;
                currentKetQuaList = dsKetQua;

                dsKetQua.forEach((item) => {
                    if (item.ket_qua?.id) {
                        allChiTietTheoKetQua[item.ket_qua.id] = item.chi_tiet;
                    }
                });

                $("#modalChiTiet .modal-title").html(`
                    <i class="bi bi-journal-text text-primary me-2 fs-4"></i>
                    <span class="fw-semibold">${baiTap.tieu_de}</span>
                `);

                $("#modalChiTiet .modal-body").html(
                    renderChiTietBaiTapGiangVien()
                );
                $("#modalChiTiet .modal-footer").html(`
                    <div class=" d-flex justify-content-end gap-2">
                        <button class="btn btn-outline-info d-flex align-items-center px-3 py-2 fw-semibold" onclick="hienThiCauHoi()">
                            <i class="bi bi-question-circle me-2 fs-5"></i> Xem câu hỏi
                        </button>
                        <button class="btn btn-primary d-flex align-items-center px-3 py-2 fw-semibold" onclick="hienThiKetQua()">
                            <i class="bi bi-bar-chart-line-fill me-2 fs-5"></i> Kết quả
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
            alert("Không lấy được dữ liệu bài tập.");
        },
    });
}

function renderChiTietBaiKiemTra(baiKiemTra, chiTiet) {
    if (!chiTiet || !chiTiet.cauHoiVaDapAn) {
        return `<p>Bạn chưa làm bài tập này.</p>`;
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
