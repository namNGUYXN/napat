@forelse($yeuCau as $yeuCauItem)
    <div class="list-group-item d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center">
            <img src="{{ $yeuCauItem->nguoi_dung->hinh_anh ?? 'https://picsum.photos/40/40' }}" alt="Avatar"
                class="border border-secondary rounded-circle me-2" width="40" height="40">
            <div>
                <strong>{{ $yeuCauItem->nguoi_dung->ho_ten }}</strong><br>
                <small>{{ $yeuCauItem->nguoi_dung->email }}</small>
            </div>
        </div>
        <div>
            <button class="btn btn-sm btn-success me-1 btn-accept-request" data-id="{{ $yeuCauItem->id }}"
                title="Chấp nhận">
                <i class="fas fa-check"></i>
            </button>

            <button class="btn btn-sm btn-danger btn-reject-request" data-id="{{ $yeuCauItem->id }}" title="Từ chối">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
@empty
    <div class="text-muted">Không có yêu cầu nào.</div>
@endforelse
