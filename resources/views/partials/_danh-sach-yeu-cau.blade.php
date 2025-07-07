@forelse($yeuCau as $yeuCauItem)
    <div class="list-group-item d-flex align-items-center justify-content-between rounded-30px custom-request-item">
        <div class="d-flex align-items-center">
            <img src="{{ asset('storage/' . $yeuCauItem->nguoi_dung->hinh_anh) }}" alt="Avatar"
                class="rounded-circle border border-secondary me-3" width="48" height="48"
                style="object-fit: cover;">
            <div>
                <div class="fw-semibold request-name">{{ $yeuCauItem->nguoi_dung->ho_ten }}</div>
                <div class="text-muted request-email">{{ $yeuCauItem->nguoi_dung->email }}</div>
            </div>
        </div>

        <div class="d-flex gap-2">
            <button class="btn btn-success d-flex align-items-center px-3 py-2 shadow-sm btn-accept-request"
                data-id="{{ $yeuCauItem->id }}" title="Chấp nhận">
                <i class="fas fa-check me-1"></i> <span>Chấp nhận</span>
            </button>

            <button class="btn btn-danger d-flex align-items-center px-3 py-2 shadow-sm btn-reject-request"
                data-id="{{ $yeuCauItem->id }}" title="Từ chối">
                <i class="fas fa-times me-1"></i> <span>Từ chối</span>
            </button>
        </div>
    </div>


@empty
    <div class="text-muted">Không có yêu cầu nào.</div>
@endforelse
