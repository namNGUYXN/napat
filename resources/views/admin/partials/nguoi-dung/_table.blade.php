<div class="mb-3">
    <div
        class="py-2 px-3 mb-0 d-flex justify-content-between align-items-center 
        {{ request('keyword') ? 'alert alert-info' : '' }}">

        @if (request('keyword'))
            <div class="d-flex align-items-center">
                <span>
                    Bạn đang tìm: <strong id="searchKeywordText">{{ request('keyword') }}</strong>
                </span>
                <button type="button" class="btn btn-sm btn-outline-danger ms-2" id="clearSearch" title="Xóa tìm kiếm">
                    &times;
                </button>
            </div>
            <div>
                Kết quả: <strong>{{ $danhSach->total() }}</strong>
            </div>
        @else
            <div class="">
                Tổng số người dùng: <strong>{{ $danhSach->total() }}</strong>
            </div>
        @endif
    </div>
</div>



<table class="table table-bordered table-hover">
    <thead class="table-light">
        <tr>
            <th>#</th>
            <th>Họ tên</th>
            <th>Email</th>
            <th>Vai trò</th>
            <th>Ngày tạo</th>
            <th>Hành động</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($danhSach as $index => $nd)
            <tr>
                <td>{{ $danhSach->firstItem() + $index }}</td>
                <td>{{ $nd->ho_ten }}</td>
                <td>{{ $nd->email }}</td>
                <td>
                    {{ $nd->vai_tro }}
                </td>
                <td>{{ $nd->ngay_tao }}</td>
                <td>
                    <a href="{{ route('nguoi-dung.sua', $nd->id) }}" class="btn btn-sm btn-warning">Sửa</a>
                    <form action="" method="POST" class="d-inline-block"
                        onsubmit="return confirm('Xóa người dùng này?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger">Xóa</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="text-center">Không có người dùng nào.</td>
            </tr>
        @endforelse
    </tbody>
</table>

{{ $danhSach->links('pagination::bootstrap-5') }}
