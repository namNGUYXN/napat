@extends('layouts.admin')

@section('content')
  <div class="col bg-light p-4 overflow-auto custom-scrollbar">
    <h2 class="mb-4">Thêm Menu mới</h2>

    <a href="{{ route('menu.index') }}" class="btn btn-outline-secondary mb-4">
      <i class="fas fa-arrow-alt-circle-left me-2"></i>Danh sách Menu
    </a>

    @if (session('message'))
      <div class="alert alert-{{ session('status') }} alert-dismissible fade show" role="alert">
        {{ session('message') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

    <div class="card shadow-sm">
      <div class="card-header bg-success text-white">
        <h5 class="mb-0">Thông tin Menu mới</h5>
      </div>
      <div class="card-body">
        <form id="form-them-menu" action="{{ route('menu.store') }}" method="POST">
          @csrf
          <div class="mb-3">
            <label for="" class="form-label">Tên menu: <span class="text-danger">*</span></label>
            <input type="text" class="form-control @error('ten') is-invalid @enderror" name="ten"
              value="{{ old('ten') }}">
            @error('ten')
              <span class="invalid-feedback">{{ $message }}</span>
            @enderror
          </div>

          <div class="mb-3">
            <label for="" class="form-label">Thuộc menu:</label>
            <select class="form-select" name="id_menu_cha">
              <option selected value="">Là menu chính</option>
              @foreach ($listMenu as $menu)
                <option value="{{ $menu['id'] }}" {{ old('id_menu_cha') == $menu['id'] ? 'selected' : '' }}>
                  {{ $menu['ten'] }}</option>
              @endforeach
            </select>
          </div>

          <div class="mb-3">
            <label for="" class="form-label">Loại menu:</label>
            <select class="form-select @error('id_loai_menu') is-invalid  @enderror" id="select-loai-menu"
              name="id_loai_menu">
              <option selected disabled value="">--- Chọn loại menu ---</option>
              @foreach ($listLoaiMenu as $loaiMenu)
                <option value="{{ $loaiMenu->id }}" {{ old('id_loai_menu') == $loaiMenu->id ? 'selected' : '' }}>
                  {{ $loaiMenu->ten }}</option>
              @endforeach
            </select>
            @error('id_loai_menu')
              <span class="invalid-feedback">{{ $message }}</span>
            @enderror
          </div>

          <div id="sub-box-select"></div>

          <div class="d-flex justify-content-end">
            <button type="reset" class="btn btn-secondary me-2">Đặt lại</button>
            <button type="submit" class="btn btn-primary">Tạo mới</button>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection

@section('scripts')
  <script>
    // Lấy list từ MenuController truyền qua
    var listKhoa = @php echo $listKhoa @endphp;
    var listHocPhan = @php echo $listHocPhan @endphp;

    // Tạo chuỗi html list option
    const listKhoaOption = createListOption('khoa', listKhoa);
    const listHocPhanOption = createListOption('học phần', listHocPhan);

    $('#select-loai-menu').on('change', function() {
      const menuType = Number($(this).val());
      xuLySelectLoaiMenuChange(menuType);
    });

    // Khi có option loại menu được chọn
    if ($('#select-loai-menu').val()) {
      const menuType = Number($('#select-loai-menu').val());
      xuLySelectLoaiMenuChange(menuType);
    }

    $('button[type="reset"]').on('click', function() {
      $('#sub-box-select').html('');
    });

    $('#form-them-menu').on('change', '#select-khoa', function() {
      const idKhoa = $(this).val();
      const listHocPhanTheoId = [];

      for (let hocPhan of listHocPhan) {
        if (hocPhan.id_khoa == idKhoa) {
          listHocPhanTheoId.push(hocPhan);
        }
      }

      const listHocPhanTheoIdOption = createListOption('học phần', listHocPhanTheoId);

      $('#select-hoc-phan').html(listHocPhanTheoIdOption);
    });

    // Hàm xử lý chọn loại menu khoa, học phần
    function xuLySelectLoaiMenuChange(menuType) {
      switch (menuType) {
        case 4: // Chỉ mục khoa
          $('#sub-box-select').html(renderBoxSelect('khoa'));
          break;
        case 5: // Chỉ mục học phần
          $('#sub-box-select').html(renderBoxSelect('học phần'));
          break;
        default:
          $('#sub-box-select').html('');
      }
    }

    // Hàm tạo list thẻ option
    function createListOption(item, list) {
      const mapList = $.map(list, function(element, index) {
        return `
         <option value="${element.id}">${element.ten}</option>
        `;
      });
      mapList.unshift(`<option selected disabled value="">--- Chọn một ${item} ---</option>`);

      return mapList.join('');
    }

    // Hàm hiển thị box select khi loại menu là khoa, học phần
    function renderBoxSelect(item) {
      if (item === 'khoa') {
        return `
        <div class="mb-3">
          <label for="" class="form-label">Chọn khoa:</label>
          <select class="form-select" id="" name="gia_tri">
            ${listKhoaOption}
          </select>
        </div>
      `;
      } else if (item === 'học phần') {
        return `
          <div class="mb-3">
            <div class="row">
              <div class="col-md-6">
                <label for="" class="form-label">Chọn khoa:</label>
                <select class="form-select" id="select-khoa">
                  ${listKhoaOption}
                </select>
              </div>
              <div class="col-md-6 mt-3 mt-md-0">
                <label for="" class="form-label">Chọn học phần theo khoa:</label>
                <select class="form-select" id="select-hoc-phan" name="gia_tri">
                  <option selected disabled value="">--- Chọn một học phần ---</option>
                </select>
              </div>
            </div>
          </div>
        `;
      }

      return false;
    }
  </script>
@endsection
