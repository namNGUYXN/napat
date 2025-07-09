<div class="card shadow-sm">
    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Danh sách các bài trong chương</h5>
        <a href="{{ route('bai.create', $chuong->id) }}" class="btn btn-light btn-sm">
            <i class="fas fa-plus-circle me-2"></i>Thêm bài
        </a>
    </div>
    <div class="card-body">
        <form action="" method="GET">
            <div class="input-group mb-3">
                <input type="text" class="form-control" name="search" value="{{ request()->input('search') }}"
                    placeholder="Nhập tiêu đề của bài cần tìm..." autocomplete="off">
                <button class="btn btn-outline-secondary">
                    <i class="fas fa-search"></i> </button>
            </div>
        </form>

        <div class="table-responsive custom-scrollbar">
            <div class="text-center text-muted fst-italic" id="url-cap-nhat-thu-tu-bai"
                data-url="{{ route('thu-tu-bai.update', $chuong->id) }}">
                Giữ vào một bài 0.5s sau đó có thể kéo thả để thiết lập vị trí
            </div>
            <form action="{{ route('bai.quick-delete') }}" method="POST">
                @csrf
                @method('DELETE')
                <input type="hidden" name="id_chuong" value="{{ $chuong->id }}">
                <table class="table table-hover table-striped caption-top" style="min-width: 600px;">
                    <caption>Có {{ $listBai->count() }} bản ghi bài trong chương</caption>
                    <thead>
                        <tr>
                            <th scope="col">
                                <input type="checkbox" class="form-check-input" name="" id="check-all">
                            </th>
                            <th scope="col">Tiêu đề</th>
                            <th scope="col">Ngày tạo</th>
                            <th scope="col" class="text-center">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody id="list-bai">

                        @forelse ($listBai as $bai)
                            <tr data-id="{{ $bai->id }}">
                                <th scope="row">
                                    <input type="checkbox" class="form-check-input row-checkbox" name="list_id_bai[]"
                                        value="{{ $bai->id }}" id="">
                                </th>
                                <td class="align-middle">{{ $bai->tieu_de }}</td>
                                <td class="align-middle">{{ $bai->ngay_tao }}</td>
                                <td class="text-center align-middle" style="min-width: 140px;">
                                    <button type="button" class="btn btn-info btn-sm me-1 btn-detail-bai"
                                        data-url="{{ route('bai.detail', $bai->id) }}">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <a href="{{ route('bai.edit', $bai->id) }}" class="btn btn-warning btn-sm me-1">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-danger btn-sm btn-delete-bai"
                                        data-url="{{ route('bai.delete', $bai->id) }}">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">
                                    Không tìm thấy bài hoặc chương chưa có bài nào
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                @if ($listBai->count() > 0)
                    <div class="input-group ms-1 mb-3" style="max-width: 210px;">
                        <select class="form-select" name="action">
                            {{-- <option value="cap-nhat">Cập nhật</option> --}}
                            <option value="xoa">Xóa</option>
                        </select>
                        <button type="submit" class="btn btn-success">Thực hiện</button>
                    </div>
                @endif
            </form>
        </div>

    </div>
</div>
