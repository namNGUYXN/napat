function loadKhoa(page = 1) {
    const keyword = $("#searchInput").val();
    const perPage = $("#perPageSelect").val();

    $.ajax({
        url: `?page=${page}`,
        type: "GET",
        data: {
            keyword: keyword,
            per_page: perPage,
        },
        success: function (data) {
            $("#khoaTable").html(data); // Đổi ID container phù hợp
        },
        error: function () {
            alert("Đã xảy ra lỗi khi tải danh sách Khoa.");
        },
    });
}

// Bắt Enter trong ô tìm kiếm
$(document).on("keypress", "#searchInput", function (e) {
    if (e.which === 13) {
        loadKhoa(1);
    }
});

// Nút xóa tìm kiếm
$(document).on("click", "#clearSearch", function () {
    $("#searchInput").val("");
    loadKhoa(1);
});

$(document).ready(function () {
    $("#btnSearch, #perPageSelect").on("click change", function () {
        loadKhoa(1);
    });

    // Phân trang
    $(document).on("click", ".pagination a", function (e) {
        e.preventDefault();
        const page = $(this).attr("href").split("page=")[1];
        loadKhoa(page);
    });
});

document.addEventListener("DOMContentLoaded", function () {
    // Gán sự kiện submit cho các form xóa, kể cả khi được thêm sau
    document.body.addEventListener("submit", function (e) {
        const form = e.target;

        // Kiểm tra nếu form có class 'form-xoa'
        if (form.classList.contains("form-xoa")) {
            e.preventDefault(); // Ngăn submit mặc định

            Swal.fire({
                title: "Bạn có chắc chắn?",
                text: "Khoa này sẽ bị xóa!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#6c757d",
                confirmButtonText: "Xóa",
                cancelButtonText: "Hủy",
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit(); // Gửi form khi xác nhận
                }
            });
        }
    });
});
