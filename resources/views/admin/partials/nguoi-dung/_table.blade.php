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
                    {{-- <a href="{{ route('nguoi-dung.edit', $nd->id) }}" class="btn btn-sm btn-warning">Sửa</a>
                    <form action="{{ route('nguoi-dung.destroy', $nd->id) }}" method="POST" class="d-inline-block"
                        onsubmit="return confirm('Xóa người dùng này?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger">Xóa</button>
                    </form> --}}
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
