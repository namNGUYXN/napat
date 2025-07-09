<div class="mb-3">
    <div
        class="py-2 px-3 mb-0 alert alert-info 
                d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-2">
        @if (request('keyword'))
            <div class="d-flex flex-wrap align-items-center gap-2">
                <span>
                    Bạn đang tìm: <strong id="searchKeywordText">{{ request('keyword') }}</strong>
                </span>
                <button type="button" class="btn btn-sm btn-outline-danger" id="clearSearch" title="Xóa tìm kiếm">
                    &times;
                </button>
            </div>
            <div>
                Kết quả: <strong>{{ $danhSach->total() }}</strong>
            </div>
        @else
            <div>
                Tổng số người dùng: <strong>{{ $danhSach->total() }}</strong>
            </div>
        @endif
    </div>
</div>




<div class="table-responsive">
    <table class="table table-bordered table-hover align-middle">
        <thead class="table-light text-center">
            <tr>
                <th style="width: 50px;">#</th>
                <th>Họ tên</th>
                <th>Email</th>
                <th>Vai trò</th>
                <th>Ngày tạo</th>
                <th style="width: 180px;">Hành động</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($danhSach as $index => $nd)
                <tr>
                    <td class="text-center">{{ $danhSach->firstItem() + $index }}</td>
                    <td>{{ $nd->ho_ten }}</td>
                    <td>{{ $nd->email }}</td>
                    <td class="text-center">
                        <span class="badge bg-{{ $nd->vai_tro === 'Giảng viên' ? 'info' : 'secondary' }}">
                            {{ $nd->vai_tro }}
                        </span>
                    </td>
                    <td class="text-center">{{ $nd->ngay_tao }}</td>
                    <td class="text-center">
                        <a href="{{ route('nguoi-dung.sua', $nd->id) }}" class="btn btn-sm btn-outline-warning me-1"
                            data-bs-toggle="tooltip" data-bs-placement="top" title="Sửa thông tin">
                            <i class="bi bi-pencil-square"></i>
                        </a>
                        <form action="{{ route('nguoi-dung.khoa-mo', $nd->id) }}" method="POST"
                            class="d-inline-block form-khoa-mo" data-ten="{{ $nd->ho_ten }}"
                            data-action="{{ route('nguoi-dung.khoa-mo', $nd->id) }}">
                            @csrf
                            @method('PATCH')
                            <button type="button"
                                class="btn btn-sm {{ $nd->is_active ? 'btn-outline-danger' : 'btn-outline-success' }}"
                                data-bs-toggle="tooltip" data-bs-placement="top"
                                title="{{ $nd->is_active ? 'Khóa người dùng' : 'Mở khóa người dùng' }}">
                                <i class="bi {{ $nd->is_active ? 'bi-lock-fill' : 'bi-unlock-fill' }}"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center text-muted">Không có người dùng nào.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>



{{ $danhSach->links('pagination::bootstrap-5') }}
