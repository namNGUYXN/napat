$(document).ready(function () {

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








$.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('#csrfForm input[name="_token"]').val()
  }
});

// Xử lý bài giảng
let cacheListBaiGiang = [];
let listBaiGiangSelected = [];

// Sự kiện khi chọn mục bài giảng
$('#select-muc-bai-giang').on('change', function () {
  const urlListBaiGiang = $(this).val();

  const alertKoMucBaiGiang = $('#alert-ko-muc-bai-giang');
  const sectionListBaiGiang = $('#section-list-bai-giang');

  // Khi select mục bài giảng được chọn
  if (urlListBaiGiang) {
    // alert(urlListBaiGiang);

    // Hiển thị list bài giảng và ẩn cảnh báo
    alertKoMucBaiGiang.hide();

    if (cacheListBaiGiang[urlListBaiGiang]) {
      const listBaiGiang = cacheListBaiGiang[urlListBaiGiang];
      renderListBaiGiangTheoHocPhan(listBaiGiang, urlListBaiGiang);
      $('#input-search-bai-giang').val(''); // Reset nội dung search khi đổi mục bg
      return;
    }

    $.ajax({
      url: urlListBaiGiang,
      type: 'POST',
      dataType: 'json',
      success: function (response) {
        const listBaiGiang = response.data;
        cacheListBaiGiang[urlListBaiGiang] = listBaiGiang;
        renderListBaiGiangTheoHocPhan(listBaiGiang, urlListBaiGiang);
        $('#input-search-bai-giang').val(''); // Reset nội dung search khi đổi mục bg
        // console.log(cacheListBaiGiang);
      },
      error: function (xhr) {
        alert('Đã xảy ra lỗi: ' + xhr.status + ' ' + xhr.statusText);
      }
    });
  } else {
    // Ẩn phần list bài giảng và hiển thị cảnh báo
    alertKoMucBaiGiang.show();
    $('#body-table-list-bai-giang').empty().append('<tr><td colspan="4" class="text-center">Chọn mục bài giảng để hiển thị bài giảng.</td></tr>');
  }
});

function renderListBaiGiangTheoHocPhan(listBaiGiang, url) {
  const idLopHoc = $('#info-lop-hoc').data('id-lop-hoc');
  const idChuong = $('#selected-lecture-insert-btn').data('id-chuong');
  const urlListBaiGiangTrongChuong = `/lop-hoc/${idLopHoc}/chuong/${idChuong}/bai-giang/list`;
  const bodyTableListBaiGiang = $('#body-table-list-bai-giang');

  bodyTableListBaiGiang.empty(); // Xóa nội dung cũ

  if (listBaiGiang.length === 0) {
    bodyTableListBaiGiang.append('<tr><td colspan="4" class="text-center">Không có bài giảng nào trong mục bài giảng này hoặc không tìm thấy kết quả.</td></tr>');
    return;
  }

  $.ajax({
    url: urlListBaiGiangTrongChuong,
    type: 'POST',
    dataType: 'json',
    success: function (response) {
      const listBaiGiangTuDB = response.data;
      // console.log(listBaiGiang);

      listBaiGiang.forEach((baiGiang, index) => {
        // Kiểm tra xem bài giảng đã được chọn trước đó trong mảng listBaiGiangSelected chưa
        const isChecked = listBaiGiangSelected.some(id => id === baiGiang.id);
        const isExists = listBaiGiangTuDB.some(element => element.id_bai_giang === baiGiang.id);
        const row = `
        <tr>
          <td>
            <div class="form-check">
              <input class="form-check-input lecture-checkbox" type="checkbox" 
                  value="${baiGiang.id}" ${isChecked ? 'checked' : ''}>
            </div>
          </td>
          <td>
            ${isExists ? '<small class="fst-italic text-muted me-2">(Đã có trong chương)</small>' : ''}
            ${baiGiang.tieu_de}
          </td>
          <td class="text-end">
            <button type="button" class="btn btn-sm btn-info lecture-detail-btn" 
                data-bs-toggle="modal" data-bs-target="#modal-chi-tiet-bai-giang" 
                data-index="${index}"
                data-url="${url}">
                <i class="fas fa-eye"></i> </button>
          </td>
        </tr>
    `;
        bodyTableListBaiGiang.append(row);
      });
    },
    error: function (xhr) {
      alert("Đã xảy ra lỗi: " + xhr.status + ' ' + xhr.statusText);
    }
  });


}

