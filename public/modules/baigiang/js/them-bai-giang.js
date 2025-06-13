$(document).ready(function () {
  // Xử lý sự kiện khi form được submit
  $('#addLessonForm').on('submit', function (e) {
    e.preventDefault(); // Ngăn chặn form submit mặc định

    // Kiểm tra validation của Bootstrap (HTML5 validation)
    if (this.checkValidity()) {
      // Lấy dữ liệu từ form
      const newLesson = {
        title: $('#lessonTitle').val(),
        author: $('#lessonAuthor').val(),
        date: $('#lessonDate').val(),
        content: $('#lessonContent').val()
      };

      console.log('Thông tin bài giảng mới:', newLesson);

      // Ở đây, bạn sẽ gửi dữ liệu `newLesson` đến backend của mình
      // thông qua AJAX (ví dụ: jQuery.ajax() hoặc fetch API).
      // Ví dụ:
      /*
      $.ajax({
          url: '/api/lessons', // Thay thế bằng API endpoint của bạn
          type: 'POST',
          contentType: 'application/json',
          data: JSON.stringify(newLesson),
          success: function(response) {
              alert('Bài giảng đã được thêm thành công!');
              // Reset form sau khi thêm thành công
              $('#addLessonForm')[0].reset();
              // Có thể chuyển hướng người dùng về trang danh sách bài giảng
              // window.location.href = 'danh-sach-bai-giang.html';
          },
          error: function(xhr, status, error) {
              alert('Có lỗi xảy ra khi thêm bài giảng: ' + error);
          }
      });
      */

      // Tạm thời, hiển thị thông báo thành công và reset form
      alert('Bài giảng "' + newLesson.title + '" đã được thêm thành công (demo)!');
      $('#addLessonForm')[0].reset(); // Reset form về trạng thái ban đầu
      $(this).removeClass('was-validated'); // Xóa trạng thái validate
    } else {
      // Nếu form không hợp lệ, thêm class 'was-validated' để hiển thị lỗi của Bootstrap
      $(this).addClass('was-validated');
    }
  });

  // Xử lý nút "Đặt lại"
  $('#addLessonForm').on('reset', function () {
    // Xóa trạng thái validate khi đặt lại form
    $(this).removeClass('was-validated');
  });

  // Tùy chọn: Nếu bạn muốn tự động điền ngày hiện tại vào trường ngày tạo
  // const today = new Date().toISOString().split('T')[0];
  // $('#lessonDate').val(today);


  var editor_config = {
    path_absolute: "/",
    selector: '.textarea-tiny',
    relative_urls: false,
    plugins: [
      'advlist autolink link image lists charmap print preview hr anchor pagebreak',
      'searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking',
      'table emoticons template paste help'
    ],
    toolbar: 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | ' +
      'bullist numlist outdent indent | link image | print preview media fullscreen | ' +
      'forecolor backcolor emoticons | help',
    menu: {
      favs: {
        title: 'My Favorites',
        items: 'code visualaid | searchreplace | emoticons'
      }
    },
    menubar: 'favs file edit view insert format tools table help',
    content_css: 'css/content.css',
    file_picker_callback: function (callback, value, meta) {
      var x = window.innerWidth || document.documentElement.clientWidth || document.getElementsByTagName(
        'body')[0].clientWidth;
      var y = window.innerHeight || document.documentElement.clientHeight || document
        .getElementsByTagName('body')[0].clientHeight;

      var cmsURL = editor_config.path_absolute + 'laravel-filemanager?editor=' + meta.fieldname;
      if (meta.filetype == 'image') {
        cmsURL = cmsURL + "&type=Images";
      } else {
        cmsURL = cmsURL + "&type=Files";
      }

      tinyMCE.activeEditor.windowManager.openUrl({
        url: cmsURL,
        title: 'Filemanager',
        width: x * 0.8,
        height: y * 0.8,
        resizable: "yes",
        close_previous: "no",
        onMessage: (api, message) => {
          callback(message.content);
        }
      });
    }
  };

  tinymce.init(editor_config);
});