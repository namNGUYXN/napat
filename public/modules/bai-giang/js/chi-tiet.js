$('#img-upload-modal-chinh-sua').on('change', function (event) {
  const file = event.target.files[0];
  const imgPreview = $('#img-preview-container-modal-chinh-sua .img-preview');
  const imgRemoveBtn = $('#img-preview-container-modal-chinh-sua .img-remove-btn');

  handleRenderImg(file, imgPreview, imgRemoveBtn);
});

function handleRenderImg(file, imgPreview, imgRemoveBtn) {
  if (file && file.type.startsWith('image/')) {
    const reader = new FileReader();

    reader.onload = function (e) {
      imgPreview.attr('src', e.target.result).show();
      imgRemoveBtn.show(); // Hiển thị nút xóa
    };

    reader.readAsDataURL(file);
  } else {
    // Nếu không có file nào được chọn hoặc file không phải là ảnh
    imgPreview.attr('src', '#').hide();
    imgRemoveBtn.hide(); // Ẩn nút xóa
  }
}

$('#img-preview-container-modal-chinh-sua .img-remove-btn').on('click', function () {
  const imgPreview = $('#img-preview-container-modal-chinh-sua .img-preview');
  const imgUpload = $('#img-upload-modal-chinh-sua');
  const imgRemoveBtn = $('#img-preview-container-modal-chinh-sua .img-remove-btn');

  handleRemoveImg(imgPreview, imgUpload, imgRemoveBtn);
});

function handleRemoveImg(imgPreview, imgUpload, imgRemoveBtn) {
  // Reset thuộc tính src của ảnh preview và ẩn nó đi
  imgPreview.attr('src', '#').hide();
  imgRemoveBtn.hide();

  // Reset giá trị của input file
  // Đây là cách để xóa file đã chọn khỏi input file
  imgUpload.val('');
}


// Xử lý modal xóa bài giảng
$(document).on('click', '.document-delete-btn', function () {
  const formData = new FormData(); // Lấy tất cả input từ form, bao gồm file
  const token = $('meta[name="csrf-token"]').attr('content');
  const urlDelete = $(this).data("url-delete");
  const urlMyLecture = $(this).data('url-my-lecture');

  formData.append('_token', token);
  formData.append('_method', 'DELETE');

  Swal.fire({
    title: `Bạn có chắc chắn xóa bài giảng này không?`,
    text: `Bạn sẽ không thể khôi phục bài giảng này!`,
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
          const Toast = Swal.mixin({
            toast: true,
            position: "top-end",
            width: "auto",
            showConfirmButton: false,
            timer: 3500,
            timerProgressBar: true,
            didOpen: (toast) => {
              toast.onmouseenter = Swal.stopTimer;
              toast.onmouseleave = Swal.resumeTimer;
            },
          });

          Toast.fire({
            icon: response.icon,
            title: response.message,
          }).then(() => {
            window.location.href = urlMyLecture;
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


$.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('#csrfForm input[name="_token"]').val()
  }
});

let cache = {};

$(document).on('click', '.btn-update-chuong', function () {
  // $('#modal-chinh-sua-chuong').modal('show');

  const urlDetail = $(this).data('url-detail');
  const urlUpdate = $(this).data('url-update');

  if (cache[urlDetail]) {
    const chuong = cache[urlDetail];
    $('#tieu-de-chuong').val(chuong.tieu_de);
    $('#mo-ta-ngan').val(chuong.mo_ta_ngan);
    $('#modal-chinh-sua-chuong').closest('form').attr('action', urlUpdate);
    $('#modal-chinh-sua-chuong').modal('show');
    return;
  }

  $.ajax({
    url: urlDetail,
    type: 'POST',
    dataType: 'json',
    success: function (response) {
      const chuong = response.data;
      cache[urlDetail] = chuong;

      $('#tieu-de-chuong').val(chuong.tieu_de);
      $('#mo-ta-ngan').val(chuong.mo_ta_ngan);
      $('#modal-chinh-sua-chuong').closest('form').attr('action', urlUpdate);
      $('#modal-chinh-sua-chuong').modal('show');

      // console.log(chuong)
    },
    error: function (xhr) {
      alert("Đã xảy ra lỗi: " + xhr.status + ' ' + xhr.statusText);
    }
  });
});


// Xử lý modal xóa chương
$(document).on('click', '.btn-xoa-chuong', function () {
  const formData = new FormData(); // Lấy tất cả input từ form, bao gồm file
  const token = $('meta[name="csrf-token"]').attr('content');
  const urlDelete = $(this).data("url-delete");
  const urlDetail = $(this).data('url-detail');

  formData.append('_token', token);
  formData.append('_method', 'DELETE');

  Swal.fire({
    title: `Bạn có chắc chắn xóa chương này không?`,
    text: `Bạn sẽ không thể khôi phục chương này!`,
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
$(document).on('submit', '#form-xoa-hang-loat-chuong', function (e) {
  e.preventDefault();
  
  const formData = new FormData(this);
  const urlQuickDelete = $(this).attr("action");
  const urlDetail = $(this).data('url-detail');

  Swal.fire({
    title: `Bạn có chắc chắn xóa các chương đã chọn không?`,
    text: `Bạn sẽ không thể khôi phục chương này!`,
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


// $('#form-cap-nhat-thu-tu-chuong').on('submit', function (e) {
//   e.preventDefault();

//   var sort1 = $('#list-chuong').sortable('toArray');
//   console.log(sort1);
// });



// Xử lý cập nhật thứ tự chương
$('#list-chuong').sortable({
  group: 'list',
  animation: 200,
  ghostClass: 'ghost',
  delay: 500,
  chosenClass: 'chosen',
  onSort: capNhatThuTu,
});

function capNhatThuTu() {
  const sortChuong = $('#list-chuong').sortable('toArray');
  const url = $('#url-cap-nhat-thu-tu-chuong').data('url');

  $.ajax({
    url: url,
    type: 'PUT',
    data: {
      listThuTuChuong: sortChuong
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

