@if ($dsLopHoc->count())
  <form action="{{ route('lop-hoc.lop-hoc-cua-toi') }}" id="form-filter" method="GET">
    <div class="row">
      <div class="col-md-3">
        <div class="mb-3">
          <select name="sort" id="sort-select" class="form-select">
            <option value="newest"{{ request('sort') == 'newest' ? ' selected' : '' }}>Mới nhất</option>
            <option value="oldest"{{ request('sort') == 'oldest' ? 'selected' : '' }}>Cũ nhất</option>
            <option value="name_asc"{{ request('sort') == 'name_asc' ? 'selected' : '' }}>Tên lớp A -> Z</option>
            <option value="name_desc"{{ request('sort') == 'name_desc' ? 'selected' : '' }}>Tên lớp Z -> A</option>
          </select>
        </div>
      </div>
      <div class="col-md-3">
        <div class="mb-3">
          <select name="limit" id="limit-select" class="form-select">
            <option value="3"{{ request('limit') == 3 ? ' selected' : '' }}>Hiển thị: 3</option>
            <option value="6"{{ request('limit') == 6 ? ' selected' : '' }}>Hiển thị: 6</option>
            <option value="12"{{ request('limit') == 12 ? ' selected' : '' }}>Hiển thị: 12</option>
          </select>
        </div>
      </div>
      <div class="col-md-6">
        <div class="input-group mb-3">
          <input type="text" class="form-control" name="search" value="{{ request()->input('search') }}"
            placeholder="Nhập tên hoặc mã lớp cần tìm..." id="search-input" autocomplete="off">
          <button class="btn btn-outline-secondary">
            <i class="fas fa-search"></i> </button>
        </div>
      </div>
    </div>
  </form>

  <div class="class-grid">
    @foreach ($dsLopHoc as $lop)
      <div class="class-card rounded">
        <a href="{{ route('lop-hoc.detail', ['slug' => $lop->slug]) }}" class="class-img">
          <img src="{{ asset('storage/' . $lop->hinh_anh) }}" class="img-fluid rounded-top" alt="">
        </a>
        <div class="p-3">
          <a href="{{ route('lop-hoc.detail', ['slug' => $lop->slug]) }}"
            class="text-dark class-name">{{ $lop->ten }}</a>
          <p class="mb-1"><b>Giảng viên: </b>{{ $lop->giang_vien->ho_ten }}</p>
          <p class="mb-1"><b>Mã lớp: </b>{{ $lop->ma }}</p>
          <p class="mb-1"><b>Khoa: </b>{{ $lop->khoa->ten }}</p>
          <small class="text-secondary fst-italic d-inline-block me-3">
            {{ Str::of($lop->mo_ta_ngan)->limit(100) }}
          </small>
          @if (session('id_nguoi_dung') == $lop->bai_giang->id_giang_vien)
            <div class="class-action-btn">
              <div class="dropdown">
                <button class="btn btn-transparent dropdown-toggle remove-arrow-down" type="button"
                  data-bs-toggle="dropdown" aria-expanded="false">
                  <i class="fas fa-ellipsis-v"></i>
                </button>
                <ul class="dropdown-menu">
                  <li>
                    <a class="dropdown-item" href="{{ route('lop-hoc.detail', ['slug' => $lop->slug]) }}">
                      Xem
                    </a>
                  </li>
                  <li>
                    <button class="dropdown-item btn-update-class" type="button"
                      data-url-detail="{{ route('lop-hoc-phan.detail-modal', $lop->id) }}"
                      data-url-update="{{ route('lop-hoc-phan.update-modal', $lop->id) }}">
                      Chỉnh sửa
                    </button>
                  </li>
                  <li>
                    <button class="dropdown-item btn-delete-class" type="button"
                      data-url-delete={{ route('lop-hoc-phan.delete', $lop->id) }}>
                      Xóa
                    </button>
                  </li>
                </ul>
              </div>
            </div>
          @elseif (session('vai_tro') == 'Sinh viên')
            <div class="class-action-btn">
              <div class="dropdown">
                <button class="btn btn-transparent dropdown-toggle remove-arrow-down" type="button"
                  data-bs-toggle="dropdown" aria-expanded="false">
                  <i class="fas fa-ellipsis-v"></i>
                </button>
                <ul class="dropdown-menu">
                  <li>
                    <a class="dropdown-item" href="{{ route('lop-hoc.detail', ['slug' => $lop->slug]) }}">
                      Xem
                    </a>
                  </li>

                  @php
                    $thanhVienLopService = app(App\Services\ThanhVienLopService::class);
                  @endphp

                  @if (!$thanhVienLopService->daThamGiaLopHocPhan($lop->id))
                    <li>
                      <button class="dropdown-item btn-register-class" type="button"
                        data-url-register="{{ route('lop-hoc-phan.register', $lop->id) }}">
                        Đăng ký lớp
                      </button>
                    </li>
                  @endif

                </ul>
              </div>
            </div>
          @endif
        </div>
      </div>
    @endforeach
  </div>

  {{-- Dấu : báo hiệu cho blade đây là biểu thức php --}}
  <x-pagination :paginator="$dsLopHoc" base-url="{{ $route }}" />
@else
  <h4 class="text-muted fst-italic text-center px-3 mt-5">
    Không tìm thấy <span class="text-dark text-decoration-underline">{{ request('search', '') }}</span> hoặc chưa có
    lớp học phần
  </h4>
@endif
