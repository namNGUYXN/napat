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
    formUpdate.data('url-detail', urlDetail);
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
      formUpdate.data('url-detail', urlDetail);
      $('#modal-chinh-sua-lop-hoc-phan').modal('show');
    },
    error: function (xhr) {
      alert('Đã xảy ra lỗi: ' + xhr.status + ' ' + xhr.statusText);
    }
  });
});

// Xử lý submit form chỉnh sửa lớp học phần
$('#modal-chinh-sua-lop-hoc-phan').closest('form').on('submit', function (e) {
  e.preventDefault();

  const token = $('meta[name="csrf-token"]').attr('content');
  const form = $(this);
  const urlUpdate = form.attr('action');
  const view = $('#modal-chinh-sua-lop-hoc-phan').data('view');
  const formData = new FormData(this); // Lấy tất cả input từ form, bao gồm file
  const params = new URLSearchParams(window.location.search);

  // Giá trị mặc định khi không có
  const currentSort = params.get('sort') || 'newest';
  const currentLimit = params.get('limit') || 3;
  const currentSearch = params.get('search') || '';
  const currentPage = params.get('page') || 1;

  formData.append('view', view);
  formData.append('sort', currentSort);
  formData.append('limit', currentLimit);
  formData.append('search', currentSearch);
  formData.append('page', currentPage);

  $.ajax({
    url: urlUpdate,
    type: 'POST',
    data: formData,
    dataType: 'json',
    contentType: false, // Để jQuery không set Content-Type
    processData: false, // Để không chuyển FormData thành chuỗi query
    headers: {
      'X-CSRF-TOKEN': token
    },
    dataType: 'json',
    success: function (response) {
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
      });

      // Reset input hinh_anh
      const imgPreview = $('#img-preview-container-modal-chinh-sua .img-preview');
      const imgUpload = $('#img-upload-modal-chinh-sua');
      const imgRemoveBtn = $('#img-preview-container-modal-chinh-sua .img-remove-btn');
      handleRemoveImg(imgPreview, imgUpload, imgRemoveBtn);

      $('#list-lop-hoc-phan').html(response.html);
      $('#modal-chinh-sua-lop-hoc-phan').modal('hide');

      // Xóa cache chi tiết lớp cũ
      delete cache[form.data('url-detail')];
    },
    error: function (xhr) {
      if (xhr.status === 422) {
        const errors = xhr.responseJSON.errors;

        if (errors.ten) {
          $('#ten-error').text(errors.ten[0]);
        }

        if (errors.id_khoa) {
          $('#id-khoa-error').text(errors.id_khoa[0]);
        }

        if (errors.id_bai_giang) {
          $('#id-bai-giang-error').text(errors.id_bai_giang[0]);
        }

        if (errors.mo_ta_ngan) {
          $('#mo-ta-ngan-error').text(errors.mo_ta_ngan[0]);
        }

        if (errors.hinh_anh) {
          $('#hinh-anh-error').text(errors.hinh_anh[0]);
        }
      } else {
        alert('Đã xảy ra lỗi: ' + xhr.status + ' ' + xhr.statusText);
      }
    }
  });
});

$('#modal-chinh-sua-lop-hoc-phan').on('hidden.bs.modal', function () {
  $(this).find('small[class*="text-danger"]').text('');
});

// Khi thay đổi sort, limit thì reload với tham số mới
$(document).on('change', '#sort-select, #limit-select', function () {
  redirectWithFilters();
});

// Khi submit thì cũng reload với tham số
$(document).on('submit', '#form-filter', function (e) {
  e.preventDefault();
  redirectWithFilters();
});

function redirectWithFilters() {
  const sort = $('#sort-select').val();
  const limit = $('#limit-select').val();
  const search = $('#search-input').val();
  const path = window.location.pathname;

  // Tạo query string
  const params = new URLSearchParams();

  // Chỉ thêm nếu search có giá trị
  if (search && search.trim() !== '') {
    params.set('search', search.trim());
  }

  // Chỉ thêm nếu khác giá trị mặc định
  if (sort && sort !== 'newest') {
    params.set('sort', sort);
  }

  if (limit && limit !== '3') {
    params.set('limit', limit);
  }

  // Tạo URL cuối cùng
  const queryString = params.toString();
  const url = path + (queryString ? `?${queryString}` : '');

  window.location.href = url;
}