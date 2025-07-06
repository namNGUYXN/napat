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


// Xử lý modal thêm bài giảng
$('#modal-them-bai-giang').closest('form').on('submit', function (e) {
  e.preventDefault();

  const urlCreate = $(this).attr('action');
  const token = $('meta[name="csrf-token"]').attr('content');
  const view = $('#modal-chinh-sua-lop-hoc-phan').data('view');
  const formData = new FormData(this);
  const params = new URLSearchParams(window.location.search);

  // Giá trị mặc định khi không có
  const currentSort = params.get('sort') || 'newest';
  const currentLimit = params.get('limit') || 3;
  const currentSearch = params.get('search') || '';
  const currentPage = params.get('page') || 1;

  formData.append('_token', token);
  formData.append('view', view);
  formData.append('sort', currentSort);
  formData.append('limit', currentLimit);
  formData.append('search', currentSearch);
  formData.append('page', currentPage);

  $.ajax({
    url: urlCreate,
    type: 'POST',
    data: formData,
    contentType: false, // Để jQuery không set Content-Type
    processData: false, // Để không chuyển FormData thành chuỗi query
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
      const imgPreview = $('#img-preview-container-modal-them .img-preview');
      const imgUpload = $('#img-upload-modal-them');
      const imgRemoveBtn = $('#img-preview-container-modal-them .img-remove-btn');
      handleRemoveImg(imgPreview, imgUpload, imgRemoveBtn);

      $('#list-bai-giang').html(response.html);
      $('#modal-them-bai-giang').modal('hide');
    },
    error: function (xhr) {
      if (xhr.status === 422) {
        const errors = xhr.responseJSON.errors;
        const modal = $('#modal-them-bai-giang');

        if (errors.ten) {
          modal.find('.ten-error').text(errors.ten[0]);
        }

        if (errors.mo_ta_ngan) {
          modal.find('.mo-ta-ngan-error').text(errors.mo_ta_ngan[0]);
        }

        if (errors.hinh_anh) {
          modal.find('.hinh-anh-error').text(errors.hinh_anh[0]);
        }
      } else {
        alert('Đã xảy ra lỗi: ' + xhr.status + ' ' + xhr.statusText);
      }
    }
  });
});


// Xử lý xóa dữ liệu khi modal thêm ẩn
$('#modal-them-bai-giang').on('hidden.bs.modal', function () {
  $(this).find('small[class*="text-danger"]').text('');
  const imgPreview = $('#img-preview-container-modal-them .img-preview');
  const imgUpload = $('#img-upload-modal-them');
  const imgRemoveBtn = $('#img-preview-container-modal-them .img-remove-btn');
  handleRemoveImg(imgPreview, imgUpload, imgRemoveBtn);

  $(this).closest('form')[0].reset();
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


$.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('#csrfForm input[name="_token"]').val()
  }
});

let cache = {};

// Xử lý modal chỉnh sửa bài giảng
$(document).on('click', '.document-edit-btn', function () {
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
    formUpdate.data('url-detail', urlDetail);
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
      formUpdate.data('url-detail', urlDetail);
      $('#modal-chinh-sua-bai-giang').modal('show');
    },
    error: function (xhr) {
      alert('Đã xảy ra lỗi: ' + xhr.status + ' ' + xhr.statusText);
    }
  });
});


// Xử lý submit form chỉnh sửa bài giảng
$('#modal-chinh-sua-bai-giang').closest('form').on('submit', function (e) {
  e.preventDefault();

  const form = $(this);
  const urlUpdate = form.attr('action');
  const formData = new FormData(this); // Lấy tất cả input từ form, bao gồm file
  const params = new URLSearchParams(window.location.search);

  // Giá trị mặc định khi không có
  const currentSort = params.get('sort') || 'newest';
  const currentLimit = params.get('limit') || 3;
  const currentSearch = params.get('search') || '';
  const currentPage = params.get('page') || 1;

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

      $('#list-bai-giang').html(response.html);
      $('#modal-chinh-sua-bai-giang').modal('hide');

      // Xóa cache chi tiết bài giảng cũ
      delete cache[form.data('url-detail')];
    },
    error: function (xhr) {
      if (xhr.status === 422) {
        const errors = xhr.responseJSON.errors;
        const modal = $('#modal-chinh-sua-bai-giang');

        if (errors.ten) {
          modal.find('.ten-error').text(errors.ten[0]);
        }

        if (errors.mo_ta_ngan) {
          modal.find('.mo-ta-ngan-error').text(errors.mo_ta_ngan[0]);
        }

        if (errors.hinh_anh) {
          modal.find('.hinh-anh-error').text(errors.hinh_anh[0]);
        }
      } else {
        alert('Đã xảy ra lỗi: ' + xhr.status + ' ' + xhr.statusText);
      }
    }
  });
});

$('#modal-chinh-sua-bai-giang').on('hidden.bs.modal', function () {
  $(this).find('small[class*="text-danger"]').text('');
  // Reset input hinh_anh
  const imgPreview = $('#img-preview-container-modal-chinh-sua .img-preview');
  const imgUpload = $('#img-upload-modal-chinh-sua');
  const imgRemoveBtn = $('#img-preview-container-modal-chinh-sua .img-remove-btn');
  handleRemoveImg(imgPreview, imgUpload, imgRemoveBtn);
});


// Xử lý modal xóa bài giảng
$(document).on('click', '.document-delete-btn', function () {
  const formData = new FormData(); // Lấy tất cả input từ form, bao gồm file
  const params = new URLSearchParams(window.location.search);
  const token = $('meta[name="csrf-token"]').attr('content');
  const urlDelete = $(this).data("url-delete");

  // Giá trị mặc định khi không có
  const currentSort = params.get('sort') || 'newest';
  const currentLimit = params.get('limit') || 3;
  const currentSearch = params.get('search') || '';
  const currentPage = params.get('page') || 1;

  formData.append('_token', token);
  formData.append('_method', 'DELETE');
  formData.append('sort', currentSort);
  formData.append('limit', currentLimit);
  formData.append('search', currentSearch);
  formData.append('page', currentPage);

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
          });

          $('#list-bai-giang').html(response.html);
        },
        error: function (xhr) {
          alert(
            "Đã xảy ra lỗi: " + xhr.status + " " + xhr.statusText
          );
        },
      });
    }
  });

  formDelete.attr('action', urlDelete);
  $('#modal-xoa-bai-giang').modal('show');
});