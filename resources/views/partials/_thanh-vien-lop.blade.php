@if (session('vai_tro') == 'Giảng viên')
    <h6 class="mb-3 text-primary"><i class="fas fa-user-clock me-1"></i> Yêu cầu vào lớp</h6>
    <div class="list-group mb-4 yeuCau">
        @include('partials._danh-sach-yeu-cau')
    </div>
@endif

<h6 class="mb-3 text-success"><i class="fas fa-users me-1"></i> Thành viên trong lớp</h6>
<div class="list-group">
    @forelse($thanhVien as $tv)
        <div class="list-group-item d-flex align-items-center justify-content-between rounded-30px custom-list-item">
            <div class="d-flex align-items-center">
                <img src="{{ asset('storage/' . $tv->nguoi_dung->hinh_anh) }}" alt="Avatar"
                    class="rounded-circle border border-secondary me-3" width="48" height="48"
                    style="object-fit: cover;">
                <div>
                    <div class="fw-semibold member-name">{{ $tv->nguoi_dung->ho_ten }}</div>
                    <div class="text-muted member-email">{{ $tv->nguoi_dung->email }}</div>
                </div>
            </div>

            @if (session('vai_tro') == 'Giảng viên')
                <div class="dropdown">
                    <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        <i class="fas fa-ellipsis-v"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <button class="dropdown-item btn-remove-from-class"
                                data-url-remove-from="{{ route('lop-hoc-phan.remove-from', [$lopHocPhan->id, $tv->id_nguoi_dung]) }}">
                                <i class="fas fa-user-minus me-1"></i> Xóa khỏi lớp
                            </button>
                        </li>
                    </ul>
                </div>
            @endif
        </div>

    @empty
        <div class="text-muted">Chưa có thành viên nào trong lớp.</div>
    @endforelse
</div>
