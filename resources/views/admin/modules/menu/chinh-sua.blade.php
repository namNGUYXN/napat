@extends('layouts.admin')

@section('content')
  <div class="col bg-light p-4 overflow-auto custom-scrollbar">
    <h2 class="mb-4">Chỉnh sửa Menu</h2>

    <a href="{{ route('menu.index') }}" class="btn btn-outline-secondary mb-4">
      <i class="fas fa-arrow-alt-circle-left me-2"></i>Danh sách Menu
    </a>

    @if ($errors->any())
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <ul class="list-unstyled m-0">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

    {{-- @if (session('message'))
      <div class="alert alert-{{ session('status') }} alert-dismissible fade show" role="alert">
        {{ session('message') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif --}}

    <div class="card shadow-sm">
      <div class="card-header bg-warning text-white">
        <h5 class="mb-0">Thông tin Menu</h5>
      </div>
      <div class="card-body">
        <form id="form-them-menu" action="{{ route('menu.update', $menu->id) }}" method="POST">
          @method('PUT')
          @csrf
          <input type="hidden" name="id" value="{{ $menu->id }}">
          <div class="mb-3">
            <label for="" class="form-label">Tên menu: <span class="text-danger">*</span></label>
            <input type="text" class="form-control @error('ten') is-invalid @enderror" name="ten"
              value="{{ old('ten', $menu->ten) }}">
            @error('ten')
              <span class="invalid-feedback">{{ $message }}</span>
            @enderror
          </div>

          <div class="mb-3">
            <label for="" class="form-label">Thuộc menu:</label>
            <select class="form-select" name="id_menu_cha">
              <option selected value="">Là menu chính</option>
              @foreach ($listMenu as $v)
                <option value="{{ $v['id'] }}"
                  {{ old('id_menu_cha', $menu->id_menu_cha) == $v['id'] ? 'selected' : '' }}>
                  {{ $v['ten'] }}</option>
              @endforeach
            </select>
          </div>

          <div class="mb-3">
            <label for="" class="form-label">Loại menu:</label>
            <select class="form-select @error('id_loai_menu') is-invalid  @enderror" id="select-loai-menu"
              name="id_loai_menu">
              <option selected disabled value="">--- Chọn loại menu ---</option>
              @foreach ($listLoaiMenu as $loaiMenu)
                <option value="{{ $loaiMenu->id }}"
                  {{ old('id_loai_menu', $menu->id_loai_menu) == $loaiMenu->id ? 'selected' : '' }}>
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
            <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
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
    // Menu đang thao tác
    var menu = @php echo $menu @endphp;

    var listKhoaOption = createListOption('khoa', listKhoa, menu.gia_tri);

    $('#select-loai-menu').on('change', function() {
      const menuType = Number($(this).val());
      if (menuType === 4) {
        $('#sub-box-select').html(renderBoxSelect('khoa'));
      }
    });

    // Khi có option loại menu được chọn
    if ($('#select-loai-menu').val()) {
      const menuType = Number($('#select-loai-menu').val());
      if (menuType === 4) {
        $('#sub-box-select').html(renderBoxSelect('khoa'));
      }
    }

    // Hàm tạo list thẻ option
    function createListOption(item, list, giaTri = "") {
      const mapList = $.map(list, function(element, index) {
        return `
         <option value="${element.slug}/lop-hoc-phan"${element.slug + '/lop-hoc-phan' == giaTri ? ' selected' : ''}>${element.ten}</option>
        `;
      });
      mapList.unshift(`<option selected disabled value="">--- Chọn một ${item} ---</option>`);

      return mapList.join('');
    }

    function renderBoxSelect() {
      return `
        <div class="mb-3">
          <label for="" class="form-label">Chọn khoa:</label>
          <select class="form-select" id="" name="gia_tri">
            ${listKhoaOption}
          </select>
        </div>
      `;
    }
  </script>

  @if (session('message'))
    <script>
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
        icon: '{{ session('icon') }}',
        title: '{{ session('message') }}'
      });
    </script>
  @endif
@endsection
