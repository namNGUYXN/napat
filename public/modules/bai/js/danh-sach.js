$.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('#csrfForm input[name="_token"]').val()
  }
});

let cache = {};

// Xử lý modal xem chi tiết bài
$(document).on('click', '.btn-detail-bai', function () {
  const url = $(this).data('url');

  if (cache[url]) {
    const bai = cache[url];
    $('#tieu-de-bai').text(bai.tieu_de);
    $('#noi-dung-bai').html(bai.noi_dung);
    $('#modal-chi-tiet-bai').modal('show');
    return;
  }

  $.ajax({
    url: url,
    type: 'POST',
    dataType: 'json',
    success: function (response) {
      const bai = response.data;
      cache[url] = bai; // Lưu vào cache
      $('#tieu-de-bai').text(bai.tieu_de);
      $('#noi-dung-bai').html(bai.noi_dung);
      $('#modal-chi-tiet-bai').modal('show');
    },
    error: function (xhr) {
      alert('Đã xảy ra lỗi: ' + xhr.status + ' ' + xhr.statusText);
    }
  });
});


// Xử lý modal xóa bài
// $(document).on('click', '.btn-delete-bai', function () {
//   const urlDelete = $(this).data('url');
//   const formDelete = $('#modal-xoa-bai').parent('form');

//   formDelete.attr('action', urlDelete);
//   $('#modal-xoa-bai').modal('show');
// });

// Xử lý modal xóa bài
$(document).on('click', '.btn-delete-bai', function () {
  const formData = new FormData(); // Lấy tất cả input từ form, bao gồm file
  const token = $('meta[name="csrf-token"]').attr('content');
  const urlDelete = $(this).data("url-delete");
  const urlDetail = $(this).data('url-detail');

  formData.append('_token', token);
  formData.append('_method', 'DELETE');

  Swal.fire({
    title: `Bạn có chắc chắn xóa bài này không?`,
    text: `Bạn sẽ không thể khôi phục bài này!`,
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Xóa",
    cancelButtonText: "Hủy",
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        url: urlDelete,
        type: 'POST',
        data: formData,
        contentType: false, // Để jQuery không set Content-Type
        processData: false, // Để không chuyển FormData thành chuỗi query
        dataType: "json",
        success: function (response) {
          // const Toast = Swal.mixin({
          //   toast: true,
          //   position: "top-end",
          //   width: "auto",
          //   showConfirmButton: false,
          //   timer: 3500,
          //   timerProgressBar: true,
          //   didOpen: (toast) => {
          //     toast.onmouseenter = Swal.stopTimer;
          //     toast.onmouseleave = Swal.resumeTimer;
          //   },
          // });

          // Toast.fire({
          //   icon: response.icon,
          //   title: response.message,
          // }).then(() => {
          //   window.location.href = urlDetail;
          // });

          Swal.fire({
            icon: response.icon,
            title: response.message,
          }).then(() => {
            window.location.href = urlDetail;
          });
        },
        error: function (xhr) {
          alert(
            "Đã xảy ra lỗi: " + xhr.status + " " + xhr.statusText
          );
        },
      });
    }
  });
});

// Xử lý modal xóa hàng loạt chương
$(document).on('submit', '#form-xoa-hang-loat-bai', function (e) {
  e.preventDefault();
  
  const formData = new FormData(this);
  const urlQuickDelete = $(this).attr("action");
  const urlDetail = $(this).data('url-detail');

  Swal.fire({
    title: `Bạn có chắc chắn xóa các bài đã chọn không?`,
    text: `Bạn sẽ không thể khôi phục bài này!`,
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Xóa",
    cancelButtonText: "Hủy",
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        url: urlQuickDelete,
        type: 'POST',
        data: formData,
        contentType: false, // Để jQuery không set Content-Type
        processData: false, // Để không chuyển FormData thành chuỗi query
        dataType: "json",
        success: function (response) {
          Swal.fire({
            icon: response.icon,
            title: response.message,
          }).then(() => {
            window.location.href = urlDetail;
          });
        },
        error: function (xhr) {
          alert(
            "Đã xảy ra lỗi: " + xhr.status + " " + xhr.statusText
          );
        },
      });
    }
  });
});


// Xử lý check tất cả bản ghi
$('#check-all').on('change', function () {
  const isChecked = $(this).is(':checked');
  $('.row-checkbox').prop('checked', isChecked);
});

$('.row-checkbox').on('change', function () {
  const total = $('.row-checkbox').length;
  const checked = $('.row-checkbox:checked').length;

  $('#check-all').prop('checked', total === checked);
});



// Xử lý cập nhật thứ tự bài
$('#list-bai').sortable({
  group: 'list',
  animation: 200,
  ghostClass: 'ghost',
  delay: 500,
  chosenClass: 'chosen',
  onSort: capNhatThuTu,
});

function capNhatThuTu() {
  const sortBai = $('#list-bai').sortable('toArray');
  const url = $('#url-cap-nhat-thu-tu-bai').data('url');

  $.ajax({
    url: url,
    type: 'PUT',
    data: {
      listThuTuBai: sortBai
    },
    dataType: 'json',
    success: function (response) {
      // console.log(response.message);
    },
    error: function (xhr) {
      alert("Đã xảy ra lỗi: " + xhr.status + ' ' + xhr.statusText);
      window.location.reload();
    }
  });
}