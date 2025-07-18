<?php

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Các route về auth
Route::get('/dang-nhap', 'AuthController@giaoDienDangNhap')->name('dang-nhap');
Route::get('/dang-nhap-lan-dau', 'AuthController@giaoDienDangNhapLanDau')->name('dang-nhap-lan-dau');
Route::post('/doi-mat-khau-lan-dau', 'AuthController@doiMatKhauLanDau')->name('doi-mat-khau-lan-dau');
Route::post('/dang-nhap', 'AuthController@dangNhap');
Route::post('/dang-xuat', 'AuthController@dangXuat')->name('dang-xuat');
Route::get('/quen-mat-khau', 'AuthController@giaoDienQuenMatKhau')->name('quen-mat-khau');
Route::post('/gui-lien-ket-dat-lai-mat-khau', 'AuthController@guiLienKetDatLaiMatKhau')->name('lien-ket-dlmk');
Route::get('/dat-lai-mat-khau/{token}', 'AuthController@giaoDienDatLaiMatKhau')->name('dat-lai-mat-khau');
Route::post('/dat-lai-mat-khau', 'AuthController@datLaiMatKhau')->name('dat-lai-mat-khau');

// Các route về laravel file manager
Route::group([
    'prefix' => 'laravel-filemanager',
    'middleware' => ['web', 'auth.custom', 'vai_tro:giang-vien+admin']
], function () {
    \UniSharp\LaravelFilemanager\Lfm::routes();
});

Route::group(['middleware' => ['auth.custom']], function () {
    Route::get('/storage/files/shares/thumbs/{ten_file}', 'SecureFileController@publicFile');
    Route::get('/storage/files/shares/{ten_file}', 'SecureFileController@publicFile');
    Route::get('/storage/photos/shares/thumbs/{ten_anh}', 'SecureFileController@publicImage');
    Route::get('/storage/photos/shares/{ten_anh}', 'SecureFileController@publicImage');

    Route::get('/storage/files/{id_nguoi_dung}/thumbs/{ten_file}', 'SecureFileController@privateFile');
    Route::get('/storage/files/{id_nguoi_dung}/{ten_file}', 'SecureFileController@privateFile');
    Route::get('/storage/photos/{id_nguoi_dung}/thumbs/{ten_anh}', 'SecureFileController@privateImage');
    Route::get('/storage/photos/{id_nguoi_dung}/{ten_anh}', 'SecureFileController@privateImage');

    Route::get('/storage/photos/{id_nguoi_dung}/{ten_thu_muc}/{ten_anh}', 'SecureFileController@privateImageWord');
});

