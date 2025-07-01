$('#form-chinh-sua-bai').on('submit', async function (e) {
  e.preventDefault(); // Chặn submit mặc định

  // Hiển thị loading spinner
  $('#loading-overlay').removeClass('d-none').addClass('d-flex');

  const editor = tinymce.get('lecture-content');
  const temp = $('<div>').html(editor.getContent());

  const imgElements = temp.find('img');
  for (let i = 0; i < imgElements.length; i++) {
    const img = imgElements[i];
    const src = $(img).attr('src');

    if (src && src.startsWith('data:image/')) {
      const blob = dataURLtoBlob(src);
      const url = await uploadImage(blob);
      $(img).attr('src', url);
    }
  }

  // Cập nhật nội dung đã thay ảnh
  editor.setContent(temp.html());

  // Gửi form sau khi cập nhật
  // this.submit();

  const tieuDe = $('#lecture-title').val();
  const noiDung = editor.getContent();

  // console.log(tieuDe, noiDung);

  $.ajax({
    url: $(this).attr('action'),
    type: 'PUT',
    data: {
      _token: csrfToken,
      tieu_de: tieuDe,
      noi_dung: noiDung
    },
    dataType: 'json',
    success: function (response) {
      $('#loading-overlay').addClass('d-none').removeClass('d-flex');

      const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        width: 'auto',
        showConfirmButton: false,
        timer: 3500,
        timerProgressBar: true,
        didOpen: (toast) => {
          toast.onmouseenter = Swal.stopTimer;
          toast.onmouseleave = Swal.resumeTimer;
        }
      });

      Toast.fire({
        icon: response.icon,
        title: response.message
      }).then(() => {
        if (response.redirect_url)
          window.location.href = response.redirect_url
      });
    },
    error: function (xhr) {
      $('#loading-overlay').addClass('d-none').removeClass('d-flex');
      if (xhr.status === 422) {
        const errors = xhr.responseJSON.errors;

        // Hiển thị lỗi cho từng field (ví dụ với Bootstrap)
        if (errors.tieu_de) {
          $('#tieu-de-error').text(errors.tieu_de[0]);
        }

        if (errors.noi_dung) {
          $('#noi-dung-error').text(errors.noi_dung[0]);
        }
      } else {
        alert('Đã xảy ra lỗi: ' + xhr.status + ' ' + xhr.statusText);
      }
    }
  });
});

// Chuyển base64 -> blob
function dataURLtoBlob(dataurl) {
  const arr = dataurl.split(',');
  const mime = arr[0].match(/:(.*?);/)[1];
  const bstr = atob(arr[1]);
  let n = bstr.length;
  const u8arr = new Uint8Array(n);
  while (n--) u8arr[n] = bstr.charCodeAt(n);
  return new Blob([u8arr], {
    type: mime
  });
}

// Dùng jQuery Ajax upload ảnh
function uploadImage(blob) {
  return new Promise((resolve, reject) => {
    const formData = new FormData();
    formData.append('image', blob);
    formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

    $.ajax({
      url: uploadImageUrl,
      method: 'POST',
      data: formData,
      processData: false,
      contentType: false,
      success: function (res) {
        resolve(res.url || res.location || '');
      },
      error: function () {
        reject('');
        alert('Lỗi khi upload ảnh');
        $('#loading-overlay').addClass('d-none').removeClass('d-flex');
      }
    });
  });
}