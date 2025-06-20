@extends('layouts.app')

@section('title', 'Lớp học của tôi')

@section('content')
    <!-- Main Content -->
    <div class="col bg-light p-4 overflow-auto custom-scrollbar">
        <h2 class="mb-3">Lớp học của tôi</h2>


        <div class="class-grid">
            @foreach ($dsLopHoc as $lop)
                <div class="class-card rounded">
                    <a href="{{ route('lop-hoc.detail', ['slug' => $lop->slug]) }}" class="class-img">
                        <img src="https://picsum.photos/id/1/1000/600" class="img-fluid rounded-top" alt="">
                    </a>
                    <div class="p-3">
                        <a href="{{ route('lop-hoc.detail', ['slug' => $lop->slug]) }}"
                            class="text-dark class-name">{{ $lop->ten }}</a>
                        <p class="mb-1"><b>Học phần: </b>{{ $lop->hoc_phan->ten }}</p>
                        <p class="mb-1"><b>Giảng viên: </b>{{ $lop->giang_vien->ho_ten }}</p>
                        <small class="text-secondary fst-italic">{{ $lop->mo_ta_ngan }}</small>
                        <div class="class-action-btn">
                            <div class="dropdown">
                                <button class="btn btn-transparent dropdown-toggle remove-arrow-down" type="button"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item"
                                            href="{{ route('lop-hoc.detail', ['slug' => $lop->slug]) }}">Xem lớp học</a>
                                    </li>
                                    <li><button class="dropdown-item class-update-btn" type="button">Chỉnh sửa lớp
                                            học</button>
                                    </li>
                                    <li><button class="dropdown-item class-delete-btn" type="button">Xóa lớp học</button>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
            <!-- Thêm các lớp khác nếu cần -->
        </div>

        <nav aria-label="" class="mt-4">
            <ul class="pagination justify-content-center">
                <li class="page-item">
                    <a class="page-link" href="#" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">3</a></li>
                <li class="page-item">
                    <a class="page-link" href="#" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            </ul>
        </nav>

        <button type="button" class="class-add-btn btn btn-primary rounded-circle" data-bs-toggle="modal"
            data-bs-target="#exampleModal">
            <i class="fas fa-plus"></i>
        </button>

        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Tạo mới lớp học</h1>
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
                        <button type="button" class="btn btn-primary">Tạo mới</button>
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
    <link rel="stylesheet" href="{{ asset('modules/lop-hoc/css/lop-hoc-cua-toi.css') }}">
@endsection

@section('scripts')
    <script src="{{ asset('modules/lop-hoc/js/lop-hoc-cua-toi.js') }}"></script>
@endsection
