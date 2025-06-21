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

let cache = {};

$(document).on('click', '.btn-detail-chuong', function () {
  const url = $(this).data('url');
  const tieuDeChuong = $(this).data('tieu-de');

  // console.log(url, tieuDeChuong);

  if (cache[url]) {
    const listBai = cache[url];

    $('#tieu-de-chuong').text(tieuDeChuong);
    $('#section-list-bai').html(renderListBai(listBai));
    $('#modal-chi-tiet-chuong').modal('show');
    return;
  }

  $.ajax({
    url: url,
    type: 'POST',
    dataType: 'json',
    success: function (response) {
      const listBai = response.data;
      cache[url] = listBai; // Lưu vào cache

      $('#tieu-de-chuong').text(tieuDeChuong);
      $('#section-list-bai').html(renderListBai(listBai));
      $('#modal-chi-tiet-chuong').modal('show');
    },
    error: function (xhr) {
      alert('Đã xảy ra lỗi: ' + xhr.status + ' ' + xhr.statusText);
    }
  });
});

function renderListBai(listBai) {
  if (listBai.length == 0) {
    return `
      <tr class="text-center">
        <td colspan="3">Không có bài nào trong chương</td>
      </tr>
    `;
  }

  const html = listBai.map((bai, index) => {
    return `
      <tr>
        <th scope="row">${index + 1}</th>
        <td>${bai.tieu_de}</td>
        <td>${bai.ngay_tao}</td>
      </tr>
    `;
  }).join('');

  return html;
}

// Xử lý modal xóa bài giảng
$(document).on('click', '.btn-xoa-chuong', function () {
  const url = $(this).data('url');
  const formXoaChuong = $('#btn-confirm-xoa-chuong').parent('form');

  formXoaChuong.attr('action', url);
  $('#modal-xoa-chuong').modal('show');
});