@foreach ($listBanTin as $banTin)
  <div class="card shadow-sm mb-5 border-0 news-item">
    <div class="card-body position-relative">

      {{-- Header bản tin --}}
      <div class="d-flex align-items-start mb-3">
        <img src="{{ asset('storage/' . $banTin->thanh_vien_lop->nguoi_dung->hinh_anh) }}"
          class="rounded-circle border border-2 border-light me-3" width="50" height="50" alt="Avatar">
        <div class="flex-grow-1">
          <h6 class="mb-1 fw-semibold">
            {{ $banTin->thanh_vien_lop->nguoi_dung->vai_tro ?? '' }}: {{ $banTin->thanh_vien_lop->nguoi_dung->ho_ten }}
          </h6>
          <small class="text-muted">
            <i class="far fa-clock me-1"></i> {{ $banTin->ngay_tao }}
          </small>
        </div>

        {{-- Action --}}
        @if ($nguoiDung->id == $banTin->thanh_vien_lop->nguoi_dung->id)
          <div class="ms-2 dropdown">
            <button class="btn btn-sm btn-light" data-bs-toggle="dropdown">
              <i class="fas fa-ellipsis-h"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
              <li><button class="dropdown-item btn-update-ban-tin" type="button"
                  data-url-detail="{{ route('ban-tin.detail', $banTin->id) }}"
                  data-url-update="{{ route('ban-tin.update', $banTin->id) }}"><i class="fas fa-edit me-2"></i> Chỉnh sửa</button></li>
              <li><button class="dropdown-item btn-delete-ban-tin text-danger" type="button" data-type="bản tin"
                  data-url-delete="{{ route('ban-tin.delete', $banTin->id) }}"><i class="fas fa-trash-alt me-2 text-danger"></i> Xóa</button></li>
            </ul>
          </div>
        @endif
      </div>

      {{-- Nội dung bản tin --}}
      <div class="news-content mb-3">
        <div class="px-2 py-2 bg-light rounded clearfix">{!! $banTin->noi_dung !!}</div>
      </div>

      {{-- Phản hồi --}}
      <div>
        @if (count($banTin->list_ban_tin_con) > 0)
          <a href="javascript:void(0)" class="text-decoration-none text-primary toggle-comments"
            data-bs-toggle="collapse" data-bs-target="#comments-{{ $banTin->id }}">
            <i class="fas fa-comment text-secondary"></i> {{ count($banTin->list_ban_tin_con) }} phản hồi
          </a>
        @endif
      </div>
    </div>

    {{-- Footer: Form phản hồi --}}
    <div class="card-footer bg-white">
      <form action="{{ route('ban-tin.reply', [$lopHocPhan->id, $banTin->id]) }}" method="POST"
        id="form-reply-{{ $banTin->id }}" class="form-reply mb-2">
        @csrf
        <div class="d-flex align-items-start">
          <img src="{{ asset('storage/' . $nguoiDung->hinh_anh) }}" class="rounded-circle me-3" width="40"
            height="40" alt="Avatar">
          <div class="input-group">
            <input type="text" class="form-control" name="noi_dung" placeholder="Nhập phản hồi..."
              autocomplete="off">
            <button class="btn btn-outline-primary" type="submit">
              <i class="fas fa-paper-plane"></i>
            </button>
          </div>
        </div>
      </form>

      {{-- Form cập nhật phản hồi (ẩn) --}}
      <form action="" method="POST" id="form-update-reply-{{ $banTin->id }}" style="display: none;">
        @csrf
        <div class="d-flex align-items-start mb-2">
          <img src="{{ asset('storage/' . $nguoiDung->hinh_anh) }}" class="rounded-circle me-3" width="40"
            height="40" alt="Avatar">
          <div class="input-group">
            <input type="text" class="form-control" name="noi_dung" placeholder="Nhập phản hồi..."
              autocomplete="off">
            <button class="btn btn-outline-success" type="submit">Chỉnh sửa</button>
            <button class="btn btn-outline-secondary btn-cancel-update-reply" type="button"
              data-form-reply="#form-reply-{{ $banTin->id }}">Hủy</button>
          </div>
        </div>
      </form>

      {{-- Danh sách phản hồi --}}
      <div class="collapse comments mt-3" id="comments-{{ $banTin->id }}">
        @foreach ($banTin->list_ban_tin_con as $cmt)
          <div class="d-flex align-items-start mb-3 position-relative">
            <img src="{{ asset('storage/' . $cmt->thanh_vien_lop->nguoi_dung->hinh_anh) }}" class="rounded-circle me-3"
              width="40" height="40" alt="Avatar">
            <div class="bg-light rounded p-2 w-100">
              <div class="d-flex justify-content-between">
                <h6 class="mb-1">{{ $cmt->thanh_vien_lop->nguoi_dung->ho_ten }}</h6>
                <small class="text-muted me-5">
                  <i class="far fa-clock me-1"></i> {{ $cmt->ngay_tao }}
                </small>
              </div>

              <p class="mb-1 noi-dung-phan-hoi">{{ $cmt->noi_dung }}</p>

              @if ($nguoiDung->id == $cmt->thanh_vien_lop->nguoi_dung->id)
                <div class="dropdown text-end child-news-action-btn">
                  <button class="btn btn-sm btn-light" data-bs-toggle="dropdown">
                    <i class="fas fa-ellipsis-h"></i>
                  </button>
                  <ul class="dropdown-menu">
                    <li>
                      <button class="dropdown-item btn-update-phan-hoi" type="button"
                        data-form-reply="#form-reply-{{ $banTin->id }}"
                        data-form-update-reply="#form-update-reply-{{ $banTin->id }}"
                        data-url-update="{{ route('phan-hoi.update', $cmt->id) }}">
                        <i class="fas fa-edit me-2"></i> Chỉnh sửa phản hồi
                      </button>
                    </li>
                    <li>
                      <button class="dropdown-item text-danger btn-delete-ban-tin" type="button" data-type="phản hồi"
                        data-url-delete="{{ route('ban-tin.delete', $cmt->id) }}">
                        <i class="fas fa-trash-alt me-2 text-danger"></i> Xóa phản hồi
                      </button>
                    </li>
                  </ul>
                </div>
              @endif
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </div>
@endforeach
