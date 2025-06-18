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