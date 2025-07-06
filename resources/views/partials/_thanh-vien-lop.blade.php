@if (session('vai_tro') == 'Giảng viên')
  <h6 class="mb-3">Yêu cầu vào lớp</h6>
  <div class="list-group mb-4 yeuCau">
    @include('partials._danh-sach-yeu-cau')
  </div>
@endif


<!-- Phần danh sách thành viên -->
<h6 class="mb-3">Thành viên trong lớp</h6>
<div class="list-group">
  @forelse($thanhVien as $tv)
    <div class="list-group-item d-flex align-items-center">
      <img src="{{ asset('storage/' . $tv->nguoi_dung->hinh_anh) }}" alt="Avatar"
        class="border border-secondary rounded-circle me-2" width="40" height="40">
      <div>
        <strong>{{ $tv->nguoi_dung->ho_ten }}</strong><br>
        <small>{{ $tv->nguoi_dung->email }}</small>
      </div>
      <div class="flex-grow-1 text-end">
        @if (session('vai_tro') == 'Giảng viên')
          <div class="dropdown">
            <button class="btn btn-transparent dropdown-toggle remove-arrow-down" type="button"
              data-bs-toggle="dropdown" aria-expanded="false">
              <i class="fas fa-ellipsis-v"></i>
            </button>
            <ul class="dropdown-menu">
              <li>
                <button class="dropdown-item btn-remove-from-class"
                  data-url-remove-from="{{ route('lop-hoc-phan.remove-from', [$lopHocPhan->id, $tv->id_nguoi_dung]) }}">
                  Xóa khỏi lớp
                </button>
              </li>
            </ul>
          </div>
        @endif

      </div>
    </div>
  @empty
    <div class="text-muted">Chưa có thành viên nào trong lớp.</div>
  @endforelse
</div>
