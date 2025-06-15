@extends('layouts.app')

@section('title', 'Lớp học - chi tiết')

@section('content')
    <!-- Main Content -->
    <div class="col bg-light p-4 overflow-auto custom-scrollbar">

        <!-- PHẦN TRÊN: THÔNG TIN LỚP HỌC -->
        <div class="card mb-4">
            <div class="row g-0">
                <div class="col-md-6">
                    <img src="https://picsum.photos/id/6/1000/600" class="img-fluid rounded-start" alt="">
                </div>
                <div class="col-md-6 position-relative">
                    <div class="card-body">
                        <h5 class="card-title mb-3">{{ $lop->ten }} - {{ $lop->ma }}</h5>
                        <p class="card-text mb-1"><strong>Học phần:</strong> {{ $lop->hoc_phan->ten }}</p>
                        <p class="card-text mb-1"><strong>Giảng viên:</strong> {{ $lop->giang_vien->ho_ten }}</p>
                        <p class="card-text mb-1"><strong>Học kì:</strong> 2024 - 2025</p>
                        <p class="card-text mt-3 mb-0">
                            <small class="text-muted">{{ $lop->mo_ta_ngan }}</small>
                        </p>

                        <div class="class-action-btn">
                            <div class="dropdown">
                                <button class="btn btn-transparent dropdown-toggle remove-arrow-down" type="button"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <!-- Sinh viên -->
                                    <li><button class="dropdown-item" type="button">Đăng ký lớp học</button></li>
                                    <li><button class="dropdown-item" type="button">Rời lớp học</button></li>
                                    <!-- Giảng viên -->
                                    <li><button class="dropdown-item" type="button" data-bs-toggle="modal"
                                            data-bs-target="#updateClassModal">Chỉnh sửa lớp học</button>
                                    </li>
                                    <li><button class="dropdown-item" type="button">Xóa lớp học</button></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- PHẦN DƯỚI: TAB NỘI DUNG -->
        <ul class="nav nav-tabs mb-3" id="classTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="news-tab" data-bs-toggle="tab" data-bs-target="#news" type="button"
                    role="tab">Bản tin <span class="badge text-bg-danger">4</span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="lecture-tab" data-bs-toggle="tab" data-bs-target="#lecture" type="button"
                    role="tab">Bài giảng <span class="badge text-bg-danger">4</span></button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="exam-tab" data-bs-toggle="tab" data-bs-target="#exam" type="button"
                    role="tab">Bài kiểm tra <span class="badge text-bg-danger">4</span></button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="member-tab" data-bs-toggle="tab" data-bs-target="#member" type="button"
                    role="tab">Thành viên <span class="badge text-bg-danger">4</span></button>
            </li>
        </ul>

        <!--Nội dung-->
        <div class="tab-content" id="classTabContent">

            <!--Bản tin-->
            <div class="tab-pane fade show active" id="news" role="tabpanel">
                @foreach ($banTin as $item)
                    <!-- Mỗi bản tin là một thẻ -->
                    <div class="card news-item overflow-hidden mb-5" style="cursor: pointer;">
                        <div class="card-body d-flex">
                            <!-- Avatar người đăng -->
                            <img src="https://picsum.photos/id/54/400/400"
                                class="border border-secondary rounded-circle me-3" alt="Avatar" width="40"
                                height="40">
                            <div>
                                <h6 class="card-title mb-1">{{ $item->nguoi_dung->vai_tro ?? '' }}
                                    {{ $item->nguoi_dung->ho_ten }}</h6>
                                <div class="news-content" style="max-height: 100px; overflow: hidden;">
                                    <p class="mb-0">
                                        {{ $item->noi_dung }}
                                    </p>
                                </div>

                                <!-- Nút hiển thị số phản hồi -->
                                <div class="mt-2">
                                    @if (count($item->list_ban_tin_con) > 0)
                                        <a href="javascript:void(0)" class="text-primary toggle-comments"
                                            data-bs-toggle="collapse" data-bs-target="#comments-{{ $item->id }}">
                                            {{ count($item->list_ban_tin_con) }} phản hồi
                                        </a>
                                    @endif

                                </div>
                            </div>
                        </div>

                        <div class="card-footer bg-white">
                            <!-- Form phản hồi -->
                            <form>
                                <div class="d-flex align-items-start gap-2 mb-3">
                                    <img src="https://picsum.photos/id/54/400/400" alt="Avatar" class="rounded-circle"
                                        width="40" height="40">
                                    <div class="flex-grow-1">
                                        <div class="input-group">
                                            <input type="text" class="form-control" placeholder="Nhập phản hồi...">
                                            <button class="btn btn-outline-primary" type="submit">Gửi</button>
                                        </div>
                                    </div>
                                </div>
                            </form>

                            <!-- Danh sách bình luận - collapse -->
                            <div class="collapse" id="comments-{{ $item->id }}">
                                <!-- Bình luận 1 -->
                                @foreach ($item->list_ban_tin_con as $cmt)
                                    <div class="d-flex align-items-start mb-3">
                                        <img src="https://picsum.photos/id/54/400/400"
                                            class="border border-secondary rounded-circle me-2" alt="Avatar"
                                            width="36" height="36">
                                        <div class="bg-light rounded p-2 flex-grow-1">
                                            <strong>{{ $cmt->nguoi_dung->ho_ten }}</strong>
                                            <p class="mb-0">{{ $cmt->noi_dung }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
                <button type="button" class="newsletter-add-btn btn btn-primary rounded-circle" title="Tạo bản tin mới"
                    data-bs-toggle="modal" data-bs-target="#newNewsletterModal">
                    <i class="fas fa-plus"></i>
                </button>

                <!-- Modal -->
                <div class="modal fade" id="newNewsletterModal" tabindex="-1" aria-labelledby="newNewsletterModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header bg-primary text-white">
                                <h5 class="modal-title" id="newNewsletterModalLabel">Tạo bản tin mới</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form id="newNewsletterForm">
                                    <div class="mb-3">
                                        <label for="newsletterContent" class="form-label">Nội dung thông báo:</label>
                                        <textarea class="form-control textarea-tiny" id="newsletterContent" rows="8"
                                            placeholder="Nhập nội dung thông báo chi tiết" required></textarea>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                <button type="submit" form="newNewsletterForm" class="btn btn-primary">Lưu bản
                                    tin</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!--Bài giảng-->
            <div class="tab-pane fade" id="lecture" role="tabpanel">
                <div class="card">
                    <h5 class="card-header bg-dark text-white">Danh sách bài giảng</h5>
                    <div class="card-body">
                        <div class="accordion" id="lectureAccordion">
                            <!-- Chương 1 -->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="heading1">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapse1" aria-expanded="false" aria-controls="collapse1">
                                        Chương 1: Giới thiệu lập trình
                                    </button>
                                </h2>
                                <div id="collapse1" class="accordion-collapse collapse" aria-labelledby="heading1"
                                    data-bs-parent="#lectureAccordion">
                                    <div class="accordion-body">
                                        <div class="list-group">
                                            <div
                                                class="list-group-item list-group-item-action list-group-item-info d-flex justify-content-between align-items-center">
                                                <a href="#"
                                                    class="text-decoration-none text-info-emphasis flex-grow-1">
                                                    Bài giảng 1.1: Cài đặt môi trường
                                                </a>
                                                <button type="button"
                                                    class="btn btn-sm btn-outline-danger remove-lecture-btn"
                                                    data-chuong-id="1" data-bai-giang-id="1.1">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>

                                            <div
                                                class="list-group-item list-group-item-action list-group-item-info d-flex justify-content-between align-items-center">
                                                <a href="#"
                                                    class="text-decoration-none text-info-emphasis flex-grow-1">
                                                    Bài giảng 1.2: Cú pháp cơ bản
                                                </a>
                                                <button type="button"
                                                    class="btn btn-sm btn-outline-danger remove-lecture-btn"
                                                    data-chuong-id="1" data-bai-giang-id="1.2">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="text-center mt-3">
                                            <button class="btn btn-sm btn-outline-primary lecture-insert-btn"
                                                data-chuong-id="1" data-bs-toggle="modal"
                                                data-bs-target="#addLectureModal">
                                                <i class="fas fa-plus"></i> Chèn bài giảng
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Chương 2 -->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="heading2">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapse2" aria-expanded="false" aria-controls="collapse2">
                                        Chương 2: Biến và kiểu dữ liệu
                                    </button>
                                </h2>
                                <div id="collapse2" class="accordion-collapse collapse" aria-labelledby="heading2"
                                    data-bs-parent="#lectureAccordion">
                                    <div class="accordion-body">
                                        <div class="list-group">
                                            <a href="#"
                                                class="list-group-item list-group-item-action list-group-item-info">Bài
                                                giảng
                                                2.1:
                                                Biến và khai báo</a>
                                            <a href="#"
                                                class="list-group-item list-group-item-action list-group-item-info">Bài
                                                giảng
                                                2.2:
                                                Kiểu dữ liệu cơ bản</a>
                                        </div>
                                        <div class="text-center mt-3">
                                            <button class="btn btn-sm btn-outline-primary lecture-insert-btn"
                                                data-chuong-id="2" data-bs-toggle="modal"
                                                data-bs-target="#addLectureModal">
                                                <i class="fas fa-plus"></i> Chèn bài giảng
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Thêm chương khác tương tự -->

                            <!-- Modal -->
                            <div class="modal fade" id="addLectureModal" tabindex="-1"
                                aria-labelledby="addLectureModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-xl">
                                    <div class="modal-content">
                                        <div class="modal-header bg-primary text-white">
                                            <h5 class="modal-title" id="addLectureModalLabel">Chèn bài giảng</h5>
                                            <button type="button" class="btn-close btn-close-white"
                                                data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form id="insertLectureForm">
                                                <input type="hidden" id="chuongIdToInsert">
                                                <div class="mb-3">
                                                    <label for="documentSelect" class="form-label">Chọn bài giảng từ kho
                                                        tài liệu:</label>
                                                    <select class="form-select" id="documentSelect">
                                                        <option value="">-- Chọn tài liệu bài giảng --</option>
                                                    </select>
                                                </div>

                                                <hr class="my-4">

                                                <h5>Danh sách bài giảng trong tài liệu đã chọn:</h5>
                                                <div class="alert alert-warning" id="noDocumentSelectedAlert"
                                                    role="alert">
                                                    Vui lòng chọn một tài liệu bài giảng để xem danh sách bài giảng.
                                                </div>

                                                <div id="lectureListSection" style="display: none;">
                                                    <div class="input-group mb-3">
                                                        <input type="text" class="form-control"
                                                            placeholder="Tìm kiếm bài giảng theo tiêu đề..."
                                                            id="lectureSearchInput">
                                                        <button class="btn btn-outline-secondary" type="button"
                                                            id="searchLectureBtn">
                                                            <i class="fas fa-search"></i>
                                                        </button>
                                                    </div>

                                                    <div class="table-responsive"
                                                        style="max-height: 400px; overflow-y: auto;">
                                                        <table class="table table-hover table-striped">
                                                            <thead>
                                                                <tr>
                                                                    <th scope="col" style="width: 50px;">Chọn</th>
                                                                    <th scope="col">Tiêu đề bài giảng</th>
                                                                    <th scope="col">Mô tả</th>
                                                                    <th scope="col"></th>
                                                                </tr>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="lecturesInDocumentBody">
                                                                <tr>
                                                                    <td colspan="4" class="text-center">Chọn tài liệu
                                                                        để hiển thị bài giảng.</td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Đóng</button>
                                            <button type="button" class="btn btn-primary"
                                                id="insertSelectedLecturesBtn">Chèn bài
                                                giảng đã chọn</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal -->
                            <div class="modal fade" id="lectureDetailModal" tabindex="-1"
                                aria-labelledby="lectureDetailModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header bg-info text-white">
                                            <h5 class="modal-title" id="lectureDetailModalLabel">Chi tiết Bài giảng: <span
                                                    id="detailLectureTitle"></span></h5>
                                            <button type="button" class="btn-close btn-close-white"
                                                data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p><strong>Tiêu đề:</strong> <span id="detailLectureFullTitle"></span></p>
                                            <p><strong>Mô tả:</strong> <span id="detailLectureDescription"></span></p>
                                            <hr>
                                            <h6>Nội dung bài giảng:</h6>
                                            <div id="detailLectureContent" class="bg-light p-3 border rounded"
                                                style="max-height: 400px; overflow-y: auto;">
                                                Không có nội dung chi tiết.
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-toggle="modal"
                                                data-bs-target="#addLectureModal">Quay lại</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!--Bài kiểm tra-->
            <div class="tab-pane fade" id="exam" role="tabpanel">
                <div class="card">
                    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Danh sách bài kiểm tra</h5>
                        <button class="btn btn-light btn-sm">
                            <i class="fas fa-plus-circle me-2"></i>Tạo bài kiểm tra
                        </button>
                    </div>
                    <div class="card-body">
                        <!-- Danh sách bài kiểm tra -->
                        <div class="row row-cols-1 row-cols-md-2 g-3 mt-2">
                            <!-- Bài kiểm tra 1 -->
                            <div class="col">
                                <div class="card shadow-sm h-100">
                                    <div class="card-body">
                                        <h5 class="card-title">Bài kiểm tra chương 1</h5>
                                        <p class="card-text mb-1"><i class="bi bi-calendar-check"></i> Ngày đăng:
                                            01/06/2025</p>
                                        <p class="card-text"><i class="bi bi-clock"></i> Hạn chót: 05/06/2025</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Bài kiểm tra 2 -->
                            <div class="col">
                                <div class="card shadow-sm h-100">
                                    <div class="card-body">
                                        <h5 class="card-title">Bài kiểm tra chương 2</h5>
                                        <p class="card-text mb-1"><i class="bi bi-calendar-check"></i> Ngày đăng:
                                            03/06/2025</p>
                                        <p class="card-text"><i class="bi bi-clock"></i> Hạn chót: 07/06/2025</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Các bài kiểm tra khác -->
                        </div>
                    </div>
                </div>
            </div>

            <!--Thành viên lớp-->
            <div class="tab-pane fade" id="member" role="tabpanel">
                <div class="card">
                    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Danh sách thành viên</h5>
                        <button class="btn btn-light btn-sm" id="member-add-btn" data-bs-toggle="modal"
                            data-bs-target="#addMemberModal">
                            <i class="fas fa-plus-circle me-2"></i>Thêm vào lớp
                        </button>

                        <div class="modal fade" id="addMemberModal" tabindex="-1" aria-labelledby="addMemberModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary text-white">
                                        <h5 class="modal-title" id="addMemberModalLabel">Thêm thành viên vào lớp</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label for="studentSearch" class="text-dark form-label">Tìm kiếm sinh
                                                viên:</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="studentSearch"
                                                    placeholder="Nhập họ tên hoặc email để tìm kiếm">
                                                <button class="btn btn-outline-secondary" type="button"
                                                    id="searchStudentBtn">
                                                    <i class="fas fa-search"></i>
                                                </button>
                                            </div>
                                        </div>

                                        <div class="alert alert-danger" id="noStudentsFoundAlert" role="alert"
                                            style="display: none;">
                                            Không tìm thấy sinh viên nào phù hợp.
                                        </div>

                                        <div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
                                            <table class="table table-hover">
                                                <thead>
                                                    <tr>
                                                        <th scope="col" style="width: 50px;">Chọn</th>
                                                        <th scope="col">Họ và tên</th>
                                                        <th scope="col">Email</th>
                                                        <th scope="col">Số điện thoại</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="studentListBody">
                                                    <tr>
                                                        <td colspan="4" class="text-center">Nhập thông tin để tìm kiếm
                                                            sinh viên.</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Đóng</button>
                                        <button type="button" class="btn btn-primary" id="addSelectedMembersBtn">Thêm
                                            vào lớp</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <h6 class="mb-3">Yêu cầu vào lớp</h6>
                        <div class="list-group mb-4">
                            @forelse($yeuCau as $yeuCauItem)
                                <div class="list-group-item d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center">
                                        <img src="{{ $yeuCauItem->sinh_vien->hinh_anh ?? 'https://picsum.photos/40/40' }}"
                                            alt="Avatar" class="border border-secondary rounded-circle me-2"
                                            width="40" height="40">
                                        <div>
                                            <strong>{{ $yeuCauItem->sinh_vien->ho_ten }}</strong><br>
                                            <small>{{ $yeuCauItem->sinh_vien->email }}</small>
                                        </div>
                                    </div>
                                    <div>
                                        <button class="btn btn-sm btn-success me-1" title="Chấp nhận">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger" title="Từ chối">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                            @empty
                                <div class="text-muted">Không có yêu cầu nào.</div>
                            @endforelse
                        </div>


                        <!-- Phần danh sách thành viên -->
                        <h6 class="mb-3">Thành viên trong lớp</h6>
                        <div class="list-group">
                            @forelse($thanhVien as $tv)
                                <div class="list-group-item d-flex align-items-center">
                                    <img src="{{ $tv->sinh_vien->hinh_anh ?? 'https://picsum.photos/40/40' }}"
                                        alt="Avatar" class="border border-secondary rounded-circle me-2" width="40"
                                        height="40">
                                    <div>
                                        <strong>{{ $tv->sinh_vien->ho_ten }}</strong><br>
                                        <small>{{ $tv->sinh_vien->email }}</small>
                                    </div>
                                    <div class="flex-grow-1 text-end">
                                        <div class="dropdown">
                                            <button class="btn btn-transparent dropdown-toggle remove-arrow-down"
                                                type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="#">Xem</a></li>
                                                <li><a class="dropdown-item" href="#">Chỉnh sửa</a></li>
                                                <li><a class="dropdown-item" href="#">Xóa</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-muted">Chưa có thành viên nào trong lớp.</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Modal -->
        <div class="modal fade" id="updateClassModal" tabindex="-1" aria-labelledby="updateClassModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header bg-warning text-white">
                        <h1 class="modal-title fs-5" id="updateClassModalLabel">Chỉnh sửa lớp học</h1>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="" class="form-label">Tên lớp học</label>
                                <input type="text" name="" class="form-control" id="">
                            </div>
                            <div class="mb-3">
                                <label for="" class="form-label">Học phần</label>
                                <select class="form-select" aria-label="">
                                    <option selected>-- Chọn học phần --</option>
                                    <option value="1">Cơ Sở Dữ Liệu</option>
                                    <option value="2">Mạng Máy Tính</option>
                                    <option value="3">Toán Rời Rạc</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="" class="form-label">Mô tả ngắn (100 từ)</label>
                                <textarea name="" id="" class="form-control"></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="imageUpload" class="form-label">Hình ảnh</label>
                                <input class="form-control" type="file" id="imageUpload" accept="image/*">
                                <div id="imagePreviewContainer" class="mt-3 position-relative d-inline-block">
                                    <img id="imagePreview" src="#" alt="Ảnh xem trước" class="img-thumbnail"
                                        style="display: none; max-width: 200px; max-height: 200px;">
                                    <span id="removeImageBtn" class="close-btn" style="display: none;">&times;</span>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        <button type="button" class="btn btn-primary">Lưu thay đổi</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('modules/lophoc/css/chi-tiet-lop-hoc.css') }}">
@endsection

@section('scripts')
    <script src="{{ asset('modules/lophoc/js/chi-tiet-lop-hoc.js') }}"></script>
@endsection
