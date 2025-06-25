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


$.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('#csrfForm input[name="_token"]').val()
  }
});

const urlCapNhatThuTu = $('#info-menu').data('url');

function initSortableRecursively(container) {
  // Gắn Sortable cho chính container
  new Sortable(container, {
    animation: 200,
    group: {
      name: 'nested',
      pull: false,
      put: false
    },
    draggable: 'li', // RẤT QUAN TRỌNG: Cho phép kéo cả cha
    ghostClass: 'sortable-ghost',
    onEnd: function (evt) {
      const parent = evt.to.closest("li");
      // const parentId = parent ? parent.dataset.id : null;
      const items = Array.from(evt.to.children).filter(el => el.tagName === "LI");

      // const order = items.map((el, i) => ({
      //   id: el.dataset.id,
      //   parent_id: parentId,
      //   position: i + 1
      // }));

      const order = items.map((el, i) => ({
        id: el.dataset.id,
        thu_tu: i + 1
      }));

      $.ajax({
        url: urlCapNhatThuTu,
        type: 'POST',
        data: {
          listThuTuMenu: order
        },
        dataType: 'json',
        success: function (response) {
          // console.log(response.message);
        },
        error: function (xhr) {
          alert('Đã xảy ra lỗi: ' + xhr.status + ' ' + xhr.statusText);
        }
      });
    }
  });

  // Đệ quy cho mọi <ul> con bên trong
  container.querySelectorAll(':scope > li > ul').forEach(childList => {
    initSortableRecursively(childList);
  });
}

// Bắt đầu từ root
initSortableRecursively(document.getElementById('menu-root'));

// $('.btn-save').on('click', function () {
//   console.log("Cập nhật vị trí mới:", order);
// })

$('#modal-cap-nhat-thu-tu').on('hidden.bs.modal', function () {
  window.location.reload();
});