function loadNguoiDung(page = 1) {
    const vaiTro = $("#vaiTroSelect").val();
    const keyword = $("#searchInput").val();
    const perPage = $("#perPageSelect").val();

    $.ajax({
        url: `?page=${page}`,
        type: "GET",
        data: {
            vai_tro: vaiTro,
            keyword: keyword,
            per_page: perPage,
        },
        success: function (data) {
            $("#nguoiDungTable").html(data);
        },
        error: function () {
            alert("Đã xảy ra lỗi khi tải danh sách.");
        },
    });
}

// Bắt Enter trong ô tìm kiếm
$(document).on("keypress", "#searchInput", function (e) {
    if (e.which === 13) {
        loadNguoiDung(1);
    }
});

$(document).on("click", "#clearSearch", function () {
    $("#searchInput").val(""); // Xóa keyword trong input
    loadNguoiDung(1); // Gọi lại AJAX từ trang 1
});

$(document).ready(function () {
    $("#btnSearch, #vaiTroSelect, #perPageSelect").on(
        "click change",
        function () {
            loadNguoiDung(1);
        }
    );

    $(document).on("click", ".pagination a", function (e) {
        e.preventDefault();
        const page = $(this).attr("href").split("page=")[1];
        loadNguoiDung(page);
    });
});

document
    .querySelectorAll('.form-khoa-mo button[type="button"]')
    .forEach((button) => {
        button.addEventListener("click", function (e) {
            const form = this.closest("form");
            const action = form.getAttribute("data-action");
            const ten = form.getAttribute("data-ten");
            const isLock = this.classList.contains("btn-outline-danger"); // true nếu đang active

            Swal.fire({
                title: isLock ? "Xác nhận khóa?" : "Xác nhận mở khóa?",
                text: `${isLock ? "Khóa" : "Mở khóa"} người dùng "${ten}"?`,
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: isLock ? "Khóa" : "Mở khóa",
                cancelButtonText: "Hủy bỏ",
                reverseButtons: true,
                confirmButtonColor: isLock ? "#d33" : "#28a745",
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
