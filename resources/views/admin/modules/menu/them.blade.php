@extends('layouts.admin')

@section('content')
  <div class="col bg-light p-4 overflow-auto custom-scrollbar">
    <h2 class="mb-4">Thêm Menu mới</h2>

    <a href="{{ route('list-menu') }}" class="btn btn-outline-secondary mb-4">
      <i class="fas fa-arrow-alt-circle-left me-2"></i>Danh sách Menu
    </a>

    <div class="card shadow-sm">
      <div class="card-header bg-success text-white">
        <h5 class="mb-0">Thông tin Menu mới</h5>
      </div>
      <div class="card-body">
        <form id="" action="" method="POST" class="was-validated">
          <div class="mb-3">
            <label for="" class="form-label">Tên menu: <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="" required>
            <div class="invalid-feedback">
              Vui lòng nhập tên Menu
            </div>
          </div>

          <div class="mb-3">
            <label for="" class="form-label">Thuộc menu:</label>
            <select class="form-select">
              <option selected value="0">Là menu chính</option>
              @foreach ($listMenu as $menu)
                <option value="{{ $menu['id'] }}">{{ $menu['ten'] }}</option>
              @endforeach
            </select>
          </div>

          <div class="mb-3">
            <label for="" class="form-label">Loại menu:</label>
            <select class="form-select" required id="select-menu-type">
              <option selected value="">--- Chọn loại menu ---</option>
              @foreach ($listLoaiMenu as $loaiMenu)
                <option value="{{ $loaiMenu->id }}">{{ $loaiMenu->ten }}</option>
              @endforeach
            </select>
            <div class="invalid-feedback">
              Vui lòng chọn loại menu
            </div>
          </div>

          <div id="menu-type"></div>

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
    $('#select-menu-type').on('change', function() {
      const menuType = Number($(this).val());

      switch (menuType) {
        case 4: // Chỉ mục khoa
          $('#menu-type').html(renderMenuType('khoa'));
          break;
        case 5: // Chỉ mục học phần
          $('#menu-type').html(renderMenuType('học phần'));
          break;
        default:
          $('#menu-type').html('');
      }
    });

    function renderMenuType(item) {
      let listOption = '';

      if (item === 'khoa') {
        listOption += `
          @foreach ($listKhoa as $khoa)
            <option value="{{ $khoa->id }}">{{ $khoa->ten }}</option>
          @endforeach
        `;
      } else if (item === 'học phần') {
        listOption += `
          @foreach ($listHocPhan as $hocPhan)
            <option value="{{ $hocPhan->id }}">{{ $hocPhan->ten }}</option>
          @endforeach
        `;
      }


      return `
        <div class="mb-3">
          <label for="" class="form-label">Chọn ${item}:</label>
          <select class="form-select" required id="">
            <option selected value="">--- Chọn một ${item} ---</option>
            ${listOption}
          </select>
          <div class="invalid-feedback">
            Vui lòng chọn ${item}
          </div>
        </div>
      `;
    }
  </script>
@endsection
