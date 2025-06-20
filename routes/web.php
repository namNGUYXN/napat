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
    Route::get('/storage/files/{id_nguoi_dung}/thumbs/{ten_file}', 'SecureFileController@download')->name('secure.file');
    Route::get('/storage/files/{id_nguoi_dung}/{ten_file}', 'SecureFileController@download')->name('secure.file');
    Route::get('/storage/photos/{id_nguoi_dung}/thumbs/{ten_anh}', 'SecureFileController@image')->name('secure.photo');
    Route::get('/storage/photos/{id_nguoi_dung}/{ten_anh}', 'SecureFileController@image')->name('secure.photo');
});


// Các route phía client
Route::group(['middleware' => ['auth.custom', 'vai_tro:giang-vien+sinh-vien']], function () {

    // Trang chủ
    Route::get('/', 'HomeController@home')->name('home');
    Route::get('/trang-chu', 'HomeController@home');

    Route::get('/bai-tap/lam-bai', 'BaiTapController@lamBai')->name('lambai');
    Route::post('/bai-tap', 'BaiTapController@themBaiTap')->name('bai_tap.them');
    Route::get('/bai-giang/{id}/bai-tap', 'BaiTapController@danhSachBaiTap')->name('bai-tap.by-bai-giang');
    Route::get('lop-hoc','LopHocController@lopHocCuaToi')->name('lop-hoc.lop-hoc-cua-toi');
    Route::get('/lop-hoc/{slug}', 'LopHocController@chiTiet')->name('lop-hoc.detail');

    // Lớp học
    // -- Chương
    Route::post('/hoc-phan/{id}/chuong/list', 'HocPhanController@layListChuong');
    // -- Bài giảng
    Route::post('/lop-hoc/{id}/bai-giang/list', 'LopHocController@layListBaiGiangTrongLop');
    Route::post('/lop-hoc/{idLopHoc}/chuong/{idChuong}/bai-giang/gan', 'LopHocController@ganBaiGiang');
    Route::post('/lop-hoc/{idLopHoc}/chuong/{idChuong}/bai-giang/list', 'LopHocController@layListBaiGiangTheoChuongTrongLop');
    Route::delete('/lop-hoc/{idLopHoc}/chuong/{idChuong}/bai-giang/{id}/go', 'LopHocController@goBaiGiang');
    Route::get('/bai-giang/chi-tiet', 'BaiGiangController@chiTietBaiGiang')->name('bai-giang.chi-tiet');

    Route::get('/hoc-phan/{id}/lop-hoc', 'LopHocController@lopHocTheoHocPhan')->name('lop-hoc.theo-hoc-phan');

    //Tài khoản
    Route::get('/tai-khoan/chi-tiet', 'NguoiDungController@chiTiet')->name('tai-khoan.chi-tiet');  
    Route::post('/tai-khoan/doi-mat-khau', 'NguoiDungController@doiMatKhau')->name('tai-khoan.doi-mat-khau');
});


// Các route cho giảng viên
Route::group(['middleware' => ['auth.custom', 'vai_tro:giang-vien']], function () {

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
    Route::get('/chuong/{id}/chinh-sua', 'ChuongController@giaoDienChinhSua')->name('chuong.edit');
    Route::put('/chuong/{id}/chinh-sua', 'ChuongController@chinhSua')->name('chuong.update');
    Route::delete('chuong/{id}/xoa', 'ChuongController@xoa')->name('chuong.delete');

    // Bài
    Route::post('/chuong/{id}/bai/list', 'BaiController@layListTheoChuong')->name('bai.list');
    Route::get('/chuong/{id}/bai/them', 'BaiController@giaoDienThem')->name('bai.create');
    Route::post('/chuong{id}/bai/them', 'BaiController@them')->name('bai.store');
    Route::get('/bai/{id}/chinh-sua', 'BaiController@giaoDienChinhSua')->name('bai.edit');
    Route::put('/bai/{id}/chinh-sua', 'BaiController@chinhSua')->name('bai.update');
    Route::post('/bai/{id}/chi-tiet', 'BaiController@chiTiet')->name('bai.detail');
    // Route::delete('/bai-giang/{id}/xoa', 'BaiGiangController@xoa')->name('bai-giang.delete');

    //Thành viên lớp
    Route::post('/thanh-vien-lop/{id}/chap-nhan', 'ThanhVienLopController@chapNhan');
    Route::post('/thanh-vien-lop/{id}/tu-choi', 'ThanhVienLopController@tuChoi');
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
    Route::delete('/admin/menu/{id}', 'MenuController@xoa')->name('menu.delete');
});
