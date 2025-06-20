const changePasswordModal = document.getElementById("changePasswordModal");

changePasswordModal.addEventListener("hidden.bs.modal", function () {
    // Xóa value các input
    document.getElementById("currentPassword").value = "";
    document.getElementById("newPassword").value = "";
    document.getElementById("confirmPassword").value = "";

    // Reset lại input type về password
    ["currentPassword", "newPassword", "confirmPassword"].forEach((id) => {
        const input = document.getElementById(id);
        const btn = document.querySelector(
            `.toggle-password[data-target="${id}"]`
        );
        const icon = btn?.querySelector("i");

        if (input && input.type === "text") {
            input.type = "password";
            if (icon) {
                icon.classList.remove("bi-eye-slash");
                icon.classList.add("bi-eye");
            }
        }
    });
});

document.querySelectorAll(".toggle-password").forEach((button) => {
    button.addEventListener("click", function () {
        const inputId = this.getAttribute("data-target");
        const input = document.getElementById(inputId);
        const icon = this.querySelector("i");

        if (input.type === "password") {
            input.type = "text";
            icon.classList.remove("bi-eye");
            icon.classList.add("bi-eye-slash");
        } else {
            input.type = "password";
            icon.classList.remove("bi-eye-slash");
            icon.classList.add("bi-eye");
        }
    });
});

document.getElementById("avatarInput").addEventListener("change", function (e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = (evt) => {
            document.getElementById("avatarPreview").src = evt.target.result;
        };
        reader.readAsDataURL(file);
    }
});

$("#btnChangePassword").click(function () {
    // Xóa thông báo lỗi cũ
    $("#currentPasswordError").text("");
    $("#confirmPasswordError").text("");

    // Lấy giá trị
    let currentPassword = $("#currentPassword").val();
    let newPassword = $("#newPassword").val();
    let confirmPassword = $("#confirmPassword").val();

    // Kiểm tra xác nhận mật khẩu
    if (newPassword !== confirmPassword) {
        $("#confirmPasswordError").text(
            "Mật khẩu mới và xác nhận mật khẩu không trùng khớp"
        );
        return;
    }

    $.ajax({
        url: "/tai-khoan/doi-mat-khau",
        method: "POST",
        data: {
            _token: $('meta[name="csrf-token"]').attr("content"),
            currentPassword: currentPassword,
            newPassword: newPassword,
        },
        success: function (res) {
            if (res.status) {
                // Thành công
                $("#changePasswordModal").modal("hide");
                Swal.fire({
                    icon: "success",
                    title: "Thành công",
                    text: res.message,
                });
            } else {
                // Mật khẩu hiện tại sai
                $("#currentPasswordError").text(res.message);
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
