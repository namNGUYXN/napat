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
            handleCheckAllSelected($(".row-checkbox:checked"));
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
                $("#news-tab>span").text(response.tongSoBanTin);

                $("#modal-them-ban-tin").modal("hide");

                // Reset form
                form[0].reset();
            },
            error: function (xhr) {
                alert("Đã xảy ra lỗi: " + xhr.status + " " + xhr.statusText);
            },
        });
    });

// Xử lý modal chỉnh sửa bản tin
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
                    $("#news-tab>span").text(response.tongSoBanTin);

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

    console.log(noiDungPhanHoi);
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

$("#img-upload-modal-chinh-sua").on("change", function (event) {
    const file = event.target.files[0];
    const imgPreview = $("#img-preview-container-modal-chinh-sua .img-preview");
    const imgRemoveBtn = $(
        "#img-preview-container-modal-chinh-sua .img-remove-btn"
    );

    handleRenderImg(file, imgPreview, imgRemoveBtn);
});

function handleRenderImg(file, imgPreview, imgRemoveBtn) {
    if (file && file.type.startsWith("image/")) {
        const reader = new FileReader();

        reader.onload = function (e) {
            imgPreview.attr("src", e.target.result).show();
            imgRemoveBtn.show(); // Hiển thị nút xóa
        };

        reader.readAsDataURL(file);
    } else {
        // Nếu không có file nào được chọn hoặc file không phải là ảnh
        imgPreview.attr("src", "#").hide();
        imgRemoveBtn.hide(); // Ẩn nút xóa
    }
}

$("#img-preview-container-modal-chinh-sua .img-remove-btn").on(
    "click",
    function () {
        const imgPreview = $(
            "#img-preview-container-modal-chinh-sua .img-preview"
        );
        const imgUpload = $("#img-upload-modal-chinh-sua");
        const imgRemoveBtn = $(
            "#img-preview-container-modal-chinh-sua .img-remove-btn"
        );

        handleRemoveImg(imgPreview, imgUpload, imgRemoveBtn);
    }
);

function handleRemoveImg(imgPreview, imgUpload, imgRemoveBtn) {
    // Reset thuộc tính src của ảnh preview và ẩn nó đi
    imgPreview.attr("src", "#").hide();
    imgRemoveBtn.hide();

    // Reset giá trị của input file
    // Đây là cách để xóa file đã chọn khỏi input file
    imgUpload.val("");
}

$(document).on("click", ".btn-delete-class", function () {
    const formData = new FormData(); // Lấy tất cả input từ form, bao gồm file
    const token = $('meta[name="csrf-token"]').attr("content");
    const view = $("#modal-chinh-sua-lop-hoc-phan").data("view");
    const urlDelete = $(this).data("url-delete");
    const urlMyClass = $(this).data("url-my-class");

    formData.append("_token", token);
    formData.append("_method", "DELETE");
    formData.append("view", view);

    Swal.fire({
        title: `Bạn có chắc chắn xóa lớp học phần này không?`,
        text: `Bạn sẽ không thể khôi phục lớp học phần này!`,
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
                data: formData,
                contentType: false, // Để jQuery không set Content-Type
                processData: false, // Để không chuyển FormData thành chuỗi query
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
                    }).then(() => {
                        window.location.href = urlMyClass;
                    });
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


