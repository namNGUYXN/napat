$(document).ready(function () {
    $("#newNewsletterForm").on("submit", function (e) {});

    // --- Dữ liệu giả định ---
    // (Trong thực tế, bạn sẽ tải dữ liệu này từ API)
    // $(".btn-accept-request").click(function () {
    //     let id = $(this).data("id");
    //     $.ajax({
    //         url: `/thanh-vien-lop/${id}/chap-nhan`,
    //         method: "POST",
    //         data: {
    //             _token: "{{ csrf_token() }}",
    //         },
    //         success: function (res) {
    //             location.reload(); // hoặc xóa phần tử DOM nếu muốn mượt hơn
    //         },
    //     });
    // });
    $(".btn-accept-request").click(function () {
        let id = $(this).data("id");

        $.ajax({
            url: `/thanh-vien-lop/${id}/chap-nhan`,
            method: "POST",
            data: {
                _token: $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (res) {
                if (res.status) {
                    $(".card-body").html(res.html);
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
    });

    $(".btn-reject-request").click(function () {
        let id = $(this).data("id");

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
    });

    // Giả định danh sách các kho tài liệu
    const mockDocuments = [
        { id: 1, title: "Lập Trình Web Cơ Bản" },
        { id: 2, title: "Cấu trúc dữ liệu và giải thuật" },
        { id: 3, title: "Mạng máy tính cơ bản" },
        { id: 4, title: "Hệ điều hành Linux" },
    ];

    // Giả định danh sách bài giảng cho từng tài liệu
    // Khóa là document ID, giá trị là mảng các bài giảng
    const mockLecturesByDocument = {
        1: [
            // Bài giảng cho "Lập Trình Web Cơ Bản" (id: 1)
            {
                id: 101,
                title: "Giới thiệu HTML",
                description: "Cú pháp và thẻ cơ bản.",
                content:
                    "<p>HTML (HyperText Markup Language) là ngôn ngữ đánh dấu tiêu chuẩn để tạo các trang web. Nó mô tả cấu trúc của một trang web.</p><p>Các thẻ HTML cơ bản bao gồm &lt;h1&gt;, &lt;p&gt;, &lt;a&gt;, &lt;img&gt;.</p>",
            },
            {
                id: 102,
                title: "CSS selectors",
                description: "Cách chọn phần tử trong CSS.",
                content:
                    "<p>CSS selectors được sử dụng để 'tìm' (hoặc chọn) các phần tử HTML mà bạn muốn định kiểu.</p><p>Ví dụ: `p { color: blue; }` chọn tất cả các đoạn văn.</p>",
            },
            {
                id: 103,
                title: "JavaScript DOM",
                description: "Thao tác với cây DOM.",
                content:
                    "<p>DOM (Document Object Model) là một giao diện lập trình cho các tài liệu HTML và XML.</p><p>Nó đại diện cho cấu trúc của một trang web như một cây đối tượng.</p>",
            },
            {
                id: 104,
                title: "Form và Validation",
                description: "Xây dựng form và kiểm tra dữ liệu.",
                content:
                    "<p>Form HTML được sử dụng để thu thập dữ liệu người dùng.</p><p>Validation là quá trình kiểm tra xem dữ liệu do người dùng nhập vào có hợp lệ hay không.</p>",
            },
        ],
        2: [
            // Bài giảng cho "Cấu trúc dữ liệu và giải thuật" (id: 2)
            {
                id: 201,
                title: "Mảng và danh sách liên kết",
                description: "Cấu trúc dữ liệu tuyến tính.",
                content:
                    "<p>Mảng là tập hợp các phần tử có cùng kiểu dữ liệu được lưu trữ liên tiếp trong bộ nhớ.</p><p>Danh sách liên kết là tập hợp các nút, mỗi nút chứa dữ liệu và một con trỏ tới nút tiếp theo.</p>",
            },
            {
                id: 202,
                title: "Cây và đồ thị",
                description: "Cấu trúc dữ liệu phi tuyến tính.",
                content:
                    "<p>Cây là cấu trúc dữ liệu phân cấp bao gồm các nút được kết nối bởi các cạnh.</p><p>Đồ thị là tập hợp các đỉnh và các cạnh nối các đỉnh đó.</p>",
            },
            {
                id: 203,
                title: "Thuật toán tìm kiếm",
                description: "Binary search, linear search.",
                content:
                    "<p>Thuật toán tìm kiếm tuyến tính duyệt qua từng phần tử cho đến khi tìm thấy phần tử mong muốn.</p><p>Tìm kiếm nhị phân hiệu quả hơn cho các mảng đã sắp xếp.</p>",
            },
        ],
        3: [
            // Bài giảng cho "Mạng máy tính cơ bản" (id: 3)
            {
                id: 301,
                title: "Mô hình OSI và TCP/IP",
                description: "Các lớp mạng.",
                content:
                    "<p>Mô hình OSI và TCP/IP là các mô hình tham chiếu để hiểu cách thức hoạt động của mạng máy tính.</p>",
            },
            {
                id: 302,
                title: "Địa chỉ IP và Subnetting",
                description: "Phân chia mạng con.",
                content:
                    "<p>Địa chỉ IP là một nhãn số duy nhất được gán cho mỗi thiết bị được kết nối với mạng máy tính.</p>",
            },
        ],
        4: [
            // Bài giảng cho "Hệ điều hành Linux" (id: 4)
            {
                id: 401,
                title: "Cài đặt Ubuntu",
                description: "Hướng dẫn cài đặt hệ điều hành.",
                content:
                    "<p>Hướng dẫn từng bước cài đặt Ubuntu trên máy tính của bạn.</p>",
            },
            {
                id: 402,
                title: "Lệnh cơ bản Linux",
                description: "Các lệnh terminal phổ biến.",
                content:
                    "<p>Các lệnh Linux cơ bản như `ls`, `cd`, `mkdir`, `rm`.</p>",
            },
            {
                id: 403,
                title: "Quản lý quyền file",
                description: "Phân quyền người dùng.",
                content:
                    "<p>Hệ thống quyền trong Linux cho phép kiểm soát ai có thể đọc, ghi hoặc thực thi file.</p>",
            },
        ],
    };

    let currentSelectedLectures = []; // Mảng tạm thời để lưu các bài giảng đang được chọn trong modal

    // --- Hàm tải và hiển thị danh sách tài liệu vào select box ---
    function loadDocumentsIntoSelect() {
        const $documentSelect = $("#documentSelect");
        $documentSelect.empty(); // Xóa các option cũ
        $documentSelect.append('<option value="">-- Chọn tài liệu --</option>'); // Thêm option mặc định

        mockDocuments.forEach((doc) => {
            $documentSelect.append(
                `<option value="${doc.id}">${doc.title}</option>`
            );
        });
    }

    // --- Hàm render danh sách bài giảng vào bảng ---
    function renderLectures(lecturesToRender) {
        const $lecturesInDocumentBody = $("#lecturesInDocumentBody");
        $lecturesInDocumentBody.empty(); // Xóa nội dung cũ

        if (lecturesToRender.length === 0) {
            $lecturesInDocumentBody.append(
                '<tr><td colspan="4" class="text-center">Không có bài giảng nào trong tài liệu này hoặc không tìm thấy kết quả.</td></tr>'
            );
            return;
        }

        lecturesToRender.forEach((lecture) => {
            // Kiểm tra xem bài giảng này đã được chọn trước đó trong mảng currentSelectedLectures không
            const isChecked = currentSelectedLectures.some(
                (sl) => sl.id === lecture.id
            );
            const row = `
                <tr>
                    <td>
                        <div class="form-check">
                            <input class="form-check-input lecture-checkbox" type="checkbox" 
                                value="${lecture.id}" data-title="${
                lecture.title
            }" ${isChecked ? "checked" : ""}>
                        </div>
                    </td>
                    <td>${lecture.title}</td>
                    <td>${lecture.description || "Không có mô tả"}</td>
                    <td>
                        <button type="button" class="btn btn-sm btn-info view-lecture-detail-btn" 
                                data-bs-toggle="modal" data-bs-target="#lectureDetailModal" 
                                data-lecture-id="${lecture.id}" 
                                data-lecture-title="${lecture.title}" 
                                data-lecture-description="${
                                    lecture.description || "Không có mô tả"
                                }" 
                                data-lecture-content="${
                                    lecture.content ||
                                    "Không có nội dung chi tiết."
                                }">
                            <i class="fas fa-eye"></i> </button>
                    </td>
                </tr>
            `;
            $lecturesInDocumentBody.append(row);
        });
    }

    // --- Sự kiện khi modal "Chèn Bài Giảng" được hiển thị ---
    $("#addLectureModal").on("show.bs.modal", function (event) {
        const button = $(event.relatedTarget); // Nút đã kích hoạt modal
        const chuongId = button.data("chuong-id"); // Lấy ID chương từ nút

        // Lưu ID chương vào trường ẩn trong form
        $("#chuongIdToInsert").val(chuongId);

        // Reset các trường trong modal
        $("#documentSelect").val(""); // Reset select box
        $("#lectureSearchInput").val(""); // Reset thanh tìm kiếm
        $("#lecturesInDocumentBody")
            .empty()
            .append(
                '<tr><td colspan="4" class="text-center">Chọn tài liệu để hiển thị bài giảng.</td></tr>'
            ); // Chỉnh colspan
        $("#noDocumentSelectedAlert").show(); // Hiển thị thông báo
        $("#lectureListSection").hide(); // Ẩn phần danh sách bài giảng
        currentSelectedLectures = []; // Reset mảng các bài giảng đã chọn

        // Tải danh sách tài liệu vào select box
        loadDocumentsIntoSelect();
    });

    // --- Sự kiện khi chọn một tài liệu từ select box ---
    $("#documentSelect").on("change", function () {
        const selectedDocumentId = $(this).val();
        const $noDocumentSelectedAlert = $("#noDocumentSelectedAlert");
        const $lectureListSection = $("#lectureListSection");

        if (selectedDocumentId) {
            // Hiển thị phần danh sách bài giảng và ẩn cảnh báo
            $noDocumentSelectedAlert.hide();
            $lectureListSection.show();

            // Lấy danh sách bài giảng tương ứng với tài liệu đã chọn
            const lectures = mockLecturesByDocument[selectedDocumentId] || [];
            renderLectures(lectures); // Hiển thị danh sách ban đầu
            $("#lectureSearchInput").val(""); // Reset search input khi đổi tài liệu
        } else {
            // Ẩn phần danh sách bài giảng và hiển thị cảnh báo
            $noDocumentSelectedAlert.show();
            $lectureListSection.hide();
            $("#lecturesInDocumentBody")
                .empty()
                .append(
                    '<tr><td colspan="4" class="text-center">Chọn tài liệu để hiển thị bài giảng.</td></tr>'
                ); // Chỉnh colspan
        }
    });

    // --- Sự kiện tìm kiếm bài giảng ---
    $("#searchLectureBtn").on("click", function () {
        performLectureSearch();
    });

    $("#lectureSearchInput").on("keyup", function () {
        performLectureSearch(); // Tìm kiếm ngay khi gõ
    });

    function performLectureSearch() {
        const selectedDocumentId = $("#documentSelect").val();
        if (!selectedDocumentId) return; // Không tìm kiếm nếu chưa chọn tài liệu

        const searchTerm = $("#lectureSearchInput").val().toLowerCase();
        const allLectures = mockLecturesByDocument[selectedDocumentId] || [];

        const filteredLectures = allLectures.filter((lecture) =>
            lecture.title.toLowerCase().includes(searchTerm)
        );
        renderLectures(filteredLectures);
    }

    // --- Xử lý checkbox chọn/bỏ chọn bài giảng ---
    $(document).on("change", ".lecture-checkbox", function () {
        const lectureId = parseInt($(this).val());
        const lectureTitle = $(this).data("title");
        const isChecked = $(this).is(":checked");

        if (isChecked) {
            currentSelectedLectures.push({
                id: lectureId,
                title: lectureTitle,
            });
        } else {
            currentSelectedLectures = currentSelectedLectures.filter(
                (l) => l.id !== lectureId
            );
        }
        console.log("Bài giảng đã chọn hiện tại:", currentSelectedLectures);
    });

    // --- Sự kiện click nút "Chèn bài giảng đã chọn" ---
    $("#insertSelectedLecturesBtn").on("click", function () {
        const chuongId = $("#chuongIdToInsert").val();

        if (!currentSelectedLectures.length) {
            alert("Vui lòng chọn ít nhất một bài giảng để chèn.");
            return;
        }

        console.log("Chèn bài giảng vào chương ID:", chuongId);
        console.log("Các bài giảng sẽ được chèn:", currentSelectedLectures);

        // --- Logic gửi dữ liệu lên server (API call) ---
        alert(
            `Đã chèn ${currentSelectedLectures.length} bài giảng vào chương ID ${chuongId}.`
        );
        $("#addLectureModal").modal("hide");
    });

    // --- Xử lý sự kiện khi nút "Xem chi tiết" của bài giảng được click ---
    // Sử dụng event delegation vì các nút này được tạo động
    $(document).on("click", ".view-lecture-detail-btn", function () {
        const $button = $(this);
        // Lấy dữ liệu từ các thuộc tính data-* của nút
        const lectureId = $button.data("lecture-id");
        const lectureTitle = $button.data("lecture-title");
        const lectureDescription = $button.data("lecture-description");
        const lectureContent = $button.data("lecture-content");

        // Đổ dữ liệu vào các phần tử tương ứng trong modal chi tiết
        $("#detailLectureTitle").text(lectureTitle);
        $("#detailLectureFullTitle").text(lectureTitle);
        $("#detailLectureDescription").text(lectureDescription);
        $("#detailLectureContent").html(lectureContent); // Sử dụng .html() nếu nội dung có thể chứa HTML tags

        // Modal sẽ tự động mở do data-bs-toggle="modal" và data-bs-target="#lectureDetailModal" đã có trong HTML của nút

        // Ghi log để kiểm tra (tùy chọn)
        console.log(
            `Đang xem chi tiết bài giảng ID: ${lectureId}, Tiêu đề: "${lectureTitle}"`
        );
    });

    const $addMemberModal = $("#addMemberModal");
    const $studentSearchInput = $("#studentSearch");
    const $searchStudentBtn = $("#searchStudentBtn");
    const $studentListBody = $("#studentListBody");
    const $noStudentsFoundAlert = $("#noStudentsFoundAlert");
    const $addSelectedMembersBtn = $("#addSelectedMembersBtn");

    let allStudents = []; // Biến để lưu trữ tất cả sinh viên (hoặc kết quả tìm kiếm mới nhất)

    // --- Hàm giả lập tìm kiếm sinh viên (thay thế bằng API thực tế) ---
    function searchStudents(searchTerm) {
        // Trong thực tế, bạn sẽ gọi API ở đây
        // $.ajax({
        //     url: 'YOUR_API_ENDPOINT_FOR_SEARCH_STUDENTS',
        //     method: 'GET',
        //     data: { query: searchTerm },
        //     success: function(response) {
        //         allStudents = response.data; // Giả sử API trả về { data: [...] }
        //         renderStudentList(allStudents);
        //     },
        //     error: function(error) {
        //         console.error('Lỗi tìm kiếm sinh viên:', error);
        //         $studentListBody.html('<tr><td colspan="4" class="text-center text-danger">Lỗi khi tìm kiếm sinh viên.</td></tr>');
        //     }
        // });

        // --- Dữ liệu sinh viên giả định ---
        const mockStudents = [
            {
                id: 1,
                name: "Nguyễn Văn A",
                email: "a@example.com",
                phone: "0901234567",
            },
            {
                id: 2,
                name: "Trần Thị B",
                email: "b@example.com",
                phone: "0909876543",
            },
            {
                id: 3,
                name: "Lê Công C",
                email: "c@example.com",
                phone: "0911223344",
            },
            {
                id: 4,
                name: "Phạm Thị D",
                email: "d@example.com",
                phone: "0988776655",
            },
            {
                id: 5,
                name: "Hoàng Văn E",
                email: "e@example.com",
                phone: "0933445566",
            },
        ];

        const filteredStudents = mockStudents.filter(
            (student) =>
                student.name.toLowerCase().includes(searchTerm.toLowerCase()) ||
                student.email.toLowerCase().includes(searchTerm.toLowerCase())
        );
        allStudents = filteredStudents;
        renderStudentList(allStudents);
    }

    // --- Hàm hiển thị danh sách sinh viên ---
    function renderStudentList(students) {
        $studentListBody.empty();
        $noStudentsFoundAlert.hide();

        if (students.length === 0) {
            $studentListBody.html(
                '<tr><td colspan="4" class="text-center">Không có sinh viên nào phù hợp.</td></tr>'
            );
            $noStudentsFoundAlert.show();
            return;
        }

        students.forEach((student) => {
            const row = `
                <tr>
                    <td>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input student-checkbox" value="${
                                student.id
                            }">
                        </div>
                    </td>
                    <td><span class="math-inline">${student.name}</td>
                    <td><span>${student.email}</span></td>
                    <td>${student.phone || "N/A"}</td>
                </tr>
            `;
            $studentListBody.append(row);
        });
    }

    // --- Sự kiện click nút tìm kiếm ---
    $searchStudentBtn.on("click", function () {
        const searchTerm = $studentSearchInput.val().trim();
        searchStudents(searchTerm);
    });

    // --- Sự kiện nhấn Enter trong ô tìm kiếm ---
    $studentSearchInput.on("keypress", function (event) {
        if (event.key === "Enter") {
            $searchStudentBtn.click();
        }
    });

    // --- Sự kiện hiển thị modal (reset trạng thái) ---
    $addMemberModal.on("show.bs.modal", function () {
        $studentSearchInput.val("");
        $studentListBody.html(
            '<tr><td colspan="4" class="text-center">Nhập thông tin để tìm kiếm sinh viên.</td></tr>'
        );
        $noStudentsFoundAlert.hide();
        allStudents = []; // Reset danh sách sinh viên
    });

    // --- Xử lý sự kiện click nút "Thêm vào lớp" ---
    $addSelectedMembersBtn.on("click", function () {
        const selectedStudents = [];
        $(".student-checkbox:checked").each(function () {
            const studentData = $(this).data("student");
            selectedStudents.push(studentData.id); // Hoặc toàn bộ đối tượng studentData
        });

        if (selectedStudents.length > 0) {
            console.log("Sinh viên được chọn để thêm:", selectedStudents);

            // --- Gọi API để thêm sinh viên vào lớp (thay thế bằng API thực tế) ---
            // $.ajax({
            //     url: 'YOUR_API_ENDPOINT_FOR_ADD_STUDENTS',
            //     method: 'POST',
            //     contentType: 'application/json',
            //     data: JSON.stringify({ studentIds: selectedStudents }),
            //     success: function(response) {
            //         if (response.success) {
            //             alert('Đã thêm sinh viên vào lớp thành công!');
            //             $addMemberModal.modal('hide');
            //             // Tùy chọn: Cập nhật lại danh sách thành viên hiện tại trên trang
            //         } else {
            //             alert('Lỗi khi thêm sinh viên vào lớp: ' + response.message);
            //         }
            //     },
            //     error: function(error) {
            //         console.error('Lỗi thêm sinh viên vào lớp:', error);
            //         alert('Có lỗi xảy ra khi thêm sinh viên vào lớp. Vui lòng thử lại.');
            //     }
            // });

            // --- Giả lập thành công ---
            alert(
                `Đã chọn ${selectedStudents.length} sinh viên để thêm vào lớp.`
            );
            $addMemberModal.modal("hide");
            // Tùy chọn: Cập nhật lại danh sách thành viên trên trang
        } else {
            alert("Vui lòng chọn ít nhất một sinh viên để thêm vào lớp.");
        }
    });

    // --- Sự kiện hiển thị modal khi nút "Thêm vào lớp" được click ---
    $("#addNewLessonBtn").on("click", function () {
        $addMemberModal.modal("show");
    });

    var editor_config = {
        path_absolute: "/",
        selector: ".textarea-tiny",
        relative_urls: false,
        plugins: [
            "advlist autolink link image lists charmap print preview hr anchor pagebreak",
            "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
            "table emoticons template paste help",
        ],
        toolbar:
            "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | " +
            "bullist numlist outdent indent | link image | print preview media fullscreen | " +
            "forecolor backcolor emoticons | help",
        menu: {
            favs: {
                title: "My Favorites",
                items: "code visualaid | searchreplace | emoticons",
            },
        },
        menubar: "favs file edit view insert format tools table help",
        content_css: "css/content.css",
        file_picker_callback: function (callback, value, meta) {
            var x =
                window.innerWidth ||
                document.documentElement.clientWidth ||
                document.getElementsByTagName("body")[0].clientWidth;
            var y =
                window.innerHeight ||
                document.documentElement.clientHeight ||
                document.getElementsByTagName("body")[0].clientHeight;

            var cmsURL =
                editor_config.path_absolute +
                "laravel-filemanager?editor=" +
                meta.fieldname;
            if (meta.filetype == "image") {
                cmsURL = cmsURL + "&type=Images";
            } else {
                cmsURL = cmsURL + "&type=Files";
            }

            tinyMCE.activeEditor.windowManager.openUrl({
                url: cmsURL,
                title: "Filemanager",
                width: x * 0.8,
                height: y * 0.8,
                resizable: "yes",
                close_previous: "no",
                onMessage: (api, message) => {
                    callback(message.content);
                },
            });
        },
    };

    tinymce.init(editor_config);
});
