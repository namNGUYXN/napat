<div class="mb-3">
    <div
        class="py-2 px-3 mb-0 d-flex justify-content-between align-items-center 
        {{ request('keyword') ? 'alert alert-info' : '' }}">

        @if (request('keyword'))
            <div class="d-flex align-items-center">
                <span>Bạn đang tìm: <strong id="searchKeywordText">{{ request('keyword') }}</strong></span>
                <button type="button" class="btn btn-sm btn-outline-danger ms-2" id="clearSearch" title="Xóa tìm kiếm">
                    &times;
                </button>
            </div>
            <div>Kết quả: <strong>{{ $danhSach->total() }}</strong></div>
        @else
            <div>Tổng số khoa: <strong>{{ $danhSach->total() }}</strong></div>
        @endif
    </div>
</div>

<table class="table table-bordered table-hover">
    <thead class="table-light">
        <tr>
            <th>#</th>
            <th>Mã khoa</th>
            <th>Tên khoa</th>
            <th>Email</th>
            <th>Ngày tạo</th>
            <th>Hành động</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($danhSach as $index => $khoa)
            <tr>
                <td>{{ $danhSach->firstItem() + $index }}</td>
                <td>{{ $khoa->ma }}</td>
                <td>{{ $khoa->ten }}</td>
                <td>{{ $khoa->email }}</td>
                <td>{{ $khoa->ngay_tao }}</td>
                <td>
                    <a href="{{ route('khoa.cap-nhat', $khoa->id) }}" class="btn btn-sm btn-warning">Sửa</a>
                    <form action="{{ route('khoa.xoa', $khoa->id) }}" method="POST" class="form-xoa d-inline-block">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger btn-confirm-delete">Xóa</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="text-center">Không có khoa nào.</td>
            </tr>
        @endforelse
    </tbody>
</table>

{{ $danhSach->links('pagination::bootstrap-5') }}
