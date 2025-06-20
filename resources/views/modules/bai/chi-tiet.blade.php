@extends('layouts.app')

@section('content')
    <!-- Main Content -->
    <div class="col bg-light p-2 position-relative overflow-auto custom-scrollbar">
        <a href="#" class="btn btn-outline-secondary mb-4">
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
                    <h4>Bài giảng: Giới thiệu môn học</h4>
                    <p>Đây là nội dung bài giảng. Bạn có thể nhúng video, tài liệu HTML, hình ảnh tại đây.</p>
                    <img src="https://via.placeholder.com/800x400" class="img-fluid rounded mb-3" alt="Bài giảng">

                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi dapibus, ligula sed
                        facilisis
                        consequat, velit
                        libero laoreet erat, et iaculis risus risus at lorem.</p>
                </div>
            </div>


            <!-- Tab Bài tập -->
            <div class="tab-pane fade" id="exercise" role="tabpanel" aria-labelledby="exercise-tab">
                <div class="container-fluid p-4">
                    <p><em>Nội dung bài tập sẽ được hiển thị tại đây...</em></p>
                </div>
            </div>

            <!-- Tab Thảo luận -->
            <div class="tab-pane fade" id="discussion" role="tabpanel" aria-labelledby="discussion-tab">
                <div class="container-fluid p-4">
                    <p><em>Khu vực thảo luận sẽ hiển thị ở đây...</em></p>
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
                <div class="mb-3">
                    <h5>Chương 1</h5>
                    <a href="lecture-detail.html" class="list-group-item list-group-item-action active ps-4">Bài 1.1: Giới
                        thiệu</a>
                    <a href="lecture-detail.html" class="list-group-item list-group-item-action ps-4">Bài 1.2: Cài đặt</a>
                </div>
                <div class="mb-3">
                    <h5>Chương 2</h5>
                    <a href="lecture-detail.html" class="list-group-item list-group-item-action ps-4">Bài 2.1: HTML cơ
                        bản</a>
                    <a href="lecture-detail.html" class="list-group-item list-group-item-action ps-4">Bài 2.2: CSS cơ
                        bản</a>
                </div>
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
