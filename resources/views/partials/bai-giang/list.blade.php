@if ($listBaiGiang->count())
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
            placeholder="Nhập tên hoặc mô tả bài giảng cần tìm..." id="search-input" autocomplete="off">
          <button class="btn btn-outline-secondary">
            <i class="fas fa-search"></i> </button>
        </div>
      </div>
    </div>
  </form>

  <div class="document-grid">
    @foreach ($listBaiGiang as $baiGiang)
      <div class="document-card rounded">
        <a href="{{ route('bai-giang.detail', $baiGiang->id) }}" class="document-img">
          <img src="{{ asset('storage/' . $baiGiang->hinh_anh) }}" class="rounded-top" alt="">
        </a>
        <div class="p-3">
          <a href="{{ route('bai-giang.detail', $baiGiang->id) }}"
            class="text-dark document-name">{{ $baiGiang->ten }}</a>
          <p class="mb-1">
            <b>Số chương: </b>{{ $baiGiang->so_chuong }}
            / <b>Số bài: </b>{{ $baiGiang->tong_so_bai }}
          </p>
          <p class="mb-1"><b>Ngày tạo: </b> {{ $baiGiang->ngay_tao }}</p>
          <small class="text-secondary text-break fst-italic d-inline-block me-3" title="{{ $baiGiang->mo_ta_ngan }}">
            {{ Str::of($baiGiang->mo_ta_ngan)->limit(100) }}
          </small>
          <div class="document-action-btn">
            <div class="dropdown">
              <button class="btn btn-transparent dropdown-toggle remove-arrow-down" type="button"
                data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-ellipsis-v"></i>
              </button>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="{{ route('bai-giang.detail', $baiGiang->id) }}">Xem</a></li>
                <li>
                  <button type="button" class="dropdown-item document-edit-btn"
                    data-url-detail="{{ route('bai-giang.detail-modal', $baiGiang->id) }}"
                    data-url-update="{{ route('bai-giang.update-modal', $baiGiang->id) }}">Chỉnh sửa</button>
                </li>
                <li>
                  <button type="button" class="dropdown-item document-delete-btn"
                    data-url-delete="{{ route('bai-giang.delete', $baiGiang->id) }}">Xóa</button>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    @endforeach
  </div>

  {{-- Dấu : báo hiệu cho blade đây là biểu thức php --}}
  <x-pagination :paginator="$listBaiGiang" base-url="{{ route('bai-giang.index') }}" />
@else
  <h4 class="text-muted fst-italic text-center px-3 mt-5">
    Không tìm thấy <span class="text-dark text-decoration-underline">{{ request('search', '') }}</span> hoặc chưa có
    bài giảng
  </h4>
@endif
