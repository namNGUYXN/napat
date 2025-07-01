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


$.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('#csrfForm input[name="_token"]').val()
  }
});

// let cache = {};

// $(document).on('click', '.btn-detail-chuong', function () {
//   const url = $(this).data('url');
//   const tieuDeChuong = $(this).data('tieu-de');

//   // console.log(url, tieuDeChuong);

//   if (cache[url]) {
//     const listBai = cache[url];

//     $('#tieu-de-chuong').text(tieuDeChuong);
//     $('#section-list-bai').html(renderListBai(listBai));
//     $('#modal-chi-tiet-chuong').modal('show');
//     return;
//   }

//   $.ajax({
//     url: url,
//     type: 'POST',
//     dataType: 'json',
//     success: function (response) {
//       const listBai = response.data;
//       cache[url] = listBai; // Lưu vào cache

//       $('#tieu-de-chuong').text(tieuDeChuong);
//       $('#section-list-bai').html(renderListBai(listBai));
//       $('#modal-chi-tiet-chuong').modal('show');
//     },
//     error: function (xhr) {
//       alert('Đã xảy ra lỗi: ' + xhr.status + ' ' + xhr.statusText);
//     }
//   });
// });

// function renderListBai(listBai) {
//   if (listBai.length == 0) {
//     return `
//       <tr class="text-center">
//         <td colspan="3">Không có bài nào trong chương</td>
//       </tr>
//     `;
//   }

//   const html = listBai.map((bai, index) => {
//     return `
//       <tr>
//         <th scope="row">${index + 1}</th>
//         <td>${bai.tieu_de}</td>
//         <td>${bai.ngay_tao}</td>
//       </tr>
//     `;
//   }).join('');

//   return html;
// }

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
  const url = $(this).data('url');
  const formXoaChuong = $('#btn-confirm-xoa-chuong').parent('form');

  formXoaChuong.attr('action', url);
  $('#modal-xoa-chuong').modal('show');
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


$('#form-cap-nhat-thu-tu-chuong').on('submit', function (e) {
  e.preventDefault();

  var sort1 = $('#list-chuong').sortable('toArray');
  console.log(sort1);
});



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