// Sự kiện xem chi tiết bài giảng trong modal
$(document).on('click', '.lecture-detail-btn', function () {
  const btn = $(this);
  const urlListBaiGiang = btn.data('url');
  const index = btn.data('index');
  const baiGiang = cacheListBaiGiang[urlListBaiGiang][index];
  // console.log(baiGiang);

  $('#tieu-de-bai-giang').text(baiGiang.tieu_de);
  $('#noi-dung-bai-giang').html(baiGiang.noi_dung);
});


// Sự kiện tìm kiếm bài giảng
let debounceTimer;

$('#input-search-bai-giang').on('keyup', function () {
  clearTimeout(debounceTimer);

  debounceTimer = setTimeout(function () {
    handleSearchBaiGiang(); // Gọi sau khi dừng gõ 300ms
  }, 300);
});

function handleSearchBaiGiang() {
  const urlListBaiGiang = $('#select-muc-bai-giang').val();
  if (!urlListBaiGiang) return; // Không tìm kiếm nếu chưa chọn mục bg

  const noiDungTimKiem = $('#input-search-bai-giang').val().toLowerCase().trim();
  const listBaiGiang = cacheListBaiGiang[urlListBaiGiang];

  const listBaiGiangSearch = listBaiGiang.filter(lecture =>
    lecture.tieu_de.toLowerCase().includes(noiDungTimKiem)
  );
  renderListBaiGiangTheoHocPhan(listBaiGiangSearch, urlListBaiGiang);
}

$(document).on('click', '.lecture-insert-btn', function () {
  resetModalGanBaiGiang();

  const btnGanBaiGiang = $('#selected-lecture-insert-btn');
  const urlGanBaiGiang = $(this).data('url');
  const idChuong = $(this).data('id-chuong');
  btnGanBaiGiang.data('url', urlGanBaiGiang);
  btnGanBaiGiang.data('id-chuong', idChuong);

  $('#modal-gan-bai-giang').modal('show');
});

function resetModalGanBaiGiang() {
  $('#select-muc-bai-giang').val('');
  $('#input-search-bai-giang').val('');
  $('#body-table-list-bai-giang').empty().append('<tr><td colspan="4" class="text-center">Chọn mục bài giảng để hiển thị bài giảng.</td></tr>');
  $('#alert-ko-muc-bai-giang').show();
  listBaiGiangSelected = [];
}


// Sự khiện check nút chọn bài giảng để insert
$(document).on('change', '.lecture-checkbox', function () {
  const lectureId = parseInt($(this).val());
  const isChecked = $(this).is(':checked');

  if (isChecked) {
    listBaiGiangSelected.push(lectureId);
  } else {
    listBaiGiangSelected = listBaiGiangSelected.filter(l => l.id !== lectureId);
  }
});



// Load chương vào lớp
loadListChuong();

