<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArtikelController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LinkController;

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

Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::resource('link', LinkController::class);
Route::get('link/get/data', [LinkController::class, 'getLink'])->name('link.list');

Route::resource('artikel', ArtikelController::class);
Route::get('artikel/get/data', [ArtikelController::class, 'getArtikel'])->name('artikel.list');
Route::get('artikel/get/link/{id}', [ArtikelController::class, 'link'])->name('artikel.link');
Route::post('artikel/get/result/{link}', [ArtikelController::class, 'result'])->name('artikel.result');

Route::get('/detail/{tahun}/{id}', [DashboardController::class, 'detail'])->name('dashboard.detail');
Route::get('/about', [DashboardController::class, 'about'])->name('dashboard.about');