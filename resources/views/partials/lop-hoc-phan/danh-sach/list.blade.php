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
                  <button class="dropdown-item class-delete-btn" type="button">
                    Xóa
                  </button>
                </li>
              </ul>
            </div>
          </div>
        @endif
      </div>
    </div>
  @endforeach
</div>
