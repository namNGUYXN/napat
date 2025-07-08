@if ($listBinhLuan->count())
  <div class="card" id="list-binh-luan-card">
    <div class="card-header bg-dark text-white">
      <h5 class="mb-0">Danh sách bình luận</h5>
    </div>
    <div class="card-body">
      <div id="list-binh-luan" class="list-group list-group-flush">

        @foreach ($listBinhLuan as $binhLuan)
          <div class="list-group-item comment-item" data-comment-id="{{ $binhLuan->id }}" data-comment-level="0">
            <div class="d-flex w-100 justify-content-between align-items-center">
              <h6 class="mb-1 me-auto">{{ $binhLuan->thanh_vien_lop->nguoi_dung->ho_ten }}</h6>
              <small class="text-muted me-2">{{ $binhLuan->ngay_tao }}</small>

              @if ($binhLuan->thanh_vien_lop->id_nguoi_dung == session('id_nguoi_dung'))
                <div class="dropdown comment-actions-dropdown">
                  <button class="btn btn-transparent dropdown-toggle hide-arrow-down" type="button"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-ellipsis-v"></i>
                  </button>
                  <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                      <button class="dropdown-item edit-comment-btn">Chỉnh sửa</button>
                    </li>
                    <li><button class="dropdown-item delete-comment-btn"
                        data-url-delete="{{ route('binh-luan.delete', $binhLuan->id) }}">Xóa</button>
                    </li>
                  </ul>
                </div>
              @endif

            </div>
            <p class="mb-1 comment-content-text">{{ $binhLuan->noi_dung }}</p>

            <div class="edit-form-container mt-2" style="display: none;">
              <form action="{{ route('binh-luan.update', $binhLuan->id) }}" method="POST"
                class="edit-comment-form d-flex align-items-end">
                @csrf
                @method('PUT')
                <div class="flex-grow-1 me-2">
                  <textarea class="form-control form-control-sm" name="noi_dung" rows="2" required="">{{ $binhLuan->noi_dung }}</textarea>
                  <small class="text-danger binh-luan-error"></small>
                </div>
                <button type="submit" class="btn btn-sm btn-success me-1">Lưu</button>
                <button type="button" class="btn btn-sm btn-secondary cancel-edit-btn">Hủy</button>
              </form>
            </div>

            <small class="comment-action-links">

              <a href="#" class="text-primary me-2 reply-btn" data-comment-id="{{ $binhLuan->id }}"
                data-comment-author="{{ $binhLuan->thanh_vien_lop->nguoi_dung->ho_ten }}" data-comment-level="0">Phản
                hồi</a>

            </small>

            @if ($soLuongBinhLuanCon = $binhLuan->list_binh_luan_con->count())
              <small>
                <a href="#" class="toggle-replies-btn text-muted" data-comment-id="{{ $binhLuan->id }}"
                  data-has-replies="true" data-toggle-state="hidden">
                  Có {{ $soLuongBinhLuanCon }} phản hồi <i class="fas fa-caret-down"></i>
                </a>
              </small>
            @endif

            <div class="reply-form-container mt-2" style="display: none;">
              <form
                action="{{ route('binh-luan.phan-hoi', [$baiTrongLop->id_lop_hoc_phan, $baiTrongLop->id_bai, $binhLuan->id]) }}"
                method="POST" class="reply-form d-flex align-items-end">
                @csrf
                <div class="flex-grow-1 me-2">
                  <textarea class="form-control form-control-sm" name="noi_dung" rows="2" placeholder="" required=""></textarea>
                  <small class="text-danger binh-luan-error"></small>
                </div>
                <button type="submit" class="btn btn-sm btn-outline-primary">Gửi</button>
              </form>
            </div>

            <div class="replies-container mt-2 hidden-replies">

              <div class="item-binh-luan-con">
                @foreach ($binhLuan->list_binh_luan_con as $binhLuanCon)
                  @include('partials.lop-hoc-phan.noi-dung-bai.item-binh-luan-con', $binhLuanCon)
                @endforeach
              </div>

            </div>
          </div>
        @endforeach

      </div>
    </div>
  </div>
@endif
