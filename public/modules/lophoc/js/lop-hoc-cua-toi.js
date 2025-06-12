$(document).ready(function () {
    $("#imageUpload").on("change", function (event) {
        const file = event.target.files[0];
        const $imagePreview = $("#imagePreview");
        const $removeImageBtn = $("#removeImageBtn");

        if (file && file.type.startsWith("image/")) {
            const reader = new FileReader();

            reader.onload = function (e) {
                $imagePreview.attr("src", e.target.result).show();
                $removeImageBtn.show(); // Hiển thị nút xóa
            };

            reader.readAsDataURL(file);
        } else {
            // Nếu không có file nào được chọn hoặc file không phải là ảnh
            $imagePreview.attr("src", "#").hide();
            $removeImageBtn.hide(); // Ẩn nút xóa
        }
    });

    $("#removeImageBtn").on("click", function () {
        const $imagePreview = $("#imagePreview");
        const $imageUpload = $("#imageUpload");
        const $removeImageBtn = $("#removeImageBtn");

        // Reset thuộc tính src của ảnh preview và ẩn nó đi
        $imagePreview.attr("src", "#").hide();
        $removeImageBtn.hide();

        // Reset giá trị của input file
        // Đây là cách để xóa file đã chọn khỏi input file
        $imageUpload.val("");
    });

    $(".class-update-btn").on("click", function () {
        $("#updateClassModal").modal("show");
    });
});
