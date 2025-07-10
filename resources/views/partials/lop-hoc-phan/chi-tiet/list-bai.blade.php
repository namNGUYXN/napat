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
        <div class="accordion-item rounded-3 shadow-sm mb-3 border border-light-subtle">
            <h2 class="accordion-header" id="heading-{{ $key }}">
                <button class="accordion-button collapsed fw-semibold py-3 px-4 fw-bold" type="button" data-bs-toggle="collapse"
                    data-bs-target="#collapse-{{ $key }}" aria-expanded="false"
                    aria-controls="collapse-{{ $key }}">
                    <i class="bi bi-journal-text me-2 text-primary "></i>
                    {{ $chuong->tieu_de }}
                </button>
            </h2>
            <div id="collapse-{{ $key }}" class="accordion-collapse collapse"
                aria-labelledby="heading-{{ $key }}" data-bs-parent="#lectureAccordion">
                <div class="accordion-body px-4 pb-4 pt-2">
                    <div class="table-responsive custom-scrollbar">
                        <table class="table table-hover table-striped align-middle caption-top mb-0">
                            <caption class="text-secondary small ps-2">
                                @if (session('id_nguoi_dung') == $lopHocPhan->id_giang_vien)
                                    Có {{ $chuong->list_bai->count() }} bài trong chương
                                @else
                                    Có {{ $soBaiCongKhai }} bài trong chương
                                @endif
                            </caption>
                            <thead class="table-light">
                                <tr>
                                    @if (session('id_nguoi_dung') == $lopHocPhan->id_giang_vien)
                                        <th scope="col" style="width: 60px;" class="text-center">
                                            <div class="form-check form-switch d-flex justify-content-center">
                                                <input class="form-check-input check-all" type="checkbox" role="switch"
                                                    title="Chọn tất cả">
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
                                                <td class="text-center">
                                                    <div class="form-check form-switch d-flex justify-content-center">
                                                        <input class="form-check-input row-checkbox" type="checkbox"
                                                            role="switch" title="Công khai bài học"
                                                            data-id="{{ $bai->id }}"{{ $isChecked ? ' checked' : '' }}>
                                                    </div>
                                                </td>
                                            @endif
                                            <td>
                                                <a href="{{ route('bai-trong-lop.detail', [$lopHocPhan->id, $bai->slug]) }}"
                                                    class="text-decoration-none text-dark d-flex align-items-center fw-bold">
                                                    <i class="bi bi-play-circle me-2 text-success"></i>
                                                    {{ $bai->tieu_de }}
                                                </a>
                                            </td>
                                        </tr>
                                    @endif
                                @empty
                                    <tr>
                                        <td colspan="2" class="text-center text-muted fst-italic">
                                            Chương chưa có bài học
                                        </td>
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

{{-- Nút Cập nhật cố định ở cuối --}}
@if (session('id_nguoi_dung') == $lopHocPhan->id_giang_vien)
    <div class="text-end position-sticky bottom-0 bg-transparent pt-2 pb-2" style="z-index: 10;">
        <button type="button" class="btn btn-success btn-public-bai px-4 py-2"
            title="Cập nhật trạng thái công khai cho các bài học">
            <i class="bi bi-cloud-upload me-2"></i> Cập nhật
        </button>
    </div>
@endif
