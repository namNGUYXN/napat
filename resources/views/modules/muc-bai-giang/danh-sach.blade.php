@extends('layouts.app')

@section('title', 'Danh sách tài liệu bài giảng')

@section('content')
    <!-- Main Content -->
    <div class="col bg-light p-4 overflow-auto custom-scrollbar">
        <h2 class="mb-3">Danh sách tài liệu bài giảng</h2>

        <div class="document-grid">
            @foreach ($listMucBaiGiang as $mucBaiGiang)
                <div class="document-card rounded">
                    <a href="{{ route('muc-bai-giang.detail', $mucBaiGiang->id) }}" class="document-img">
                        <img src="https://picsum.photos/id/1/1000/600" class="img-fluid rounded-top" alt="">
                    </a>
                    <div class="p-3">
                        <a href="{{ route('muc-bai-giang.detail', $mucBaiGiang->id) }}" class="text-dark document-name">{{ $mucBaiGiang->ten }}</a>
                        <p class="mb-1"><b>Số lượng bài giảng: </b>{{ $mucBaiGiang->so_bai_giang }}</p>
                        <p class="mb-1"><b>Ngày tạo: </b> {{ $mucBaiGiang->ngay_tao }}</p>
                        <small class="text-secondary fst-italic">{{ $mucBaiGiang->mo_ta_ngan }}</small>
                        <div class="document-action-btn">
                            <div class="dropdown">
                                <button class="btn btn-transparent dropdown-toggle" type="button" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="document-detail.html">Xem</a></li>
                                    <li><a class="dropdown-item" href="#">Chỉnh sửa</a></li>
                                    <li><a class="dropdown-item" href="#">Xóa</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
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

        <button type="button" class="document-add-btn btn btn-primary rounded-circle" data-bs-toggle="modal"
            data-bs-target="#exampleModal">
            <i class="fas fa-plus"></i>
        </button>

        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Tạo mới tài liệu bài giảng</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="">
                            <div class="mb-3">
                                <label for="" class="form-label">Tên tài liệu bài giảng</label>
                                <input type="text" name="" class="form-control" id="">
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

    </div>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('modules/tailieu/css/danh-sach-tai-lieu.css') }}">
@endsection
