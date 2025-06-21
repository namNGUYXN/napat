@extends('layouts.app')

@section('content')
  <div class="col bg-light px-4 pt-4 overflow-auto custom-scrollbar">
    <h2 class="mb-4">Chỉnh sửa chương</h2>

    <a href="{{ route('bai-giang.detail', $chuong->bai_giang->id) }}" class="btn btn-outline-secondary mb-4">
      <i class="fas fa-arrow-alt-circle-left me-2"></i>Danh sách chương
    </a>

    @if ($errors->any())
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <ul class="list-unstyled m-0">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

    @if (session('message'))
      <div class="alert alert-{{ session('status') }} alert-dismissible fade show" role="alert">
        {{ session('message') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

    <div class="row">
      <div class="col-lg-4 mb-4">
        <div class="card h-100 shadow-sm p-3">
          <form action="{{ route('chuong.update', $chuong->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
              <label for="" class="form-label">
                Tiêu đề chương
                <span class="text-muted">(100 từ)</span>
                <abbr class="text-danger" title="Bắt buộc">*</abbr>
              </label>
              <input type="text" name="tieu_de" class="form-control" id="" required maxlength="100"
                value="{{ $chuong->tieu_de }}">
            </div>
            <div class="mb-3">
              <label for="" class="form-label">Mô tả ngắn <span class="text-muted">(255 từ)</span></label>
              <textarea name="mo_ta_ngan" id="" class="form-control" rows="6" maxlength="255">{{ $chuong->mo_ta_ngan }}</textarea>
            </div>
            <div class="text-end">
              <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
            </div>
          </form>
        </div>
      </div>

      <div class="col-lg-8 mb-4">
        <div class="card shadow-sm">
          <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Danh sách bài trong chương</h5>
            <a href="{{ route('bai.create', $chuong->id) }}" class="btn btn-light btn-sm">
              <i class="fas fa-plus-circle me-2"></i>Thêm bài
            </a>
            {{-- <a href="{{ route('bai-giang.create', $baiGiang->id) }}" class="btn btn-light btn-sm">
              <i class="fas fa-plus-circle me-2"></i>Thêm chương
            </a> --}}
          </div>
          <div class="card-body">
            <form action="" method="GET">
              <div class="input-group mb-3">
                <input type="text" class="form-control" name="search" value="{{ request()->input('search') }}"
                  placeholder="Tìm kiếm bài theo tên..." id="">
                <button class="btn btn-outline-secondary">
                  <i class="fas fa-search"></i> </button>
              </div>
            </form>

            <div class="table-responsive custom-scrollbar">
              <table class="table table-hover table-striped" style="min-width: 600px;">
                <thead>
                  <tr>
                    <th scope="col">STT</th>
                    <th scope="col">Tiêu đề</th>
                    <th scope="col">Ngày tạo</th>
                    <th scope="col" class="text-center">Thao tác</th>
                  </tr>
                </thead>
                <tbody>
                  {{-- @php
                    $page = request()->query('page') ?: 1;
                    $start = ($page - 1) * $numPerPage;
                  @endphp --}}
                  @foreach ($chuong->list_bai as $bai)
                    <tr>
                      <th scope="row">1</th>
                      <td>{{ $bai->tieu_de }}</td>
                      <td>{{ $bai->ngay_tao }}</td>
                      <td class="text-center">
                        <button class="btn btn-info btn-sm me-1 btn-detail-bai-giang" data-url="">
                          <i class="fas fa-eye"></i>
                        </button>
                        {{-- <button class="btn btn-info btn-sm me-1 btn-detail-bai-giang"
                          data-url="{{ route('bai-giang.detail', $chuong->id) }}">
                          <i class="fas fa-eye"></i>
                        </button> --}}
                        <a href="{{ route('bai.edit', $bai->id) }}" class="btn btn-warning btn-sm me-1">
                          <i class="fas fa-edit"></i>
                        </a>
                        {{-- <a href="{{ route('bai-giang.edit', $chuong->id) }}"
                          class="btn btn-warning btn-sm me-1 edit-lesson-btn">
                          <i class="fas fa-edit"></i>
                        </a> --}}
                        <button class="btn btn-danger btn-sm btn-xoa-bai-giang" data-url="">
                          <i class="fas fa-trash-alt"></i>
                        </button>
                        {{-- <button class="btn btn-danger btn-sm btn-xoa-bai-giang"
                          data-url="{{ route('bai-giang.delete', $chuong->id) }}">
                          <i class="fas fa-trash-alt"></i>
                        </button> --}}
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>

            {{-- Dấu : báo hiệu cho blade đây là biểu thức php --}}
            {{-- <x-pagination :paginator="$listChuong" base-url="{{ route('bai-giang.detail', $baiGiang->id) }}" /> --}}

          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="modal-chi-tiet-bai" tabindex="-1" aria-labelledby="" aria-hidden="true" data-bs-focus="false">
      <div class="modal-dialog modal-xl modal-dialog-scrollable modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header bg-info text-white">
            <h5 class="modal-title">Chi tiết chương</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            {{-- <p><strong>Tên bài giảng:</strong> <span id="tieu-de-bai-giang"></span></p>
            <hr>
            <h6>Nội dung:</h6>
            <div id="noi-dung-bai-giang"></div> --}}
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
            <button class="btn btn-primary" data-bs-target="#modal-chi-tiet-bai-giang" data-bs-toggle="modal">Open
              second modal</button>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('styles')
  <link rel="stylesheet" href="{{ asset('modules/bai-giang/css/chi-tiet.css') }}">
@endsection
