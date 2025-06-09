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


// Các route phía client
Route::group(['middleware' => ['auth.custom', 'vai_tro:giang-vien+sinh-vien']], function () {
    Route::get('/', 'HomeController@home')->name('home');
    Route::get('/trang-chu', 'HomeController@home');
});


// Các route phía admin
Route::group(['middleware' => ['auth.custom', 'vai_tro:admin']], function () {
    Route::get('/admin', 'HomeController@dashboard')->name('dashboard');
    Route::get('/admin/dashboard', 'HomeController@dashboard');
});
