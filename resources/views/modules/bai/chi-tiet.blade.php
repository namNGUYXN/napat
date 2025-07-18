@extends('layouts.app')

@section('title', 'Chi tiết bài học')

@section('content')
    <!-- Main Content -->
    <div class="col bg-light p-4 overflow-auto custom-scrollbar">
        <a href="{{ route('lop-hoc.detail', $baiTrongLop->lop->slug) }}" class="btn btn-outline-secondary mb-4">
            <i class="fas fa-arrow-alt-circle-left me-2"></i>Quay lại lớp học
        </a>

        <ul class="nav nav-tabs mb-4" id="lectureTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active fw-bold" id="content-tab" data-bs-toggle="tab" data-bs-target="#content"
                    type="button" role="tab" aria-controls="content" aria-selected="true">Nội dung</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-bold" id="exercise-tab" data-bs-toggle="tab" data-bs-target="#exercise"
                    type="button" role="tab" aria-controls="exercise" aria-selected="false">Bài tập</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-bold" id="discussion-tab" data-bs-toggle="tab" data-bs-target="#discussion"
                    type="button" role="tab" aria-controls="discussion" aria-selected="false">Thảo luận</button>
            </li>
            @if (session('vai_tro') == 'Giảng viên')
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-bold" id="setting-tab" data-bs-toggle="tab" data-bs-target="#setting"
                        type="button" role="tab" aria-controls="setting" aria-selected="true">Tùy chỉnh</button>
                </li>
            @endif
        </ul>

        <div class="tab-content" id="lectureTabsContent">
            <!-- Tab Nội dung -->
            <div class="tab-pane fade show active" id="content" role="tabpanel" aria-labelledby="content-tab">
                <div class="container-fluid border rounded bg-white shadow-sm p-4">
                    <h4>{{ $baiTrongLop->bai->tieu_de }}</h4>

                    <div id="content-bai" class="custom-scrollbar position-relative">
                        {!! $baiTrongLop->bai->noi_dung !!}
                    </div>
                    @if (session('vai_tro') == 'Sinh viên' && !$baiTrongLop->hoan_thanh_khi)
                        @if (!$daHoanThanh)
                            <div id="formWrapper">
                                <form id="formDanhDauHoanThanh" class="mt-4"
                                    data-url="{{ route('tien-do-hoc-tap.danh-dau-hoan-thanh', [$baiTrongLop->id]) }}">
                                    @csrf
                                    <button type="submit" class="btn btn-success rounded-pill px-4 py-2">
                                        <i class="fas fa-check-circle me-1"></i> Đánh dấu hoàn thành bài học
                                    </button>
                                </form>
                            </div>
                        @else
                            <div class="alert alert-success mt-4 d-inline-flex align-items-center" role="alert">
                                <i class="fas fa-check-circle me-2"></i>
                                Bạn đã hoàn thành bài học này.
                            </div>
                        @endif
                    @endif

                </div>
            </div>

            <!-- Tab Bài tập -->
            <div class="tab-pane fade" id="exercise" role="tabpanel" aria-labelledby="exercise-tab">
                <div class="card border-0 shadow">
                    {{-- Tiêu đề và nút tạo bài tập (nếu cần) --}}
                    {{-- 
                        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center rounded-top">
                            <h5 class="mb-0">Danh sách bài tập</h5>
                            @if (session('vai_tro') == 'Giảng viên')
                                <button class="btn btn-light btn-sm" id="addNewExerciseBtn">
                                    <i class="fas fa-plus-circle me-2"></i>Tạo bài tập
                                </button>
                            @endif
                        </div>
                        --}}

                    <div class="card-body">
                        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3 mt-1" id="danhSachBaiTap"
                            data-lop-id="{{ $lopHocPhan->id }}">

                            @forelse ($baiTap as $item)
                                <div class="col">
                                    <div class="card h-100 border border-3 border-primary rounded-4 shadow-sm item-bai-kiem-tra cursor-pointer"
                                        data-id="{{ $item->id }}" style="transition: transform 0.2s ease;">
                                        <div class="card-body d-flex flex-column justify-content-between">
                                            <div>
                                                <h5 class="card-title fw-bold text-dark d-flex align-items-center mb-3">
                                                    <i class="bi bi-journal-text text-primary me-3 fs-2"></i>
                                                    {{ $item->tieu_de }}
                                                </h5>
                                                <div class="text-muted small d-flex align-items-center">
                                                    <i class="bi bi-calendar-check text-success me-2"></i>
                                                    <span>Ngày Tạo:
                                                        {{ \Carbon\Carbon::parse($item->ngay_tao)->format('d/m/Y H:i') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col">
                                    <div class="alert alert-info text-center">
                                        Không có bài tập nào được giao.
                                    </div>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Modal chi tiết bài tập -->
                    <div class="modal fade" id="modalChiTiet" tabindex="-1">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header d-flex justify-content-between align-items-center">
                                    <h5 class="modal-title">Chi tiết bài tập</h5>
                                    <div class="modal-actions">
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                </div>
                                <div class="modal-body overflow-auto" id="modalChiTietBody" style="max-height: 60vh;">
                                    <!-- Nội dung sẽ được render bằng JS -->
                                </div>
                                <div class="modal-footer">
                                    <!-- Tùy chọn thêm -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Tab Thảo luận -->
            <div class="tab-pane fade" id="discussion" role="tabpanel" aria-labelledby="discussion-tab">
                <div class="container-fluid">
                    <div class="card mb-4">
                        <div class="card-header bg-secondary text-white">
                            <h5 class="mb-0">Để lại bình luận của bạn</h5>
                        </div>
                        <div class="card-body">
                            <form
                                action="{{ route('binh-luan.store', [$baiTrongLop->id_lop_hoc_phan, $baiTrongLop->id_bai]) }}"
                                id="commentForm" method="POST" class="d-flex align-items-end">
                                @csrf
                                <div class="flex-grow-1 me-2">
                                    <label for="newCommentContent" class="form-label visually-hidden">Nội dung bình
                                        luận</label>
                                    <textarea class="form-control" name="noi_dung" id="newCommentContent" rows="3"
                                        placeholder="Viết bình luận của bạn tại đây..." required></textarea>
                                    <small class="text-danger binh-luan-error"></small>
                                </div>
                                <button type="submit" class="btn btn-primary">Gửi</button>
                            </form>
                        </div>
                    </div>

                    <div id="commentsList">
                        @include('partials.lop-hoc-phan.noi-dung-bai.list-binh-luan', [
                            $listBinhLuan,
                            $baiTrongLop,
                        ])
                    </div>

                </div>
            </div>
            <!-- Tab tùy chỉnh -->
            @if (session('vai_tro') == 'Giảng viên')
                <div class="tab-pane fade " id="setting" role="tabpanel" aria-labelledby="setting-tab">
                    <div class="card shadow-sm border-0 rounded-4">
                        <div class="card-body">
                            <h5 class="fw-bold mb-4 text-primary">
                                <i class="fas fa-check-circle me-2"></i>Điều kiện hoàn thành bài học
                            </h5>

                            <form id="formHoanThanhBai" method="POST"
                                data-url="{{ route('bai-trong-lop.cap-nhat-hoan-thanh', [$baiTrongLop->id]) }}">
                                @csrf

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <div class="form-check border p-3 rounded-3 bg-light hover-shadow-sm">
                                            <input class="form-check-input me-2" type="radio" name="hoan_thanh_khi"
                                                id="hoanThanhTuDanhDau" value="0"
                                                {{ !$baiTrongLop->hoan_thanh_khi ? 'checked' : '' }}>
                                            <label class="form-check-label" for="hoanThanhTuDanhDau">
                                                <strong>Sinh viên tự đánh dấu hoàn thành</strong><br>
                                                <small class="text-muted">Sinh viên chủ động xác nhận đã học xong.</small>
                                            </label>
                                        </div>
                                    </div>

                                    @if ($baiTap->count() > 0)
                                        <div class="col-md-6 mb-3">
                                            <div class="form-check border p-3 rounded-3 bg-light hover-shadow-sm">
                                                <input class="form-check-input me-2" type="radio" name="hoan_thanh_khi"
                                                    id="hoanThanhBaiTap" value="1"
                                                    {{ $baiTrongLop->hoan_thanh_khi ? 'checked' : '' }}>
                                                <label class="form-check-label" for="hoanThanhBaiTap">
                                                    <strong>Hoàn thành bài tập được giao</strong><br>
                                                    <small class="text-muted">Sinh viên cần nộp và hoàn thành bài tập để
                                                        được đánh dấu.</small>
                                                </label>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <div class="mt-4 text-end">
                                    <button type="submit" class="btn btn-primary px-4">
                                        <i class="fas fa-save me-1"></i> Lưu thay đổi
                                    </button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            @endif

        </div>

        <button class="btn btn-primary floating-btn rounded-circle" id="toggleMenu">
            <i class="fas fa-list-ul"></i>
        </button>

        <div id="slideMenu" class="p-3 custom-scrollbar">
            <h6>Danh sách chương & bài giảng</h6>
            <hr>
            {{-- <form action="{{ route('bai-trong-lop.quick-search', $lopHocPhan->id) }}" id="form-search-bai" method="GET"> --}}
            <form action="{{ route('bai-trong-lop.detail', [$lopHocPhan->id, $baiTrongLop->bai->slug]) }}"
                id="form-search-bai" method="GET">
                <div class="input-group">
                    <input type="text" class="form-control" name="search" value="{{ request()->input('search') }}"
                        placeholder="Nhập tiêu đề bài cần tìm..." id="search-input" autocomplete="off">
                    <button class="btn btn-outline-secondary">
                        <i class="fas fa-search"></i> </button>
                </div>
            </form>
            <div class="list-group" id="list-bai">
                @include('partials.lop-hoc-phan.noi-dung-bai.list-bai', [
                    $listChuong,
                    $listChuongTrongLop,
                    $lopHocPhan,
                ])
            </div>
        </div>
    @endsection

    @section('styles')
        <link rel="stylesheet" href="{{ asset('modules/bai/css/chi-tiet.css') }}">
    @endsection

    @section('scripts')
        <script src="{{ asset('modules/bai/js/chi-tiet.js') }}"></script>
    @endsection
