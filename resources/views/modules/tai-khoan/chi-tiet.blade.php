@extends('layouts.app')

@section('title', 'Thông tin người dùng')

@section('content')
    <div class="col bg-light p-4">
        <div class="container bg-white p-4 rounded shadow-sm">
            <h5 class="mb-4">Thông tin tài khoản</h5>
            <div class="row">
                <!-- Ảnh đại diện -->
                <div class="col-md-4 text-center mb-3 d-flex flex-column align-items-center">
                    <!-- Khung hình đại diện -->
                    <div style="width: 200px; height: 200px;">
                        <img id="avatarPreview" src="https://via.placeholder.com/150" class="img-thumbnail w-100 h-100"
                            style="object-fit: cover;" alt="Ảnh đại diện">
                    </div>

                    <!-- Nút thay đổi ảnh dưới hình và canh giữa -->
                    <input type="file" id="avatarInput" class="d-none" accept="image/*">
                    <label for="avatarInput" class="btn btn-success btn-sm mt-3">
                        <i class="bi bi-camera"></i> Thay đổi ảnh
                    </label>
                </div>


                <!-- Thông tin cá nhân -->
                <div class="col-md-8">
                    <div class="mb-3 position-relative">
                        <label for="fullName" class="form-label">Họ tên:</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="fullName" value="{{ $nguoiDung->ho_ten }}">
                            <span class="input-group-text bg-white border-start-0">
                                <i class="bi bi-pencil-square text-muted"></i>
                            </span>
                        </div>
                    </div>

                    <div class="mb-3 position-relative">
                        <label for="email" class="form-label">Email:</label>
                        <div class="input-group">
                            <input type="email" class="form-control" id="email" value="{{ $nguoiDung->email }}">
                            <span class="input-group-text bg-white border-start-0">
                                <i class="bi bi-pencil-square text-muted"></i>
                            </span>
                        </div>
                    </div>

                    <div class="mb-3 position-relative">
                        <label for="phone" class="form-label">Số điện thoại:</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="phone" value="{{ $nguoiDung->sdt }}">
                            <span class="input-group-text bg-white border-start-0">
                                <i class="bi bi-pencil-square text-muted"></i>
                            </span>
                        </div>
                    </div>

                    <div class="mb-3 position-relative">
                        <label for="role" class="form-label">Tài khoản:</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="role" value="{{ $nguoiDung->vai_tro }}">
                        </div>
                    </div>


                    <div class="d-flex gap-2 mt-4 justify-content-center">
                        <button class="btn btn-outline-primary" data-bs-toggle="modal"
                            data-bs-target="#changePasswordModal">
                            <i class="bi bi-key"></i> Đổi mật khẩu
                        </button>
                        <button class="btn btn-success">
                            <i class="bi bi-save"></i> Lưu thông tin
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Modal Đổi mật khẩu -->
    <div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered"> <!-- ✅ Canh giữa -->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="changePasswordModalLabel">Đổi mật khẩu</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                </div>
                <!-- Modal Body -->
                <div class="modal-body">
                    <form>
                        <!-- Mật khẩu hiện tại -->
                        <div class="mb-3 position-relative">
                            <label for="currentPassword" class="form-label">Mật khẩu hiện tại</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="currentPassword">
                                <button class="btn btn-outline-secondary toggle-password" type="button"
                                    data-target="currentPassword">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            <div class="text-danger mt-2" id="currentPasswordError"></div>
                        </div>

                        <!-- Mật khẩu mới -->
                        <div class="mb-3">
                            <label for="newPassword" class="form-label">Mật khẩu mới</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="newPassword">
                                <button class="btn btn-outline-secondary toggle-password" type="button"
                                    data-target="newPassword">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Xác nhận mật khẩu -->
                        <div class="mb-3">
                            <label for="confirmPassword" class="form-label">Xác nhận mật khẩu</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="confirmPassword">
                                <button class="btn btn-outline-secondary toggle-password" type="button"
                                    data-target="confirmPassword">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            <div class="text-danger mt-2" id="confirmPasswordError"></div>
                        </div>
                    </form>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="button" class="btn btn-primary" id="btnChangePassword">Đổi</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
@endsection

@section('scripts')
    <script src="{{ asset('modules/tai-khoan/js/chi-tiet.js') }}"></script>
@endsection
