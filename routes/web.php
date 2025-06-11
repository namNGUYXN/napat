<?php

use Illuminate\Support\Facades\Route;

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


// Các route phía client
Route::group(['middleware' => ['auth.custom', 'vai_tro:giang-vien+sinh-vien']], function () {
    Route::get('/', 'HomeController@home')->name('home');
    Route::get('/trang-chu', 'HomeController@home');
});


// Các route phía admin
Route::group(['middleware' => ['auth.custom', 'vai_tro:admin']], function () {

    // Dashboard
    Route::get('/admin', 'HomeController@dashboard')->name('dashboard');
    Route::get('/admin/dashboard', 'HomeController@dashboard');

    // Menu
    Route::get('/admin/menu', 'MenuController@giaoDienQuanLy')->name('list-menu');
    Route::get('/admin/menu/them', 'MenuController@giaoDienThem')->name('them-menu');
    Route::post('/admin/menu/them', 'MenuController@them');
    Route::get('/admin/menu/chinh-sua/{id}', 'MenuController@giaoDienChinhSua')->name('giao-dien-chinh-sua-menu');
    Route::post('/admin/menu/chinh-sua', 'MenuController@chinhSua')->name('chinh-sua-menu');
});