// Các route phía client
Route::group(['middleware' => ['auth.custom', 'vai_tro:giang-vien+sinh-vien']], function () {

    // Trang chủ
    Route::get('/', 'HomeController@home')->name('home');
    Route::get('/trang-chu', 'HomeController@home');

    Route::post('/bai-tap', 'BaiTapController@themBaiTap')->name('bai_tap.them');
    Route::get('/bai-giang/{id}/bai-tap', 'BaiTapController@danhSachBaiTap')->name('bai-tap.by-bai-giang');

    // Lớp học phần
    Route::get('/khoa/{slug}/lop-hoc-phan', 'LopHocPhanController@lopHocPhanTheoKhoa')->name('lop-hoc.index');
    Route::get('/lop-hoc-phan-cua-toi', 'LopHocPhanController@lopHocCuaToi')->name('lop-hoc.lop-hoc-cua-toi');
    Route::get('/lop-hoc-phan/{slug}', 'LopHocPhanController@chiTiet')->name('lop-hoc.detail');
    // -- Bản tin
    Route::post('/lop-hoc-phan/{id}/ban-tin/them', 'BanTinController@them')->name('ban-tin.store');
    Route::post('/ban-tin/{id}/chi-tiet', 'BanTinController@chiTiet')->name('ban-tin.detail');
    Route::put('/ban-tin/{id}/chinh-sua', 'BanTinController@chinhSua')->name('ban-tin.update');
    Route::delete('/ban-tin/{id}/xoa', 'BanTinController@xoa')->name('ban-tin.delete');
    Route::post('/lop-hoc-phan/{idLopHocPhan}/ban-tin/{idBanTin}/phanHoi', 'BanTinController@phanHoi')->name('ban-tin.reply');
    Route::put('/ban-tin/{id}/chinh-sua-phan-hoi', 'BanTinController@chinhSuaPhanHoi')->name('phan-hoi.update');
    // -- Bài
    Route::post('/lop-hoc-phan/{slug}/bai/cong-khai', 'LopHocPhanController@congKhaiBaiTrongLop')->name('bai-trong-lop.public');
    Route::get('/lop-hoc-phan/{id}/bai/{slug}', 'LopHocPhanController@xemNoiDungBai')->name('bai-trong-lop.detail');
    // -- Bình luận
    Route::post('/lop-hoc-phan/{lop}/bai/{bai}/binh-luan/them', 'BinhLuanController@them')->name('binh-luan.store');
    Route::post('/lop-hoc-phan/{lop}/bai/{bai}/binh-luan/{binhluan}/phan-hoi', 'BinhLuanController@phanHoi')->name('binh-luan.phan-hoi');
    Route::put('/binh-luan/{binhluan}/chinh-sua', 'BinhLuanController@chinhSua')->name('binh-luan.update');
    Route::delete('/binh-luan/{binhluan}/xoa', 'BinhLuanController@xoa')->name('binh-luan.delete');

    //Bài tập
    Route::get('lop-hoc-phan/{lop}/bai-tap/{id}/chi-tiet', 'BaiTapController@layChiTiet');
    Route::get('/lop-hoc-phan/{id_lop_hoc_phan}/lam-bai/{id}', 'BaiTapController@lamBai')
        ->name('bai-tap.lam-bai');
    Route::post('/bai-tap/nop-bai', 'BaiTapController@nopBai')->name('bai-tap.nop-bai');

    //Bài kiểm tra
    Route::get('/bai-kiem-tra/{idLopHoc}', 'BaiKiemTraController@danhSachBaiKiemTra');
    Route::get('/lam-bai/{id}', 'BaiKiemTraController@lamBai')->name('lambai');
    Route::post('/bai-kiem-tra/nop-bai', 'BaiKiemTraController@nopBai')->name('bai-kiem-tra.nop-bai');
    Route::get('/bai-kiem-tra/{id}/chi-tiet', 'BaiKiemTraController@layChiTiet');



    //Lấy giờ hệ thống
    Route::get('/server-time', function () {
        return response()->json(['now' => now()->format('Y-m-d H:i:s')]);
    });
});

