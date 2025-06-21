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