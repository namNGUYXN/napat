$(document).ready(function () {

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

  // --- Sự kiện click nút "Chèn bài giảng đã chọn" ---
  // $('#insertSelectedLecturesBtn').on('click', function () {
  //   const chuongId = $('#chuongIdToInsert').val();


  //   if (!currentSelectedLectures.length) {
  //     alert('Vui lòng chọn ít nhất một bài giảng để chèn.');
  //     return;
  //   }

  //   console.log('Chèn bài giảng vào chương ID:', chuongId);
  //   console.log('Các bài giảng sẽ được chèn:', currentSelectedLectures);

  //   // --- Logic gửi dữ liệu lên server (API call) ---
  //   alert(`Đã chèn ${currentSelectedLectures.length} bài giảng vào chương ID ${chuongId}.`);
  //   $('#addLectureModal').modal('hide');
  // });













  const $addMemberModal = $('#addMemberModal');
  const $studentSearchInput = $('#studentSearch');
  const $searchStudentBtn = $('#searchStudentBtn');
  const $studentListBody = $('#studentListBody');
  const $noStudentsFoundAlert = $('#noStudentsFoundAlert');
  const $addSelectedMembersBtn = $('#addSelectedMembersBtn');

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
      { id: 1, name: 'Nguyễn Văn A', email: 'a@example.com', phone: '0901234567' },
      { id: 2, name: 'Trần Thị B', email: 'b@example.com', phone: '0909876543' },
      { id: 3, name: 'Lê Công C', email: 'c@example.com', phone: '0911223344' },
      { id: 4, name: 'Phạm Thị D', email: 'd@example.com', phone: '0988776655' },
      { id: 5, name: 'Hoàng Văn E', email: 'e@example.com', phone: '0933445566' },
    ];

    const filteredStudents = mockStudents.filter(student =>
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
      $studentListBody.html('<tr><td colspan="4" class="text-center">Không có sinh viên nào phù hợp.</td></tr>');
      $noStudentsFoundAlert.show();
      return;
    }

    students.forEach(student => {
      const row = `
                <tr>
                    <td>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input student-checkbox" value="${student.id}">
                        </div>
                    </td>
                    <td><span class="math-inline">${student.name}</td>
                    <td><span>${student.email}</span></td>
                    <td>${student.phone || 'N/A'}</td>
                </tr>
            `;
      $studentListBody.append(row);
    });
  }

  // --- Sự kiện click nút tìm kiếm ---
  $searchStudentBtn.on('click', function () {
    const searchTerm = $studentSearchInput.val().trim();
    searchStudents(searchTerm);
  });

  // --- Sự kiện nhấn Enter trong ô tìm kiếm ---
  $studentSearchInput.on('keypress', function (event) {
    if (event.key === 'Enter') {
      $searchStudentBtn.click();
    }
  });

  // --- Sự kiện hiển thị modal (reset trạng thái) ---
  $addMemberModal.on('show.bs.modal', function () {
    $studentSearchInput.val('');
    $studentListBody.html('<tr><td colspan="4" class="text-center">Nhập thông tin để tìm kiếm sinh viên.</td></tr>');
    $noStudentsFoundAlert.hide();
    allStudents = []; // Reset danh sách sinh viên
  });

  // --- Xử lý sự kiện click nút "Thêm vào lớp" ---
  $addSelectedMembersBtn.on('click', function () {
    const selectedStudents = [];
    $('.student-checkbox:checked').each(function () {
      const studentData = $(this).data('student');
      selectedStudents.push(studentData.id); // Hoặc toàn bộ đối tượng studentData
    });

    if (selectedStudents.length > 0) {
      console.log('Sinh viên được chọn để thêm:', selectedStudents);

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
      alert(`Đã chọn ${selectedStudents.length} sinh viên để thêm vào lớp.`);
      $addMemberModal.modal('hide');
      // Tùy chọn: Cập nhật lại danh sách thành viên trên trang
    } else {
      alert('Vui lòng chọn ít nhất một sinh viên để thêm vào lớp.');
    }
  });

  // --- Sự kiện hiển thị modal khi nút "Thêm vào lớp" được click ---
  $('#addNewLessonBtn').on('click', function () {
    $addMemberModal.modal('show');
  });
});






// Enable cho tooltips (bootstrap 5)
const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))


let listBaiTrongLop = {};

// Xử lý check tất cả bản ghi
$(document).on('change', '.check-all', function () {
  const isChecked = $(this).is(':checked');
  const tbody = $(this).parents('thead').next('tbody');

  tbody.find('.row-checkbox').prop('checked', isChecked);
});


$(document).on('change', '.row-checkbox', function () {
  // const tbody = $(this).parents('tbody');
  // const thead = tbody.prev('thead');
  // const total = tbody.find('.row-checkbox').length;
  // const checked = tbody.find('.row-checkbox:checked').length;

  // thead.find('.check-all').prop('checked', total === checked);

  handleCheckAllSelected($(this));
});

handleCheckAllSelected($('.row-checkbox:checked'));

function handleCheckAllSelected(element) {
  const tbody = element.parents('tbody');
  const thead = tbody.prev('thead');
  const total = tbody.find('.row-checkbox').length;
  const checked = tbody.find('.row-checkbox:checked').length;

  thead.find('.check-all').prop('checked', total === checked);
}


$.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('#csrfForm input[name="_token"]').val()
  }
});


$(document).on('click', '.btn-public-bai', function (e) {
  const checkbox = $('.row-checkbox');
  checkbox.each(function (index, element) {
    const idBai = parseInt($(element).data('id'));
    const congKhai = $(element).is(':checked') ? '1' : '0';

    listBaiTrongLop[idBai] = congKhai;
  });

  const url = window.location.pathname;

  $.ajax({
    url: `${url}/bai/cong-khai`,
    type: 'POST',
    data: {
      listBaiTrongLop: listBaiTrongLop
    },
    dataType: 'json',
    success: function (response) {
      window.location.reload();
      alert(response.message);
    },
    error: function (xhr) {
      alert("Đã xảy ra lỗi: " + xhr.status + ' ' + xhr.statusText);
    }
  });
  // e.preventDefault();
  // const temp = $('.row-checkbox:checked');
  // console.log(listBaiTrongLop);
});
