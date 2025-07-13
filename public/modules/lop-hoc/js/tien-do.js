function toggleTienDo() {
    const card = document.getElementById("tienDoCard");
    const icon = document.getElementById("toggle-icon");
    const text = document.getElementById("toggle-text");

    if (card.style.display === "none") {
        card.style.display = "block";
        icon.classList.remove("bi-eye");
        icon.classList.add("bi-eye-slash");
        text.innerText = "Ẩn";
    } else {
        card.style.display = "none";
        icon.classList.remove("bi-eye-slash");
        icon.classList.add("bi-eye");
        text.innerText = "Hiện";
    }
}

$(document).ready(function () {
    $(".bai-item").on("click", function () {
        const baiId = $(this).data("id");

        $.ajax({
            url: `/tien-do/bai/${baiId}`,
            type: "GET",
            dataType: "json",
            success: function (data) {
                $("#dsDaHoc").empty();
                $("#dsChuaHoc").empty();

                data.da_hoc.forEach((tv) => {
                    const html = taoTheSinhVien(tv);
                    document
                        .getElementById("dsDaHoc")
                        .insertAdjacentHTML("beforeend", html);
                });

                data.chua_hoc.forEach((tv) => {
                    const html = taoTheSinhVien(tv);
                    document
                        .getElementById("dsChuaHoc")
                        .insertAdjacentHTML("beforeend", html);
                });
            },
            error: function (xhr, status, error) {
                console.error("Lỗi khi tải dữ liệu:", error);
            },
        });
    });

    function taoTheSinhVien(tv) {
        const imgSrc = tv.nguoi_dung?.hinh_anh
            ? `/storage/${tv.nguoi_dung.hinh_anh}`
            : "/img/default-avatar.jpg";

        return `
    <div class="list-group-item d-flex align-items-center justify-content-between rounded-30px custom-list-item mb-2">
        <div class="d-flex align-items-center">
            <img src="${imgSrc}" alt="Avatar"
                class="rounded-circle border border-secondary me-3" width="48" height="48"
                style="object-fit: cover;">
            <div>
                <div class="fw-semibold member-name">${tv.nguoi_dung.ho_ten}</div>
                <div class="text-muted member-email">${tv.nguoi_dung.email}</div>
            </div>
        </div>
    </div>`;
    }
});
