$(document).ready(function () {

    // --- Xử lý sự kiện ---

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('#csrfForm input[name="_token"]').val()
        }
    });

    let cache = {};

    // Xử lý modal xem chi tiết bài giảng
    $(document).on('click', '.btn-detail-bai-giang', function () {
        const url = $(this).data('url');

        if (cache[url]) {
            const baiGiang = cache[url];
            $('#tieu-de-bai-giang').text(baiGiang.tieu_de);
            $('#ngay-tao-bai-giang').text(baiGiang.ngay_tao);
            $('#noi-dung-bai-giang').html(baiGiang.noi_dung);
            $('#modal-chi-tiet-bai-giang').modal('show');
            return;
        }

        $.ajax({
            url: url,
            type: 'POST',
            dataType: 'json',
            success: function (response) {
                const baiGiang = response.data;
                cache[url] = baiGiang; // Lưu vào cache
                $('#tieu-de-bai-giang').text(baiGiang.tieu_de);
                $('#ngay-tao-bai-giang').text(baiGiang.ngay_tao);
                $('#noi-dung-bai-giang').html(baiGiang.noi_dung);
                $('#modal-chi-tiet-bai-giang').modal('show');
            },
            error: function (xhr) {
                alert('Đã xảy ra lỗi: ' + xhr.status + ' ' + xhr.statusText);
            }
        });
    });

    // Xử lý modal xóa bài giảng
    $(document).on('click', '.btn-xoa-bai-giang', function () {
        const url = $(this).data('url');
        const formXoaBaiGiang = $('#btn-confirm-xoa-bai-giang').parent('form');

        formXoaBaiGiang.attr('action', url);
        $('#modal-xoa-bai-giang').modal('show');
    });

    // --- BỔ SUNG: Xử lý khi nhấn nút 'Sửa Kho' ---
    $(document).on('click', '.btn-warning.btn-sm', function () {
        if ($(this).text().includes('Sửa Kho')) {
            // Điền dữ liệu kho hiện tại vào form trong modal
            $('#editCourseId').val(courseData.id);
            $('#editCourseTitle').val(courseData.title);
            $('#editCourseDescription').val(courseData.description);
            $('#editCourseCreationDate').val(courseData.creationDate);
            $('#editCourseLessonCount').val(courseData.lessonCount); // readonly

            // Hiển thị modal chỉnh sửa kho
            const editCourseModal = new bootstrap.Modal(
                document.getElementById('editCourseModal')
            );
            editCourseModal.show();
        }
    });

    // --- BỔ SUNG: Xử lý khi nhấn nút 'Lưu thay đổi' trong modal Sửa Kho ---
    $('#editCourseForm').on('submit', function (e) {
        e.preventDefault(); // Ngăn chặn form submit mặc định

        // Lấy dữ liệu từ form
        const updatedCourse = {
            id: $('#editCourseId').val(),
            title: $('#editCourseTitle').val(),
            description: $('#editCourseDescription').val(),
            creationDate: $('#editCourseCreationDate').val(),
            lessonCount: parseInt($('#editCourseLessonCount').val()), // Giữ nguyên số lượng bài giảng hiện tại
        };

        // Cập nhật dữ liệu mẫu (trong thực tế sẽ gửi dữ liệu này lên API backend)
        courseData = updatedCourse;

        // Cập nhật lại giao diện cột trái với thông tin mới
        loadCourseDetails(courseData);

        // Đóng modal
        const editCourseModalInstance = bootstrap.Modal.getInstance(
            document.getElementById('editCourseModal')
        );
        editCourseModalInstance.hide();

        alert('Đã lưu thay đổi cho Kho bài giảng: ' + updatedCourse.title);
    });

    // --- Bắt sự kiện click vào nút 'Xóa Kho' trong trang chi tiết ---
    $('#delete-doc-btn').on('click', function () {
        // Hiển thị modal xác nhận xóa kho
        $('#deleteCourseConfirmModal').modal('show');
    });
});
