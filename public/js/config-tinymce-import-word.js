var editor_config = {
  path_absolute: "/",
  selector: "textarea.tinymce",
  height: 500,
  relative_urls: false,
  // plugins: [
  //   "advlist autolink link image lists charmap print preview hr anchor pagebreak",
  //   "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
  //   "table emoticons template paste help",
  // ],
  // toolbar:
  //   "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | " +
  //   "bullist numlist outdent indent | link image | print preview media fullscreen | " +
  //   "forecolor backcolor emoticons | help | importWord",
  // menu: {
  //   favs: {
  //     title: "My Favorites",
  //     items: "code visualaid | searchreplace | emoticons",
  //   },
  // },
  // menubar: "favs file edit view insert format tools table help",
  plugins: [
    'advlist', 'autolink', 'link', 'image', 'lists', 'charmap', 'preview', 'anchor', 'pagebreak',
    'searchreplace', 'wordcount', 'visualblocks', 'code', 'fullscreen', 'insertdatetime', 'media',
    'table', 'emoticons', 'help', 'powerpaste'
  ],
  toolbar: 'undo redo | styles | bold italic | alignleft aligncenter alignright alignjustify | ' +
    'bullist numlist outdent indent | link image | print preview media fullscreen | ' +
    'forecolor backcolor emoticons | help | importWord',
  powerpaste_allow_local_images: true,
  powerpaste_word_import: 'prompt',
  powerpaste_html_import: 'prompt',
  menu: {
    favs: { title: 'My Favorites', items: 'code visualaid | searchreplace | emoticons' }
  },
  menubar: 'favs file edit view insert format tools table help',
  content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:16px }',
  content_css: false,
  paste_data_images: true,
  setup: function (editor) {
    editor.ui.registry.addButton('importWord', {
      text: 'Tải Word',
      onAction: function () {
        document.getElementById('upload-docx').click();
      }
    });

    // Khi người dùng chọn file Word
    document.getElementById('upload-docx').addEventListener('change', function (event) {
      // Hiển thị loading spinner
      $('#loading-overlay').removeClass('d-none').addClass('d-flex');

      const file = event.target.files[0];

      if (!file || file.type !== "application/vnd.openxmlformats-officedocument.wordprocessingml.document") {
        alert("Vui lòng chọn file .docx hợp lệ");
        return;
      }

      const reader = new FileReader();
      reader.onload = function (e) {
        mammoth.convertToHtml({ arrayBuffer: e.target.result })
          .then(function (result) {
            editor.setContent(result.value); // Gán HTML vào TinyMCE (ảnh còn dạng base64)
            $('#loading-overlay').addClass('d-none').removeClass('d-flex');
          })
          .catch(function (err) {
            console.error("Lỗi khi chuyển Word:", err);
          });
      };

      reader.readAsArrayBuffer(file);
    });
  },
  // images_upload_handler: function (blobInfo, success, failure) {
  //   // Chuẩn bị dữ liệu
  //   const formData = new FormData();
  //   formData.append('image', blobInfo.blob());
  //   formData.append('_token', csrfToken); // Laravel CSRF

  //   $.ajax({
  //     url: uploadImageUrl, // Laravel route upload
  //     method: 'POST',
  //     data: formData,
  //     processData: false,
  //     contentType: false,
  //     success: function (response) {
  //       if (response.url) {
  //         success(response.url); // TinyMCE sẽ gán ảnh vào nội dung
  //       } else {
  //         failure('Không nhận được URL ảnh');
  //       }
  //     },
  //     error: function () {
  //       failure('Lỗi upload ảnh');
  //     }
  //   });
  // },
  file_picker_callback: function (callback, value, meta) {
    var x =
      window.innerWidth ||
      document.documentElement.clientWidth ||
      document.getElementsByTagName("body")[0].clientWidth;
    var y =
      window.innerHeight ||
      document.documentElement.clientHeight ||
      document.getElementsByTagName("body")[0].clientHeight;

    var cmsURL =
      editor_config.path_absolute +
      "laravel-filemanager?editor=" +
      meta.fieldname;
    if (meta.filetype == "image") {
      cmsURL = cmsURL + "&type=Images";
    } else {
      cmsURL = cmsURL + "&type=Files";
    }

    tinyMCE.activeEditor.windowManager.openUrl({
      url: cmsURL,
      title: "Filemanager",
      width: x * 0.8,
      height: y * 0.8,
      resizable: "yes",
      close_previous: "no",
      onMessage: (api, message) => {
        callback(message.content);
      },
    });
  },
};

tinymce.init(editor_config);