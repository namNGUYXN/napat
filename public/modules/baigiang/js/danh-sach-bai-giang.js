$(document).ready(function () {
    // --- Dữ liệu mẫu (thực tế sẽ lấy từ API hoặc backend) ---

    // Dữ liệu mẫu cho KHO BÀI GIẢNG (cập nhật nhẹ để có thể chỉnh sửa)
    // let courseData = {
    //     // Dùng `let` thay vì `const` để có thể gán lại nếu lưu thay đổi
    //     id: 101,
    //     title: "Lập Trình Web Cơ Bản",
    //     description:
    //         "Khoá học này cung cấp kiến thức nền tảng về phát triển web, bao gồm HTML, CSS và JavaScript. Phù hợp cho người mới bắt đầu.",
    //     lessonCount: 5, // Sẽ được cập nhật động dựa vào lessonsData
    //     creationDate: "2023-01-01",
    // };

    // // Dữ liệu mẫu cho DANH SÁCH BÀI GIẢNG (giữ nguyên)
    // const lessonsData = [
    //     // ... (giữ nguyên nội dung lessonsData) ...
    //     {
    //         id: 1,
    //         courseId: 101,
    //         title: "Giới thiệu về HTML cơ bản",
    //         author: "Nguyễn Văn A",
    //         date: "2023-01-15",
    //         content:
    //             "Đây là nội dung chi tiết của bài giảng <strong>Giới thiệu về HTML cơ bản</strong>. Bài giảng này sẽ bao gồm các khái niệm như thẻ HTML, cấu trúc tài liệu, và một số thẻ cơ bản khác. Mục tiêu là giúp người học nắm vững nền tảng để bắt đầu xây dựng các trang web đơn giản. <br><br>HTML (HyperText Markup Language) là ngôn ngữ đánh dấu tiêu chuẩn để tạo các trang web. Cùng tìm hiểu sâu hơn nhé!",
    //     },
    //     {
    //         id: 2,
    //         courseId: 101,
    //         title: "CSS nâng cao: Flexbox và Grid",
    //         author: "Trần Thị B",
    //         date: "2023-02-20",
    //         content:
    //             "Nội dung chi tiết về <em>CSS nâng cao: Flexbox và Grid</em>. Bài này sẽ đi sâu vào hai công cụ bố cục mạnh mẽ nhất của CSS hiện nay, giúp bạn xây dựng các bố cục phức tạp và responsive một cách dễ dàng. Chúng ta sẽ xem xét cách Flexbox giải quyết việc sắp xếp các mục theo một chiều, trong khi Grid cho phép bố cục hai chiều.",
    //     },
    //     {
    //         id: 3,
    //         courseId: 101,
    //         title: "JavaScript cơ bản: Biến và Kiểu dữ liệu",
    //         author: "Lê Văn C",
    //         date: "2023-03-10",
    //         content:
    //             "Bài giảng này tập trung vào <strong>JavaScript cơ bản</strong>, bao gồm khái niệm về biến, các kiểu dữ liệu phổ biến như chuỗi, số, boolean, và cách khai báo cũng như sử dụng chúng trong lập trình. Đây là những kiến thức nền tảng quan trọng cho bất kỳ ai muốn học JavaScript.",
    //     },
    //     {
    //         id: 4,
    //         courseId: 101,
    //         title: "ReactJS: Xây dựng Component đầu tiên",
    //         author: "Phạm Thị D",
    //         date: "2023-04-05",
    //         content:
    //             "Bạn sẽ học cách xây dựng <em>Component đầu tiên</em> trong ReactJS, nền tảng cơ bản cho mọi ứng dụng React. Chúng ta sẽ đi qua các bước từ tạo một component đơn giản đến truyền props và quản lý state cơ bản.",
    //     },
    //     {
    //         id: 5,
    //         courseId: 101,
    //         title: "Thiết kế Responsive với Bootstrap",
    //         author: "Nguyễn Văn A",
    //         date: "2023-05-01",
    //         content:
    //             "Bài giảng này sẽ hướng dẫn bạn cách sử dụng Bootstrap để thiết kế các trang web <strong>responsive</strong>, đảm bảo trang web của bạn hiển thị tốt trên mọi thiết bị, từ máy tính để bàn đến điện thoại di động. Chúng ta sẽ tìm hiểu về hệ thống Grid của Bootstrap và các lớp tiện ích khác.",
    //     },
    // ];

    // // --- Hàm tải dữ liệu lên giao diện (giữ nguyên) ---

    // // Hàm tải thông tin Kho bài giảng vào cột trái
    // function loadCourseDetails(course) {
    //     $("#courseTitle").text(course.title);
    //     $("#courseId").text(course.id);
    //     $("#courseDescription").text(course.description);
    //     $("#lessonCount").text(course.lessonCount);
    //     $("#courseCreationDate").text(course.creationDate);
    // }

    // // Hàm tải danh sách bài giảng vào cột phải
    // function loadLessonList(lessons) {
    //     const $lessonListBody = $("#lessonListBody");
    //     $lessonListBody.empty(); // Xóa các bài giảng cũ trước khi thêm mới

    //     if (lessons.length === 0) {
    //         $lessonListBody.append(
    //             '<tr><td colspan="4" class="text-center">Không có bài giảng nào trong kho này.</td></tr>'
    //         );
    //         return;
    //     }

    //     lessons.forEach((lesson, index) => {
    //         const row = `
    //                 <tr>
    //                     <th scope="row">${index + 1}</th>
    //                     <td>${lesson.title}</td>
    //                     <td>${lesson.date}</td>
    //                     <td class="text-center">
    //                         <button class="btn btn-info btn-sm me-1 view-lesson-detail-btn" data-bs-toggle="modal" data-bs-target="#lessonDetailModal" data-lesson-id="${
    //                             lesson.id
    //                         }">
    //                             <i class="fas fa-eye"></i>
    //                         </button>
    //                         <a href="lecture-update.html" class="btn btn-warning btn-sm me-1 edit-lesson-btn"lesson.id}">
    //                             <i class="fas fa-edit"></i>
    //                         </a>
    //                         <button class="btn btn-danger btn-sm delete-lesson-btn" data-lesson-id="${
    //                             lesson.id
    //                         }">
    //                             <i class="fas fa-trash-alt"></i>
    //                         </button>
    //                     </td>
    //                 </tr>
    //             `;
    //         $lessonListBody.append(row);
    //     });
    // }

    // // --- Khởi tạo dữ liệu khi trang tải xong ---
    // courseData.lessonCount = lessonsData.length;
    // loadCourseDetails(courseData);
    // loadLessonList(lessonsData);

    // --- Xử lý sự kiện ---

    // Xử lý khi nhấn nút "Xem chi tiết" (icon mắt) của BÀI GIẢNG (giữ nguyên)
    $(document).on("click", ".view-lesson-detail-btn", function () {
        const lessonId = $(this).data("lesson-id");
        const lesson = lessonsData.find((l) => l.id === lessonId);

        if (lesson) {
            $("#modalLessonTitle").text(lesson.title);
            $("#detailId").text(lesson.id);
            $("#detailTitle").text(lesson.title);
            $("#detailAuthor").text(lesson.author);
            $("#detailDate").text(lesson.date);
            $("#detailContent").html(lesson.content);
        }
    });

    // Xử lý khi nhấn nút "Xóa" (icon thùng rác) của BÀI GIẢNG (giữ nguyên)
    $(document).on("click", ".delete-lesson-btn", function () {
        const lessonId = $(this).data("lesson-id");
        if (
            confirm(
                "Bạn có chắc chắn muốn xóa bài giảng này (ID: " +
                    lessonId +
                    ")?"
            )
        ) {
            alert("Đã xóa bài giảng có ID: " + lessonId + ".");
        }
    });

    // --- BỔ SUNG: Xử lý khi nhấn nút "Sửa Kho" ---
    $(document).on("click", ".btn-warning.btn-sm", function () {
        if ($(this).text().includes("Sửa Kho")) {
            // Điền dữ liệu kho hiện tại vào form trong modal
            $("#editCourseId").val(courseData.id);
            $("#editCourseTitle").val(courseData.title);
            $("#editCourseDescription").val(courseData.description);
            $("#editCourseCreationDate").val(courseData.creationDate);
            $("#editCourseLessonCount").val(courseData.lessonCount); // readonly

            // Hiển thị modal chỉnh sửa kho
            const editCourseModal = new bootstrap.Modal(
                document.getElementById("editCourseModal")
            );
            editCourseModal.show();
        }
    });

    // --- BỔ SUNG: Xử lý khi nhấn nút "Lưu thay đổi" trong modal Sửa Kho ---
    $("#editCourseForm").on("submit", function (e) {
        e.preventDefault(); // Ngăn chặn form submit mặc định

        // Lấy dữ liệu từ form
        const updatedCourse = {
            id: $("#editCourseId").val(),
            title: $("#editCourseTitle").val(),
            description: $("#editCourseDescription").val(),
            creationDate: $("#editCourseCreationDate").val(),
            lessonCount: parseInt($("#editCourseLessonCount").val()), // Giữ nguyên số lượng bài giảng hiện tại
        };

        // Cập nhật dữ liệu mẫu (trong thực tế sẽ gửi dữ liệu này lên API backend)
        courseData = updatedCourse;

        // Cập nhật lại giao diện cột trái với thông tin mới
        loadCourseDetails(courseData);

        // Đóng modal
        const editCourseModalInstance = bootstrap.Modal.getInstance(
            document.getElementById("editCourseModal")
        );
        editCourseModalInstance.hide();

        alert("Đã lưu thay đổi cho Kho bài giảng: " + updatedCourse.title);
    });

    // --- Bắt sự kiện click vào nút "Xóa Kho" trong trang chi tiết ---
    $("#delete-doc-btn").on("click", function () {
        // Hiển thị modal xác nhận xóa kho
        $("#deleteCourseConfirmModal").modal("show");
    });
});
