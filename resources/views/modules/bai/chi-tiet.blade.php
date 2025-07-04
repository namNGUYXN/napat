@extends('layouts.app')

@section('content')
    <!-- Main Content -->
    <div class="col bg-light p-4 overflow-auto custom-scrollbar">
        <a href="{{ route('lop-hoc.detail', $baiTrongLop->lop->slug) }}" class="btn btn-outline-secondary mb-4">
            <i class="fas fa-arrow-alt-circle-left me-2"></i>Quay lại lớp học
        </a>

        <ul class="nav nav-tabs mb-4" id="lectureTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="content-tab" data-bs-toggle="tab" data-bs-target="#content"
                    type="button" role="tab" aria-controls="content" aria-selected="true">Nội dung</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="exercise-tab" data-bs-toggle="tab" data-bs-target="#exercise" type="button"
                    role="tab" aria-controls="exercise" aria-selected="false">Bài tập</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="discussion-tab" data-bs-toggle="tab" data-bs-target="#discussion"
                    type="button" role="tab" aria-controls="discussion" aria-selected="false">Thảo luận</button>
            </li>
        </ul>

        <div class="tab-content" id="lectureTabsContent">
            <!-- Tab Nội dung -->
            <div class="tab-pane fade show active" id="content" role="tabpanel" aria-labelledby="content-tab">
                <div class="container-fluid border rounded bg-white shadow-sm p-4">
                    <h4>{{ $baiTrongLop->bai->tieu_de }}</h4>

                    <div id="content-bai" class="custom-scrollbar">
                        {!! $baiTrongLop->bai->noi_dung !!}
                    </div>
                </div>
            </div>


            <!-- Tab Bài tập -->
            <div class="tab-pane fade" id="exercise" role="tabpanel" aria-labelledby="exercise-tab">
                <div class="card">
                    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Danh sách bài tập</h5>
                        @if (session('vai_tro') == 'Giảng viên')
                            <button class="btn btn-light btn-sm" id="addNewExerciseBtn">
                                <i class="fas fa-plus-circle me-2"></i>Tạo bài tập
                            </button>
                        @endif
                    </div>
                    <div class="card-body">
                        <!-- Danh sách bài kiểm tra -->
                        <div class="row row-cols-1 row-cols-md-2 g-3 mt-2" id="danhSachBaiTap"
                            data-lop-id="{{ $lopHocPhan->id }}">
                            @foreach ($baiTap as $item)
                                <div class="col mb-3">
                                    <div class="card shadow-sm h-100 cursor-pointer item-bai-kiem-tra"
                                        data-id="{{ $item->id }}">
                                        <div class="card-body">
                                            <h5 class="card-title">{{ $item->tieu_de }}</h5>
                                            <p class="card-text mb-1">
                                                <i class="bi bi-calendar-check"></i> Ngày đăng:
                                                {{ \Carbon\Carbon::parse($item->ngay_tao)->format('d/m/Y H:i') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
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
                            <form id="commentForm" class="d-flex align-items-end">
                                <div class="flex-grow-1 me-2">
                                    <label for="newCommentContent" class="form-label visually-hidden">Nội dung bình
                                        luận</label>
                                    <textarea class="form-control" id="newCommentContent" rows="3" placeholder="Viết bình luận của bạn tại đây..."
                                        required></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">Gửi</button>
                            </form>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header bg-dark text-white">
                            <h5 class="mb-0">Danh sách bình luận</h5>
                        </div>
                        <div class="card-body">
                            <div id="commentsList" class="list-group list-group-flush">

                                <div class="list-group-item comment-item" data-comment-id="cmt1"
                                    data-comment-owner-id="userA" data-comment-level="0">
                                    <div class="d-flex w-100 justify-content-between align-items-center">
                                        <h6 class="mb-1 me-auto">Người dùng A</h6>
                                        <small class="text-muted me-2">2 giờ trước</small>

                                        <div class="dropdown comment-actions-dropdown">
                                            <button class="btn btn-transparent dropdown-toggle hide-arrow-down"
                                                type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li><a class="dropdown-item edit-comment-btn" href="#"
                                                        data-comment-id="cmt1">Chỉnh sửa</a>
                                                </li>
                                                <li><a class="dropdown-item delete-comment-btn" href="#"
                                                        data-comment-id="cmt1">Xóa</a></li>
                                            </ul>
                                        </div>

                                    </div>
                                    <p class="mb-1 comment-content-text">Đây là nội dung của bình luận đầu tiên.</p>

                                    <div class="edit-form-container mt-2" style="display: none;">
                                        <form class="edit-comment-form d-flex align-items-end">
                                            <div class="flex-grow-1 me-2">
                                                <textarea class="form-control form-control-sm" rows="2" required="">Đây là nội dung của bình luận đầu tiên.</textarea>
                                            </div>
                                            <button type="submit" class="btn btn-sm btn-success me-1">Lưu</button>
                                            <button type="button"
                                                class="btn btn-sm btn-secondary cancel-edit-btn">Hủy</button>
                                        </form>
                                    </div>

                                    <small class="comment-action-links">

                                        <a href="#" class="text-primary me-2 reply-btn" data-comment-id="cmt1"
                                            data-comment-author="Người dùng A" data-comment-level="0">Phản hồi</a>

                                    </small>


                                    <small>
                                        <a href="#" class="toggle-replies-btn text-muted" data-comment-id="cmt1"
                                            data-has-replies="true" data-toggle-state="hidden">
                                            Có 1 phản hồi <i class="fas fa-caret-down"></i>
                                        </a>
                                    </small>


                                    <div class="reply-form-container mt-2" style="display: none;">
                                        <form class="reply-form d-flex align-items-end">
                                            <div class="flex-grow-1 me-2">
                                                <textarea class="form-control form-control-sm" rows="2" placeholder="Phản hồi lại Người dùng A..."
                                                    required=""></textarea>
                                            </div>
                                            <button type="submit" class="btn btn-sm btn-outline-primary">Gửi</button>
                                        </form>
                                    </div>

                                    <div class="replies-container mt-2 hidden-replies">

                                        <div class="list-group-item comment-item" data-comment-id="cmt1.1"
                                            data-comment-owner-id="userB" data-comment-level="1">
                                            <div class="d-flex w-100 justify-content-between align-items-center">
                                                <h6 class="mb-1 me-auto">Người dùng B</h6>
                                                <small class="text-muted me-2">1 giờ trước</small>

                                            </div>
                                            <p class="mb-1 comment-content-text">@Người dùng A: Tôi hoàn toàn đồng ý!</p>

                                            <div class="edit-form-container mt-2" style="display: none;">
                                                <form class="edit-comment-form d-flex align-items-end">
                                                    <div class="flex-grow-1 me-2">
                                                        <textarea class="form-control form-control-sm" rows="2" required="">@Người dùng A: Tôi hoàn toàn đồng ý!</textarea>
                                                    </div>
                                                    <button type="submit"
                                                        class="btn btn-sm btn-success me-1">Lưu</button>
                                                    <button type="button"
                                                        class="btn btn-sm btn-secondary cancel-edit-btn">Hủy</button>
                                                </form>
                                            </div>

                                            <small class="comment-action-links">

                                                <a href="#" class="text-primary me-2 reply-btn"
                                                    data-comment-id="cmt1.1" data-comment-author="Người dùng B"
                                                    data-comment-level="1">Phản hồi</a>

                                            </small>


                                            <small>
                                                <a href="#" class="toggle-replies-btn text-muted"
                                                    data-comment-id="cmt1.1" data-has-replies="true"
                                                    data-toggle-state="hidden">
                                                    Có 2 phản hồi <i class="fas fa-caret-down"></i>
                                                </a>
                                            </small>


                                            <div class="reply-form-container mt-2" style="display: none;">
                                                <form class="reply-form d-flex align-items-end">
                                                    <div class="flex-grow-1 me-2">
                                                        <textarea class="form-control form-control-sm" rows="2" placeholder="Phản hồi lại Người dùng B..."
                                                            required=""></textarea>
                                                    </div>
                                                    <button type="submit"
                                                        class="btn btn-sm btn-outline-primary">Gửi</button>
                                                </form>
                                            </div>

                                            <div class="replies-container mt-2 hidden-replies">

                                                <div class="list-group-item comment-item" data-comment-id="cmt1.1.1"
                                                    data-comment-owner-id="userC" data-comment-level="2">
                                                    <div class="d-flex w-100 justify-content-between align-items-center">
                                                        <h6 class="mb-1 me-auto">Người dùng C</h6>
                                                        <small class="text-muted me-2">30 phút trước</small>

                                                    </div>
                                                    <p class="mb-1 comment-content-text">@Người dùng B: Cảm ơn bạn đã xác
                                                        nhận!</p>

                                                    <div class="edit-form-container mt-2" style="display: none;">
                                                        <form class="edit-comment-form d-flex align-items-end">
                                                            <div class="flex-grow-1 me-2">
                                                                <textarea class="form-control form-control-sm" rows="2" required="">@Người dùng B: Cảm ơn bạn đã xác nhận!</textarea>
                                                            </div>
                                                            <button type="submit"
                                                                class="btn btn-sm btn-success me-1">Lưu</button>
                                                            <button type="button"
                                                                class="btn btn-sm btn-secondary cancel-edit-btn">Hủy</button>
                                                        </form>
                                                    </div>

                                                    <small class="comment-action-links">

                                                        <a href="#" class="text-primary me-2 reply-btn"
                                                            data-comment-id="cmt1.1.1" data-comment-author="Người dùng C"
                                                            data-comment-level="2">Phản hồi</a>

                                                    </small>


                                                    <small>
                                                        <a href="#" class="toggle-replies-btn text-muted"
                                                            data-comment-id="cmt1.1.1" data-has-replies="true"
                                                            data-toggle-state="hidden">
                                                            Có 1 phản hồi <i class="fas fa-caret-down"></i>
                                                        </a>
                                                    </small>


                                                    <div class="reply-form-container mt-2" style="display: none;">
                                                        <form class="reply-form d-flex align-items-end">
                                                            <div class="flex-grow-1 me-2">
                                                                <textarea class="form-control form-control-sm" rows="2" placeholder="Phản hồi lại Người dùng C..."
                                                                    required=""></textarea>
                                                            </div>
                                                            <button type="submit"
                                                                class="btn btn-sm btn-outline-primary">Gửi</button>
                                                        </form>
                                                    </div>

                                                    <div class="replies-container mt-2 hidden-replies">

                                                        <div class="list-group-item comment-item"
                                                            data-comment-id="cmt1.1.1.1" data-comment-owner-id="userD"
                                                            data-comment-level="3">
                                                            <div
                                                                class="d-flex w-100 justify-content-between align-items-center">
                                                                <h6 class="mb-1 me-auto">Người dùng D</h6>
                                                                <small class="text-muted me-2">15 phút trước</small>

                                                            </div>
                                                            <p class="mb-1 comment-content-text">@Người dùng C: Không có
                                                                gì, rất vui được thảo luận.
                                                            </p>

                                                            <div class="edit-form-container mt-2" style="display: none;">
                                                                <form class="edit-comment-form d-flex align-items-end">
                                                                    <div class="flex-grow-1 me-2">
                                                                        <textarea class="form-control form-control-sm" rows="2" required="">@Người dùng C: Không có gì, rất vui được thảo luận.</textarea>
                                                                    </div>
                                                                    <button type="submit"
                                                                        class="btn btn-sm btn-success me-1">Lưu</button>
                                                                    <button type="button"
                                                                        class="btn btn-sm btn-secondary cancel-edit-btn">Hủy</button>
                                                                </form>
                                                            </div>

                                                            <small class="comment-action-links">

                                                            </small>



                                                            <div class="reply-form-container mt-2" style="display: none;">
                                                                <form class="reply-form d-flex align-items-end">
                                                                    <div class="flex-grow-1 me-2">
                                                                        <textarea class="form-control form-control-sm" rows="2" placeholder="Phản hồi lại Người dùng D..."
                                                                            required=""></textarea>
                                                                    </div>
                                                                    <button type="submit"
                                                                        class="btn btn-sm btn-outline-primary">Gửi</button>
                                                                </form>
                                                            </div>

                                                            <div class="replies-container mt-2 hidden-replies">

                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>

                                                <div class="list-group-item comment-item" data-comment-id="cmt1.1.2"
                                                    data-comment-owner-id="userE" data-comment-level="2">
                                                    <div class="d-flex w-100 justify-content-between align-items-center">
                                                        <h6 class="mb-1 me-auto">Người dùng E</h6>
                                                        <small class="text-muted me-2">20 phút trước</small>

                                                    </div>
                                                    <p class="mb-1 comment-content-text">@Người dùng B: Bình luận của bạn
                                                        rất có giá trị.</p>

                                                    <div class="edit-form-container mt-2" style="display: none;">
                                                        <form class="edit-comment-form d-flex align-items-end">
                                                            <div class="flex-grow-1 me-2">
                                                                <textarea class="form-control form-control-sm" rows="2" required="">@Người dùng B: Bình luận của bạn rất có giá trị.</textarea>
                                                            </div>
                                                            <button type="submit"
                                                                class="btn btn-sm btn-success me-1">Lưu</button>
                                                            <button type="button"
                                                                class="btn btn-sm btn-secondary cancel-edit-btn">Hủy</button>
                                                        </form>
                                                    </div>

                                                    <small class="comment-action-links">

                                                        <a href="#" class="text-primary me-2 reply-btn"
                                                            data-comment-id="cmt1.1.2" data-comment-author="Người dùng E"
                                                            data-comment-level="2">Phản hồi</a>

                                                    </small>



                                                    <div class="reply-form-container mt-2" style="display: none;">
                                                        <form class="reply-form d-flex align-items-end">
                                                            <div class="flex-grow-1 me-2">
                                                                <textarea class="form-control form-control-sm" rows="2" placeholder="Phản hồi lại Người dùng E..."
                                                                    required=""></textarea>
                                                            </div>
                                                            <button type="submit"
                                                                class="btn btn-sm btn-outline-primary">Gửi</button>
                                                        </form>
                                                    </div>

                                                    <div class="replies-container mt-2 hidden-replies">

                                                    </div>
                                                </div>

                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <div class="list-group-item comment-item" data-comment-id="cmt2"
                                    data-comment-owner-id="userA" data-comment-level="0">
                                    <div class="d-flex w-100 justify-content-between align-items-center">
                                        <h6 class="mb-1 me-auto">Admin</h6>
                                        <small class="text-muted me-2">Hôm qua</small>

                                        <div class="dropdown comment-actions-dropdown">
                                            <button class="btn btn-transparent dropdown-toggle hide-arrow-down"
                                                type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li><a class="dropdown-item edit-comment-btn" href="#"
                                                        data-comment-id="cmt2">Chỉnh
                                                        sửa</a></li>
                                                <li><a class="dropdown-item delete-comment-btn" href="#"
                                                        data-comment-id="cmt2">Xóa</a>
                                                </li>
                                            </ul>
                                        </div>

                                    </div>
                                    <p class="mb-1 comment-content-text">Chào mừng các bạn đến với phần bình luận!</p>

                                    <div class="edit-form-container mt-2" style="display: none;">
                                        <form class="edit-comment-form d-flex align-items-end">
                                            <div class="flex-grow-1 me-2">
                                                <textarea class="form-control form-control-sm" rows="2" required="">Chào mừng các bạn đến với phần bình luận!</textarea>
                                            </div>
                                            <button type="submit" class="btn btn-sm btn-success me-1">Lưu</button>
                                            <button type="button"
                                                class="btn btn-sm btn-secondary cancel-edit-btn">Hủy</button>
                                        </form>
                                    </div>

                                    <small class="comment-action-links">

                                        <a href="#" class="text-primary me-2 reply-btn" data-comment-id="cmt2"
                                            data-comment-author="Admin" data-comment-level="0">Phản hồi</a>

                                    </small>



                                    <div class="reply-form-container mt-2" style="display: none;">
                                        <form class="reply-form d-flex align-items-end">
                                            <div class="flex-grow-1 me-2">
                                                <textarea class="form-control form-control-sm" rows="2" placeholder="Phản hồi lại Admin..." required=""></textarea>
                                            </div>
                                            <button type="submit" class="btn btn-sm btn-outline-primary">Gửi</button>
                                        </form>
                                    </div>

                                    <div class="replies-container mt-2 hidden-replies">

                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <button class="btn btn-primary floating-btn rounded-circle" id="toggleMenu">
            <i class="fas fa-list-ul"></i>
        </button>

        <div id="slideMenu" class="p-3 custom-scrollbar">
            <h6>Danh sách chương & bài giảng</h6>
            <hr>
            <div class="list-group">
                @foreach ($listChuong as $chuong)
                    @php
                        $chuongTrongLop = isset($listChuongTrongLop[$chuong->id])
                            ? $listChuongTrongLop[$chuong->id]
                            : collect([]);
                        $hasBaiCongKhai = $chuongTrongLop->flatten(1)->contains(function ($bai) {
                            return $bai->pivot->cong_khai == true;
                        });
                    @endphp
                    @if ($hasBaiCongKhai || session('id_nguoi_dung') == $lopHocPhan->id_giang_vien)
                        <div class="mb-3">
                            <h5>{{ $chuong->tieu_de }}</h5>

                            @forelse ($chuongTrongLop as $bai)
                                @php
                                    $isPublic = $bai->pivot->cong_khai;
                                    $url = route('bai-trong-lop.detail', [$lopHocPhan->id, $bai->slug]);
                                    $isActive = request()->url() == $url ? 'active' : '';
                                @endphp

                                @if ($isPublic || session('id_nguoi_dung') == $lopHocPhan->id_giang_vien)
                                    <a href="{{ $url }}"
                                        class="list-group-item list-group-item-action {{ $isActive }} ps-4">
                                        {{ $bai->tieu_de }}
                                    </a>
                                @endif
                            @empty
                                <p>Chương chưa có bài học</p>
                            @endforelse

                        </div>
                    @endif
                @endforeach

            </div>
        </div>
    </div>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('modules/bai/css/chi-tiet.css') }}">
@endsection

@section('scripts')
    <script src="{{ asset('modules/bai/js/chi-tiet.js') }}"></script>
@endsection
