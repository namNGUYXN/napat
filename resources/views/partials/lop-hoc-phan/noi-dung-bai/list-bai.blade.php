@php
  $timThay = false;
@endphp

@foreach ($listChuong as $chuong)
  @php
    $chuongTrongLop = isset($listChuongTrongLop[$chuong->id]) ? $listChuongTrongLop[$chuong->id] : collect([]);
    $hasBaiCongKhai = $chuongTrongLop->flatten(1)->contains(function ($bai) {
        return $bai->pivot->cong_khai == true;
    });
  @endphp
  @if ($hasBaiCongKhai || session('id_nguoi_dung') == $lopHocPhan->id_giang_vien)
    @php
      $timThay = true;
    @endphp
    <h5 class="mt-4">{{ $chuong->tieu_de }}</h5>

    @forelse ($chuongTrongLop as $bai)
      @php
        $isPublic = $bai->pivot->cong_khai;
        $url = route('bai-trong-lop.detail', [$lopHocPhan->id, $bai->slug]);
        $isActive = request()->url() == $url ? 'active sticky-top' : '';
      @endphp

      @if ($isPublic || session('id_nguoi_dung') == $lopHocPhan->id_giang_vien)
        <a href="{{ $url }}" class="list-group-item list-group-item-action {{ $isActive }} ps-4">
          {{ $bai->tieu_de }}
        </a>
      @endif
    @empty
      <p>Không tìm thấy bài hoặc chương chưa có bài học</p>
    @endforelse
  @endif
@endforeach

@if (!$timThay)
    <p class="text-center">Không tìm thấy bài hoặc chương chưa có bài học</p>
@endif