$('#img-upload-modal-them').on('change', function (event) {
  const file = event.target.files[0];
  const imgPreview = $('#img-preview-container-modal-them .img-preview');
  const imgRemoveBtn = $('#img-preview-container-modal-them .img-remove-btn');

  handleRenderImg(file, imgPreview, imgRemoveBtn);
});

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

$('#img-preview-container-modal-them .img-remove-btn').on('click', function () {
  const imgPreview = $('#img-preview-container-modal-them .img-preview');
  const imgUpload = $('#img-upload-modal-them');
  const imgRemoveBtn = $('#img-preview-container-modal-them .img-remove-btn');

  handleRemoveImg(imgPreview, imgUpload, imgRemoveBtn);
});

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

// Xử lý modal chỉnh sửa bài giảng
$('.document-edit-btn').on('click', function () {
  const pathStorage = $('#hinh-anh-bai-giang').data('url') + '/';
  const urlDetail = $(this).data('url-detail');
  const urlUpdate = $(this).data('url-update');
  const formUpdate = $('#modal-chinh-sua-bai-giang').parent('form');
  const selectHocPhan = $('#modal-chinh-sua-bai-giang select');

  if (cache[urlDetail]) {
    const baiGiang = cache[urlDetail];
    const optionHocPhan = selectHocPhan.find('option');
    const optionHocPhanSelected = selectHocPhan.find(`option[value="${baiGiang.id_hoc_phan}"]`);

    $('#ten-bai-giang').val(baiGiang.ten);
    optionHocPhan.removeAttr('selected');
    optionHocPhanSelected.attr('selected', true);
    $('#mo-ta-bai-giang').val(baiGiang.mo_ta_ngan);
    $('#hinh-anh-bai-giang').attr('src', pathStorage + baiGiang.hinh_anh);
    formUpdate.attr('action', urlUpdate);
    $('#modal-chinh-sua-bai-giang').modal('show');
    return;
  }

  $.ajax({
    url: urlDetail,
    type: 'POST',
    dataType: 'json',
    success: function (response) {
      const baiGiang = response.data;
      const optionHocPhan = selectHocPhan.find('option');
      const optionHocPhanSelected = selectHocPhan.find(`option[value="${baiGiang.id_hoc_phan}"]`);

      cache[urlDetail] = baiGiang; // Lưu vào cache
      $('#ten-bai-giang').val(baiGiang.ten);
      optionHocPhan.removeAttr('selected');
      optionHocPhanSelected.attr('selected', true);
      $('#mo-ta-bai-giang').val(baiGiang.mo_ta_ngan);
      $('#hinh-anh-bai-giang').attr('src', pathStorage + baiGiang.hinh_anh);
      formUpdate.attr('action', urlUpdate);
      $('#modal-chinh-sua-bai-giang').modal('show');
    },
    error: function (xhr) {
      alert('Đã xảy ra lỗi: ' + xhr.status + ' ' + xhr.statusText);
    }
  });
});


// Xử lý modal xóa bài giảng
$('.document-delete-btn').on('click', function () {
  // console.log($(this).data('url-delete'));
  const urlDelete = $(this).data('url-delete');
  const formDelete = $('#modal-xoa-bai-giang').parent('form');

  formDelete.attr('action', urlDelete);
  $('#modal-xoa-bai-giang').modal('show');
});

// Xử lý xóa dữ liệu khi modal thêm ẩn
$('#modal-them-bai-giang').on('hidden.bs.modal', function () {
  const imgPreview = $('#img-preview-container-modal-them .img-preview');
  const imgUpload = $('#img-upload-modal-them');
  const imgRemoveBtn = $('#img-preview-container-modal-them .img-remove-btn');

  handleRemoveImg(imgPreview, imgUpload, imgRemoveBtn);

  $(this).find('input[name="ten"]').val('');
  $(this).find('textarea[name="mo_ta_ngan"]').val('');
});