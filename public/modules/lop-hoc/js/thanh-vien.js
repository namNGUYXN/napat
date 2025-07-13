//Nhấn chấp nhận yêu cầu tham gia lớp học
$(document).on("click", ".btn-accept-request", function () {
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
                            icon: "success",
                            title: "Chấp nhận đăng ký lớp thành công",
                        });

                        $("#list-thanh-vien").html(res.html);
                        $("#member-tab span").text(res.tongSoThanhVien);
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
$(document).on("click", ".btn-reject-request", function () {
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

//Đuổi khỏi lớp
$(document).on("click", ".btn-remove-from-class", function () {
    const formData = new FormData(); // Lấy tất cả input từ form, bao gồm file
    const token = $('meta[name="csrf-token"]').attr("content");
    const urlRemoveFrom = $(this).data("url-remove-from");

    formData.append("_token", token);
    formData.append("_method", "DELETE");

    Swal.fire({
        title: `Bạn có chắc chắn xóa sinh viên này khỏi lớp?`,
        text: `Các dữ liệu mà sinh viên thao tác trong lớp sẽ không thể khôi phục!`,
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Xóa khỏi lớp",
        cancelButtonText: "Hủy",
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: urlRemoveFrom,
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
                    });

                    $("#list-thanh-vien").html(response.html);
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

//Rời lớp
$(document).on("click", ".btn-leave-class", function () {
    const formData = new FormData(); // Lấy tất cả input từ form, bao gồm file
    const token = $('meta[name="csrf-token"]').attr("content");
    const urlLeave = $(this).data("url-leave");
    const urlMyClass = $(this).data("url-my-class");

    formData.append("_token", token);
    formData.append("_method", "DELETE");

    Swal.fire({
        title: `Bạn có chắc chắn rời lớp học phần này không?`,
        // text: ``,
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Rời khỏi",
        cancelButtonText: "Hủy",
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: urlLeave,
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
