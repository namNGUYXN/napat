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

// Xóa dữ liệu form khi ản modal thêm
$('#modal-them-lop-hoc-phan').on('hidden.bs.modal', function () {
  const imgPreview = $('#img-preview-container-modal-them .img-preview');
  const imgUpload = $('#img-upload-modal-them');
  const imgRemoveBtn = $('#img-preview-container-modal-them .img-remove-btn');

  handleRemoveImg(imgPreview, imgUpload, imgRemoveBtn);

  $(this).closest('form')[0].reset();
});


let cache = {};

// Xử lý modal chỉnh sửa lớp học phần
$(document).on('click', '.btn-update-class', function () {
  const token = $('meta[name="csrf-token"]').attr('content');
  const pathStorage = $('#hinh-anh-lop-hoc-phan').data('url') + '/';
  const urlDetail = $(this).data('url-detail');
  const urlUpdate = $(this).data('url-update');
  const formUpdate = $('#modal-chinh-sua-lop-hoc-phan').parent('form');
  const selectKhoa = $('#modal-chinh-sua-lop-hoc-phan #select-khoa');
  const selectBaiGiang = $('#modal-chinh-sua-lop-hoc-phan #select-bai-giang');

  if (cache[urlDetail]) {
    const lopHocPhan = cache[urlDetail];
    const listOptionKhoa = selectKhoa.find('option');
    const optionKhoaSelected = selectKhoa.find(`option[value="${lopHocPhan.id_khoa}"]`);
    const listOptionBaiGiang = selectBaiGiang.find('option');
    const optionBaiGiangSelected = selectBaiGiang.find(`option[value="${lopHocPhan.id_bai_giang}"]`);

    $('#ten-lop-hoc-phan').val(lopHocPhan.ten);
    listOptionKhoa.removeAttr('selected');
    optionKhoaSelected.attr('selected', true);
    listOptionBaiGiang.removeAttr('selected');
    optionBaiGiangSelected.attr('selected', true);
    $('#mo-ta-lop-hoc-phan').val(lopHocPhan.mo_ta_ngan);
    $('#hinh-anh-lop-hoc-phan').attr('src', pathStorage + lopHocPhan.hinh_anh);
    formUpdate.attr('action', urlUpdate);
    $('#modal-chinh-sua-lop-hoc-phan').modal('show');
    return;
  }

  $.ajax({
    url: urlDetail,
    type: 'POST',
    data: {
      _token: token
    },
    dataType: 'json',
    success: function (response) {
      const lopHocPhan = response.data;
      const listOptionKhoa = selectKhoa.find('option');
      const optionKhoaSelected = selectKhoa.find(`option[value="${lopHocPhan.id_khoa}"]`);
      const listOptionBaiGiang = selectBaiGiang.find('option');
      const optionBaiGiangSelected = selectBaiGiang.find(`option[value="${lopHocPhan.id_bai_giang}"]`);

      // Lưu vào cache
      cache[urlDetail] = lopHocPhan;

      $('#ten-lop-hoc-phan').val(lopHocPhan.ten);
      listOptionKhoa.removeAttr('selected');
      optionKhoaSelected.attr('selected', true);
      listOptionBaiGiang.removeAttr('selected');
      optionBaiGiangSelected.attr('selected', true);
      $('#mo-ta-lop-hoc-phan').val(lopHocPhan.mo_ta_ngan);
      $('#hinh-anh-lop-hoc-phan').attr('src', pathStorage + lopHocPhan.hinh_anh);
      formUpdate.attr('action', urlUpdate);
      $('#modal-chinh-sua-lop-hoc-phan').modal('show');
    },
    error: function (xhr) {
      alert('Đã xảy ra lỗi: ' + xhr.status + ' ' + xhr.statusText);
    }
  });

});