@foreach ($listChuong as $key => $chuong)
  @php
    $chuongTrongLop = isset($listChuongTrongLop[$chuong->id]) ? $listChuongTrongLop[$chuong->id] : collect([]);
    $soBaiCongKhai = $chuongTrongLop
        ->filter(function ($bai) {
            return $bai->pivot->cong_khai == true;
        })
        ->count();
    $hasBaiCongKhai = $chuongTrongLop->flatten(1)->contains(function ($bai) {
        return $bai->pivot->cong_khai == true;
    });
  @endphp

  @if ($hasBaiCongKhai || session('id_nguoi_dung') == $lopHocPhan->id_giang_vien)
    <div class="accordion-item">
      <h2 class="accordion-header" id="heading-{{ $key }}">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
          data-bs-target="#collapse-{{ $key }}" aria-expanded="false"
          aria-controls="collapse-{{ $key }}">
          {{ $chuong->tieu_de }}
        </button>
      </h2>
      <div id="collapse-{{ $key }}" class="accordion-collapse collapse"
        aria-labelledby="heading-{{ $key }}" data-bs-parent="#lectureAccordion">
        <div class="accordion-body">
          <div class="table-responsive custom-scrollbar">

            <table class="table table-hover table-striped caption-top">
              @if (session('id_nguoi_dung') == $lopHocPhan->id_giang_vien)
                <caption>Có {{ $chuong->list_bai->count() }} bài trong chương
                </caption>
              @else
                <caption>Có {{ $soBaiCongKhai }} bài trong chương</caption>
              @endif
              <thead>
                <tr>
                  @if (session('id_nguoi_dung') == $lopHocPhan->id_giang_vien)
                    <th scope="col">
                      <div class="form-check form-switch me-3" style="max-width: 50px;">
                        <input class="form-check-input check-all" type="checkbox" role="switch"
                          title="Check tất cả">
                      </div>
                    </th>
                  @endif
                  <th scope="col" class="w-100">Tiêu đề</th>
                </tr>
              </thead>
              <tbody>
                @forelse ($chuongTrongLop as $index => $bai)
                  @php
                    $isChecked = $bai->pivot->cong_khai;
                  @endphp

                  @if ($isChecked || session('id_nguoi_dung') == $lopHocPhan->id_giang_vien)
                    <tr>
                      @if (session('id_nguoi_dung') == $lopHocPhan->id_giang_vien)
                        <td>
                          <div class="form-check form-switch">
                            <input class="form-check-input row-checkbox" type="checkbox" role="switch"
                              title="Công khai bài học" data-id="{{ $bai->id }}"{{ $isChecked ? ' checked' : '' }}>
                          </div>
                        </td>
                      @endif
                      <td class="align-middle">
                        <a href="{{ route('bai-trong-lop.detail', [$lopHocPhan->id, $bai->slug]) }}"
                          class="link-dark link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover">
                          {{ $bai->tieu_de }}
                        </a>
                      </td>
                    </tr>
                  @endif

                @empty
                  <tr>
                    <td colspan="2">Chương chưa có bài học</td>
                  </tr>
                @endforelse
              </tbody>
            </table>

          </div>
        </div>
      </div>
    </div>
  @endif
@endforeach

@if (session('id_nguoi_dung') == $lopHocPhan->id_giang_vien)
  <button type="button" class="btn btn-success mt-3 btn-public-bai"
    title="Cập nhật trạng thái công khai cho các bài học">
    Cập nhật
  </button>
@endif
