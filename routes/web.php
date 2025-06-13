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

    // Bài giảng
    Route::get('/tai-lieu/bai-giang/chinh-sua-bai-giang/{id}', 'BaiGiangController@chinhSua')->name('bai-giang.chinh-sua');
    Route::get('/tai-lieu/danh-sach-bai-giang/{id}', 'TaiLieuController@chiTiet')->name('muc-bai-giang.chi-tiet');

    Route::get('/tai-lieu', 'TaiLieuController@danhSachTheoGiangVien')->name('danhsachtailieu');
    Route::get('/bai-tap/lam-bai', 'BaiTapController@lamBai')->name('lambai');
    Route::post('/bai-tap', 'BaiTapController@themBaiTap')->name('bai_tap.them');
    Route::get('/bai-giang/{id}/bai-tap', 'BaiTapController@danhSachBaiTap')->name('bai-tap.by-bai-giang');
    Route::get('lop-hoc','LopHocController@lopHocCuaToi')->name('lop-hoc.lop-hoc-cua-toi');
    Route::get('/lop-hoc/{slug}', 'LopHocController@chiTietLopHoc')->name('lop-hoc.chi-tiet');

});


// Các route cho giảng viên
Route::group(['middleware' => ['auth.custom', 'vai_tro:giang-vien']], function () {

    // Mục bài giảng (Nam)
    Route::get('/muc-bai-giang', 'MucBaiGiangController@giaoDienQuanLy')->name('muc-bai-giang.index');
    Route::get('/muc-bai-giang/{id}/chi-tiet', 'MucBaiGiangController@chiTiet')->name('muc-bai-giang.detail');

    // Bài giảng (Nam)
    Route::get('/muc-bai-giang/{id}/bai-giang/them', 'BaiGiangController@giaoDienThem')->name('bai-giang.create');
    Route::post('/bai-giang/them', 'BaiGiangController@them')->name('bai-giang.store');
    Route::get('/bai-giang/{id}/chinh-sua', 'BaiGiangController@giaoDienChinhSua')->name('bai-giang.edit');
    Route::put('/bai-giang/{id}/chinh-sua', 'BaiGiangController@chinhSua_nam')->name('bai-giang.update');
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
