@if ($dsLopHoc->count())
    <form action="{{ route('lop-hoc.lop-hoc-cua-toi') }}" id="form-filter" method="GET">
        <div class="row">
            <div class="col-md-3">
                <div class="mb-3">
                    <select name="sort" id="sort-select" class="form-select">
                        <option value="newest"{{ request('sort') == 'newest' ? ' selected' : '' }}>Mới nhất</option>
                        <option value="oldest"{{ request('sort') == 'oldest' ? 'selected' : '' }}>Cũ nhất</option>
                        <option value="name_asc"{{ request('sort') == 'name_asc' ? 'selected' : '' }}>Tên lớp A -> Z
                        </option>
                        <option value="name_desc"{{ request('sort') == 'name_desc' ? 'selected' : '' }}>Tên lớp Z -> A
                        </option>
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="mb-3">
                    <select name="limit" id="limit-select" class="form-select">
                        <option value="3"{{ request('limit') == 3 ? ' selected' : '' }}>Hiển thị: 3</option>
                        <option value="6"{{ request('limit') == 6 ? ' selected' : '' }}>Hiển thị: 6</option>
                        <option value="12"{{ request('limit') == 12 ? ' selected' : '' }}>Hiển thị: 12</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="input-group mb-3">
                    <input type="text" class="form-control" name="search" value="{{ request()->input('search') }}"
                        placeholder="Nhập tên hoặc mã lớp cần tìm..." id="search-input" autocomplete="off">
                    <button class="btn btn-outline-secondary">
                        <i class="fas fa-search"></i> </button>
                </div>
            </div>
        </div>
    </form>

    <div class="class-grid">
        @foreach ($dsLopHoc as $lop)
            <div class="col">
                <div class="card shadow-sm h-100 border-0 rounded-4 overflow-hidden position-relative">

                    <a href="{{ route('lop-hoc.detail', ['slug' => $lop->slug]) }}" class="d-block">
                        <img src="{{ asset('storage/' . $lop->hinh_anh) }}" class="img-fluid w-100"
                            style="height: 180px; object-fit: cover;" alt="">
                    </a>

                    <div class="card-body p-4">
                        <h5 class="card-title mb-2">
                            <a href="{{ route('lop-hoc.detail', ['slug' => $lop->slug]) }}" class="class-title-link">
                                {{ $lop->ten }}
                            </a>
                        </h5>

                        <p class="mb-2 d-flex align-items-center text-primary-emphasis fs-6">
                            <i class="bi bi-person-circle me-2 fs-5 text-primary"></i>
                            <span><strong>Giảng viên:</strong> {{ $lop->giang_vien->ho_ten }}</span>
                        </p>

                        <p class="mb-2 d-flex align-items-center text-success-emphasis fs-6">
                            <i class="bi bi-hash me-2 fs-5 text-success"></i>
                            <span><strong>Mã lớp:</strong> {{ $lop->ma }}</span>
                        </p>

                        <p class="mb-2 d-flex align-items-center text-warning-emphasis fs-6">
                            <i class="bi bi-building me-2 fs-5 text-warning"></i>
                            <span><strong>Khoa:</strong> {{ $lop->khoa->ten }}</span>
                        </p>

                        <p class="mt-3 text-secondary-emphasis small fst-italic" style="line-height: 1.4;"
                            title="{{ $lop->mo_ta_ngan }}">
                            {{ Str::of($lop->mo_ta_ngan)->limit(100) }}
                        </p>
                    </div>

                    {{-- Nút tùy chọn --}}
                    <div class="position-absolute top-0 end-0 mt-2 me-2">
                        <div class="dropdown">
                            <button class="btn btn-sm btn-light rounded-circle shadow-sm" data-bs-toggle="dropdown">
                                <i class="bi bi-three-dots-vertical"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item"
                                        href="{{ route('lop-hoc.detail', ['slug' => $lop->slug]) }}">Xem</a></li>

                                @if (session('id_nguoi_dung') == $lop->bai_giang->id_giang_vien)
                                    <li>
                                        <button class="dropdown-item btn-update-class" type="button"
                                            data-url-detail="{{ route('lop-hoc-phan.detail-modal', $lop->id) }}"
                                            data-url-update="{{ route('lop-hoc-phan.update-modal', $lop->id) }}">
                                            Chỉnh sửa
                                        </button>
                                    </li>
                                    <li>
                                        <button class="dropdown-item btn-delete-class" type="button"
                                            data-url-delete={{ route('lop-hoc-phan.delete', $lop->id) }}>
                                            Xóa
                                        </button>
                                    </li>
                                @elseif (session('vai_tro') == 'Sinh viên')
                                    @php
                                        $thanhVienLopService = app(App\Services\ThanhVienLopService::class);
                                    @endphp
                                    @if (!$thanhVienLopService->daThamGiaLopHocPhan($lop->id))
                                        <li>
                                            <button class="dropdown-item btn-register-class" type="button"
                                                data-url-register="{{ route('lop-hoc-phan.register', $lop->id) }}">
                                                Đăng ký lớp
                                            </button>
                                        </li>
                                    @endif
                                @endif
                            </ul>
                        </div>
                    </div>

                </div>
            </div>
        @endforeach
    </div>



    {{-- Dấu : báo hiệu cho blade đây là biểu thức php --}}
    <x-pagination :paginator="$dsLopHoc" base-url="{{ $route }}" />
@else
    <h4 class="text-muted fst-italic text-center px-3 mt-5">
        Không tìm thấy <span class="text-dark text-decoration-underline">{{ request('search', '') }}</span> hoặc chưa
        có
        lớp học phần
    </h4>
@endif