// Các route cho giảng viên
Route::group(['middleware' => ['auth.custom', 'vai_tro:giang-vien']], function () {

    // Lớp học phần
    Route::post('/lop-hoc-phan/them', 'LopHocPhanController@them')->name('lop-hoc.store');
    Route::post('/lop-hoc-phan/{id}/modal-chi-tiet', 'LopHocPhanController@modalChiTiet')->name('lop-hoc-phan.detail-modal');
    Route::put('/lop-hoc-phan/{id}/modal-chinh-sua', 'LopHocPhanController@modalChinhSua')->name('lop-hoc-phan.update-modal');
    Route::put('/lop-hoc-phan/{id}/chinh-sua', 'LopHocPhanController@chinhSua')->name('lop-hoc-phan.update');
    Route::delete('/lop-hoc-phan/{id}/xoa', 'LopHocPhanController@xoa')->name('lop-hoc-phan.delete');
    Route::delete('/lop-hoc-phan/{idLHP}/sinh-vien/{idND}', 'LopHocPhanController@xoaKhoiLop')->name('lop-hoc-phan.remove-from');


    // Bài giảng
    Route::get('/bai-giang', 'BaiGiangController@giaoDienQuanLy')->name('bai-giang.index');
    Route::get('/bai-giang/{id}/chi-tiet', 'BaiGiangController@chiTiet')->name('bai-giang.detail');
    Route::post('/bai-giang/them', 'BaiGiangController@them')->name('bai-giang.store');
    Route::post('/bai-giang/{id}/modal-chi-tiet', 'BaiGiangController@modalChiTiet')->name('bai-giang.detail-modal');
    Route::put('/bai-giang/{id}/modal-chinh-sua', 'BaiGiangController@modalChinhSua')->name('bai-giang.update-modal');
    Route::put('/bai-giang/{id}/chinh-sua', 'BaiGiangController@chinhSua')->name('bai-giang.update');
    Route::delete('/bai-giang/{id}/xoa', 'BaiGiangController@xoa')->name('bai-giang.delete');

    // Chương
    Route::post('/bai-giang/{id}/chuong/them', 'ChuongController@them')->name('chuong.store');
    Route::post('/chuong/{id}/modal-chinh-sua', 'ChuongController@modalChinhSua')->name('chuong.edit');
    Route::put('/chuong/{id}/chinh-sua', 'ChuongController@chinhSua')->name('chuong.update');
    Route::put('/bai-giang/{id}/chuong/cap-nhat-thu-tu', 'ChuongController@capNhatThuTu')->name('thu-tu-chuong.update');
    Route::delete('/chuong/{id}/xoa', 'ChuongController@xoa')->name('chuong.delete');
    Route::delete('/chuong/xoa-hang-loat', 'ChuongController@xoaHangLoat')->name('chuong.quick-delete');

    // Bài
    Route::get('/chuong/{id}/bai', 'BaiController@giaoDienQuanLy')->name('bai.index');
    Route::get('/chuong/{id}/bai/them', 'BaiController@giaoDienThem')->name('bai.create');
    Route::post('/chuong{id}/bai/them', 'BaiController@them')->name('bai.store');
    Route::get('/bai/{id}/chinh-sua', 'BaiController@giaoDienChinhSua')->name('bai.edit');
    Route::put('/bai/{id}/chinh-sua', 'BaiController@chinhSua')->name('bai.update');
    Route::put('/chuong/{id}/bai/cap-nhat-thu-tu', 'BaiController@capNhatThuTu')->name('thu-tu-bai.update');
    Route::post('/bai/{id}/chi-tiet', 'BaiController@chiTiet')->name('bai.detail');
    Route::delete('/bai/{id}/xoa', 'BaiController@xoa')->name('bai.delete');
    Route::post('/upload-image', 'BaiController@privateUploadImage')->name('upload.image');
    Route::delete('/bai/xoa-hang-loat', 'BaiController@xoaHangLoat')->name('bai.quick-delete');

    //Thành viên lớp
    Route::post('/thanh-vien-lop/{id}/chap-nhan', 'LopHocPhanController@chapNhan');
    Route::post('/thanh-vien-lop/{id}/tu-choi', 'LopHocPhanController@tuChoi');
    Route::post('/thanh-vien/them-danh-sach', 'LopHocPhanController@themDanhSach')->name('thanh-vien-lop.import');

    //Bài kiểm tra
    Route::post('/bai-kiem-tra', 'BaiKiemTraController@themBaiKiemTra')->name('bai_kiem_tra.them');
    Route::put('/bai-kiem-tra', 'BaiKiemTraController@capNhatBaiKiemTra')->name('bai_kiem_tra.cap-nhat');
    Route::post('/bai-kiem-tra/{id}/cong-khai', 'BaiKiemTraController@congKhai');

    //Tiến độ
    Route::get('/tien-do/bai/{id}', 'BaiController@layChiTietSinhVien');

    Route::post('/bai-trong-lop/{id}/cap-nhat-hoan-thanh', 'LopHocPhanController@capNhatHoanThanh')
        ->name('bai-trong-lop.cap-nhat-hoan-thanh');
});

