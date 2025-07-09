<div class="card shadow-sm">
    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Danh sách các chương</h5>
        <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#modal-them-chuong">
            <i class="fas fa-plus-circle me-2"></i>Thêm chương
        </button>
        {{-- <a href="{{ route('bai-giang.create', $baiGiang->id) }}" class="btn btn-light btn-sm">
              <i class="fas fa-plus-circle me-2"></i>Thêm chương
            </a> --}}
    </div>
    <div class="card-body">
        <form action="{{ route('bai-giang.detail', $baiGiang->id) }}" method="GET">
            <div class="input-group mb-3">
                <input type="text" class="form-control" name="search" value="{{ request()->input('search') }}"
                    placeholder="Nhập tiêu đề hoặc mô tả của chương cần tìm..." autocomplete="off">
                <button class="btn btn-outline-secondary">
                    <i class="fas fa-search"></i> </button>
            </div>
        </form>

        <div class="table-responsive custom-scrollbar">
            <div id="url-cap-nhat-thu-tu-chuong" class="text-center text-muted fst-italic"
                data-url="{{ route('thu-tu-chuong.update', $baiGiang->id) }}">
                Giữ vào một chương 0.5s sau đó có thể kéo thả để thiết lập vị trí</div>
            <form action="{{ route('chuong.quick-delete') }}" method="POST">
                @csrf
                @method('DELETE')
                <input type="hidden" name="id_bai_giang" value="{{ $baiGiang->id }}">
                <table class="table table-hover table-striped caption-top" style="min-width: 600px;">
                    <caption>Có {{ $listChuong->count() }} bản ghi chương</caption>
                    <thead>
                        <tr>
                            <th scope="col">
                                <input type="checkbox" class="form-check-input" name="" id="check-all">
                            </th>
                            <th scope="col">Tiêu đề</th>
                            <th scope="col">Mô tả ngắn</th>
                            <th scope="col" class="text-center">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody id="list-chuong">

                        @forelse ($listChuong as $chuong)
                            <tr data-id="{{ $chuong->id }}">
                                <th scope="row">
                                    <input type="checkbox" class="form-check-input row-checkbox" name="list_id_chuong[]"
                                        value="{{ $chuong->id }}" id="">
                                </th>
                                <td class="align-middle">
                                    <a href="{{ route('bai.index', $chuong->id) }}"
                                        class="link-body-emphasis link-offset-2 link-underline-opacity-25 link-underline-opacity-75-hover">
                                        {{ $chuong->tieu_de }}
                                    </a>
                                </td>
                                <td class="align-middle">{{ $chuong->mo_ta_ngan }}</td>
                                <td class="text-center align-middle" style="min-width: 140px;">
                                    <a href="{{ route('bai.index', $chuong->id) }}" class="btn btn-info btn-sm me-1">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <button type="button" class="btn btn-warning btn-sm me-1 btn-update-chuong"
                                        data-url-detail="{{ route('chuong.edit', $chuong->id) }}"
                                        data-url-update="{{ route('chuong.update', $chuong->id) }}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-danger btn-sm btn-xoa-chuong"
                                        data-url-delete="{{ route('chuong.delete', $chuong->id) }}"
                                        data-url-detail="{{ route('bai-giang.detail', $baiGiang->id) }}">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">
                                    Không tìm thấy chương hoặc bài giảng chưa có chương nào
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                @if ($listChuong->count() > 0)
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

        {{-- Dấu : báo hiệu cho blade đây là biểu thức php --}}
        {{-- <x-pagination :paginator="$listChuong" base-url="{{ route('bai-giang.detail', $baiGiang->id) }}" /> --}}

    </div>
</div>
