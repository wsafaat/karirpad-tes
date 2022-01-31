<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProdukController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;



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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/tes-1', function () {
    return view('tes1');
});

Auth::routes();

Route::middleware(['auth','superuser'])->group(function () {
    // Route::get('superuser/home', [HomeController::class, 'superuserHome'])->name('superuser.home');
    Route::get('superuser/home', [ProdukController::class, 'index'])->name('superuser.home');
    Route::get('superuser/produk/{id}/edit', [ProdukController::class, 'edit']);
    Route::post('superuser/produk/store', [ProdukController::class, 'store']);
    Route::get('superuser/produk/delete/{id}', [ProdukController::class, 'destroy']);
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