// Các route cho sinh viên
Route::group(['middleware' => ['auth.custom', 'vai_tro:sinh-vien']], function () {
    // Đăng ký lớp
    Route::post('/lop-hoc-phan/{id}/dang-ky', 'LopHocPhanController@dangKy')->name('lop-hoc-phan.register');
    // Rời khỏi lớp
    Route::delete('/lop-hoc-phan/{id}/roi-khoi', 'LopHocPhanController@roiKhoi')->name('lop-hoc-phan.leave');
    Route::post('/danh-dau-hoan-thanh/{idBaiTrongLop}', 'BaiController@danhDauHoanThanh')
        ->name('tien-do-hoc-tap.danh-dau-hoan-thanh');
});

// Các route phía admin
Route::group(['middleware' => ['auth.custom', 'vai_tro:admin']], function () {

    // Dashboard
    Route::get('/admin', 'HomeController@dashboard')->name('dashboard');
    Route::get('/admin/dashboard', 'HomeController@dashboard');

    // Menu
    Route::get('/admin/menu', 'MenuController@giaoDienQuanLy')->name('menu.index');
    Route::get('/admin/menu/them', 'MenuController@giaoDienThem')->name('menu.create');
    Route::post('/admin/menu/them', 'MenuController@them')->name('menu.store');
    Route::get('/admin/menu/{id}/chinh-sua', 'MenuController@giaoDienChinhSua')->name('menu.edit');
    Route::put('/admin/menu/{id}', 'MenuController@chinhSua')->name('menu.update');
    Route::post('/admin/menu/cap-nhat-thu-tu', 'MenuController@capNhatThuTu')->name('thu-tu-menu.update');
    Route::delete('/admin/menu/{id}', 'MenuController@xoa')->name('menu.delete');
    Route::delete('/menu/xoa-hang-loat', 'MenuController@xoaHangLoat')->name('menu.quick-delete');

    // Người dùng
    Route::prefix('nguoi-dung')->group(function () {
        Route::get('/', 'NguoiDungController@danhSachNguoiDung')->name('nguoi-dung.index');
        Route::get('/them', 'NguoiDungController@hienThiFormThem')->name('nguoi-dung.them');
        Route::post('/them', 'NguoiDungController@xuLyThemNguoiDung')->name('nguoi-dung.xu-ly-them');
        Route::post('/import', 'NguoiDungController@xuLyImportExcel')->name('nguoi-dung.import');
        Route::get('/nguoi-dung/{id}/sua', 'NguoiDungController@suaNguoiDung')->name('nguoi-dung.sua');
        Route::put('/nguoi-dung/{id}', 'NguoiDungController@capNhatNguoiDung')->name('nguoi-dung.cap-nhat');
        Route::patch('/nguoi-dung/{id}/khoa-mo', 'NguoiDungController@khoaMo')->name('nguoi-dung.khoa-mo');
    });

    //Khoa
    Route::prefix('khoa')->group(function () {
        Route::get('/', 'KhoaController@danhSachKhoa')->name('khoa.index');
        Route::get('/them-moi', 'KhoaController@hienThiFormThem')->name('khoa.them');
        Route::post('/them-moi', 'KhoaController@xuLyThemKhoa')->name('khoa.luu');
        Route::post('/import', 'KhoaController@xuLyImportExcel')->name('khoa.import');
        Route::get('/cap-nhat/{id}', 'KhoaController@hienThiFormCapNhat')->name('khoa.cap-nhat');
        Route::put('/cap-nhat/{id}', 'KhoaController@capNhat')->name('khoa.chinh-sua');
        Route::delete('/xoa/{id}', 'KhoaController@xoa')->name('khoa.xoa');
    });
});

// Route quản lý tài khoản cá nhân
Route::group(['middleware' => ['auth.custom', 'vai_tro:giang-vien+sinh-vien+admin']], function () {
    //Tài khoản
    Route::get('/tai-khoan/chi-tiet', 'NguoiDungController@chiTiet')->name('tai-khoan.chi-tiet');
    Route::post('/tai-khoan/doi-mat-khau', 'NguoiDungController@doiMatKhau')->name('tai-khoan.doi-mat-khau');
    Route::put('/tai-khoan/cap-nhat-thong-tin', 'NguoiDungController@capNhatThongTinCaNhan')->name('tai-khoan.update');
});
