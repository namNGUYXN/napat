<div class="list-group-item comment-item" data-comment-id="{{ $binhLuanCon->id }}" data-comment-level="1">
  <div class="d-flex w-100 justify-content-between align-items-center">
    <h6 class="mb-1 me-auto">{{ $binhLuanCon->thanh_vien_lop->nguoi_dung->ho_ten }}</h6>
    <small class="text-muted me-2">{{ $binhLuanCon->ngay_tao }}</small>

    @if ($binhLuanCon->thanh_vien_lop->id_nguoi_dung == session('id_nguoi_dung'))
      <div class="dropdown comment-actions-dropdown">
        <button class="btn btn-transparent dropdown-toggle hide-arrow-down" type="button" data-bs-toggle="dropdown"
          aria-expanded="false">
          <i class="fas fa-ellipsis-v"></i>
        </button>
        <ul class="dropdown-menu dropdown-menu-end">
          <li>
            <button class="dropdown-item edit-comment-btn">Chỉnh sửa</button>
          </li>
          <li><button class="dropdown-item delete-comment-btn" data-url-delete="{{ route('binh-luan.delete', $binhLuanCon->id) }}">Xóa</button>
          </li>
        </ul>
      </div>
    @endif

  </div>
  <p class="mb-1 comment-content-text">{{ $binhLuanCon->noi_dung }}</p>

  @if ($binhLuanCon->thanh_vien_lop->id_nguoi_dung == session('id_nguoi_dung'))
    <div class="edit-form-container mt-2" style="display: none;">
      <form action="{{ route('binh-luan.update', $binhLuanCon->id) }}" method="POST"
        class="edit-comment-form d-flex align-items-end">
        @csrf
        @method('PUT')
        <div class="flex-grow-1 me-2">
          <textarea class="form-control form-control-sm" name="noi_dung" rows="2" required="">{{ $binhLuanCon->noi_dung }}</textarea>
          <small class="text-danger binh-luan-error"></small>
        </div>
        <button type="submit" class="btn btn-sm btn-success me-1">Lưu</button>
        <button type="button" class="btn btn-sm btn-secondary cancel-edit-btn">Hủy</button>
      </form>
    </div>
  @endif

</div>
