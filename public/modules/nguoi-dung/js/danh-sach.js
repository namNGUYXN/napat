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
