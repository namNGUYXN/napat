@foreach ($listBanTin as $banTin)
  <!-- Mỗi bản tin là một thẻ -->
  <div class="card news-item overflow-hidden mb-5">
    <div class="card-body position-relative">
      <!-- Avatar người đăng -->
      <div class="d-flex align-items-center me-4">
        <img src="{{ asset('storage/' . $banTin->thanh_vien_lop->nguoi_dung->hinh_anh) }}"
          class="border border-secondary rounded-circle me-3" alt="Avatar" width="40" height="40">
        <h6 class="card-title">
          {{ $banTin->thanh_vien_lop->nguoi_dung->vai_tro ?? '' }}
          : {{ $banTin->thanh_vien_lop->nguoi_dung->ho_ten }}
          <small class="text-body-tertiary fst-italic">
            (Đã đăng vào lúc {{ $banTin->ngay_tao }})
          </small>
        </h6>
      </div>
      <div class="news-content mt-3 clearfix">
        {!! $banTin->noi_dung !!}
      </div>

      <!-- Nút hiển thị số phản hồi -->
      <div class="mt-2">
        @if (count($banTin->list_ban_tin_con) > 0)
          <a href="javascript:void(0)" class="text-primary toggle-comments" data-bs-toggle="collapse"
            data-bs-target="#comments-{{ $banTin->id }}">
            {{ count($banTin->list_ban_tin_con) }} phản hồi
          </a>
        @endif

      </div>
      @if ($nguoiDung->id == $banTin->thanh_vien_lop->nguoi_dung->id)
        <div class="news-action-btn">
          <div class="dropdown">
            <button class="btn btn-transparent dropdown-toggle remove-arrow-down" type="button"
              data-bs-toggle="dropdown" aria-expanded="false">
              <i class="fas fa-ellipsis-v"></i>
            </button>
            <ul class="dropdown-menu">
              <li>
                <button class="dropdown-item btn-update-ban-tin" type="button"
                  data-url-detail="{{ route('ban-tin.detail', $banTin->id) }}"
                  data-url-update="{{ route('ban-tin.update', $banTin->id) }}">
                  Chỉnh sửa bản tin
                </button>
              </li>
              <li>
                <button class="dropdown-item btn-delete-ban-tin" type="button" data-type="bản tin"
                  data-url-delete={{ route('ban-tin.delete', $banTin->id) }}>Xóa bản tin</button>
              </li>
            </ul>
          </div>
        </div>
      @endif
    </div>

    <div class="card-footer bg-white py-3">
      <!-- Form phản hồi -->
      <form action="{{ route('ban-tin.reply', [$lopHocPhan->id, $banTin->id]) }}" method="POST"
        id="form-reply-{{ $banTin->id }}" class="form-reply">
        @csrf
        <div class="d-flex align-items-start gap-2">
          <img src="{{ asset('storage/' . $nguoiDung->hinh_anh) }}" alt="Avatar"
            class="border border-secondary rounded-circle me-3" width="40" height="40">
          <div class="flex-grow-1">
            <div class="input-group">
              <input type="text" class="form-control" name="noi_dung" placeholder="Nhập phản hồi..."
                autocomplete="off">
              <button class="btn btn-outline-primary" type="submit">Gửi</button>
            </div>
          </div>
        </div>
      </form>

      {{-- Form chỉnh sửa phản hồi --}}
      <form action="" method="POST" id="form-update-reply-{{ $banTin->id }}" style="display: none;">
        @csrf
        <div class="d-flex align-items-start gap-2">
          <img src="{{ asset('storage/' . $nguoiDung->hinh_anh) }}" alt="Avatar"
            class="border border-secondary rounded-circle me-3" width="40" height="40">
          <div class="flex-grow-1">
            <div class="input-group">
              <input type="text" class="form-control" name="noi_dung" placeholder="Nhập phản hồi..."
                autocomplete="off">
              <button class="btn btn-outline-primary" type="submit">Chỉnh sửa</button>
              <button type="button" class="btn btn-outline-secondary btn-cancel-update-reply" data-form-reply="#form-reply-{{ $banTin->id }}">Hủy</button>
            </div>
          </div>
        </div>
      </form>

      <!-- Danh sách bình luận - collapse -->
      <div class="collapse comments pe-3 my-2 custom-scrollbar" id="comments-{{ $banTin->id }}">
        <!-- Bình luận 1 -->
        @foreach ($banTin->list_ban_tin_con as $cmt)
          <div class="d-flex align-items-start mt-3 position-relative">
            <img src="{{ asset('storage/' . $cmt->thanh_vien_lop->nguoi_dung->hinh_anh) }}"
              class="border border-secondary rounded-circle me-4" alt="Avatar" width="40" height="40">
            <div class="bg-body-secondary rounded p-2 flex-grow-1">
              <h6>
                {{ $cmt->thanh_vien_lop->nguoi_dung->ho_ten }}
                <small class="text-body-tertiary fst-italic">
                  (Đã đăng vào lúc {{ $cmt->ngay_tao }})
                </small>
              </h6>
              <p class="mb-0 noi-dung-phan-hoi">{{ $cmt->noi_dung }}</p>
              @if ($nguoiDung->id == $cmt->thanh_vien_lop->nguoi_dung->id)
                <div class="child-news-action-btn">
                  <div class="dropdown">
                    <button class="btn btn-transparent dropdown-toggle remove-arrow-down" type="button"
                      data-bs-toggle="dropdown" aria-expanded="false">
                      <i class="fas fa-ellipsis-v"></i>
                    </button>
                    <ul class="dropdown-menu">
                      <li>
                        <button class="dropdown-item btn-update-phan-hoi" type="button"
                          data-form-reply="#form-reply-{{ $banTin->id }}"
                          data-form-update-reply="#form-update-reply-{{ $banTin->id }}"
                          data-url-update="{{ route('phan-hoi.update', $cmt->id) }}">
                          Chỉnh sửa phản hồi</button>
                      </li>
                      <li>
                        <button class="dropdown-item btn-delete-ban-tin" type="button" data-type="phản hồi"
                          data-url-delete="{{ route('ban-tin.delete', $cmt->id) }}">Xóa phản hồi</button>
                      </li>
                    </ul>
                  </div>
                </div>
              @endif
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </div>
@endforeach