function loadListChuong() {
  const idHocPhan = $('#info-lop-hoc').data('id-hoc-phan');
  const idLopHoc = $('#info-lop-hoc').data('id-lop-hoc');

  loadListBaiGiang((listBaiGiang) => {
    $.ajax({
      url: `/hoc-phan/${idHocPhan}/chuong/list`,
      type: 'POST',
      dataType: 'json',
      success: function (response) {
        const listChuong = response.data;

        const htmlListChuong = listChuong.map((chuong, index) => {
          // Lấy các bài giảng theo chương trong lớp
          const listBaiGiangTheoChuong = listBaiGiang.filter(baiGiang => baiGiang.chuong.id == chuong.id);

          const htmlListBaiGiang = listBaiGiangTheoChuong.map(baiGiang => {
            const urlGoBaiGiang = window.location.origin
              + `/lop-hoc/${idLopHoc}/chuong/${chuong.id}/bai-giang/${baiGiang.bai_giang.id}/go`;
            
            return `
              <div class="list-group-item list-group-item-action list-group-item-info d-flex justify-content-between align-items-center">
                <a href="#" class="text-decoration-none text-info-emphasis flex-grow-1">
                  ${baiGiang.bai_giang.tieu_de}
                </a>
                <button type="button" class="btn btn-sm btn-outline-danger lecture-remove-btn"
                  data-url="${urlGoBaiGiang}">
                  <i class="fas fa-times"></i>
                </button>
              </div>
            `;
          }).join('');

          return renderListChuongVaBaiGiang(index, chuong, htmlListBaiGiang);
        });

        $('#accordion-chuong').html(htmlListChuong.join(''));
      },
      error: function (xhr) {
        alert('Đã xảy ra lỗi: ' + xhr.status + ' ' + xhr.statusText);
      }
    });
  });

}

function loadListBaiGiang(callback) {
  const idLopHoc = $('#info-lop-hoc').data('id-lop-hoc');

  $.ajax({
    url: `/lop-hoc/${idLopHoc}/bai-giang/list`,
    type: 'POST',
    dataType: 'json',
    success: function (response) {
      const listBaiGiang = response.data;

      if (callback) callback(listBaiGiang.bai_giang_lop);
      else alert("OK");
    },
    error: function (xhr) {
      alert('Đã xảy ra lỗi: ' + xhr.status + ' ' + xhr.statusText);
    }
  });
}

function renderListChuongVaBaiGiang(index, chuong, htmlListBaiGiang) {
  const idLopHoc = $('#info-lop-hoc').data('id-lop-hoc');
  const url = window.location.origin + `/lop-hoc/${idLopHoc}/chuong/${chuong.id}/bai-giang/gan`;

  return `
    <div class="accordion-item">
      <h2 class="accordion-header" id="heading-${index}">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
          data-bs-target="#collapse-${index}" aria-expanded="false" aria-controls="collapse-${index}">
          ${chuong.tieu_de}
        </button>
      </h2>
      <div id="collapse-${index}" class="accordion-collapse collapse" aria-labelledby="heading-${index}">
        <div class="accordion-body">
          <div class="list-group">

            ${htmlListBaiGiang}

          </div>
          <div class="text-center mt-3">
            <button class="btn btn-sm btn-outline-primary lecture-insert-btn"
              data-url="${url}" data-id-chuong="${chuong.id}">
              <i class="fas fa-plus"></i> Chèn bài giảng
            </button>
          </div>
        </div>
      </div>
    </div>
  `;
}

// $('#modal-gan-bai-giang').on('hidden.bs.modal', function () {
//   console.log(listBaiGiangSelected);
// });

$('#selected-lecture-insert-btn').on('click', function (e) {
  const urlGanBaiGiang = $(this).data('url');

  $.ajax({
    url: urlGanBaiGiang,
    type: 'POST',
    data: {
      listIdBaiGiang: listBaiGiangSelected
    },
    dataType: 'json',
    success: function (response) {
      alert(response.message);

      if (response.success) {
        loadListChuong();
      }
    },
    error: function (xhr) {
      alert("Đã xảy ra lỗi: " + xhr.status + ' ' + xhr.statusText);
    }
  });

  resetModalGanBaiGiang();
  $('#modal-gan-bai-giang').modal('hide');
});


// Sự kiện xóa bài giảng khỏi lớp
$(document).on('click', '.lecture-remove-btn', function () {
  const urlGoBaiGiang = $(this).data('url');

  $.ajax({
    url: urlGoBaiGiang,
    type: 'DELETE',
    dataType: 'json',
    success: function (response) {
      alert(response.message);

      if (response.success) {
        loadListChuong();
      }
    },
    error: function (xhr) {
      alert("Đã xảy ra lỗi: " + xhr.status + ' ' + xhr.statusText);
    }
  });
});
