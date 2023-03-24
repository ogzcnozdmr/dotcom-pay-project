<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('start');
})->name('start');

/**
 * Seller
 */
Route::name('seller.')->group(function () {
    Route::get('/seller', [\App\Http\Controllers\SellerController::class, 'start'])->name('start');
    Route::get('/seller/add', [\App\Http\Controllers\SellerController::class, 'add'])->name('add');
    Route::get('/seller/update', [\App\Http\Controllers\SellerController::class, 'update'])->name('update');
    Route::get('/seller/list', [\App\Http\Controllers\SellerController::class, 'list'])->name('list');
    Route::get('/seller/pay', [\App\Http\Controllers\SellerController::class, 'pay'])->name('pay');
});

/**
 * Bank
 */
Route::name('bank.')->group(function () {
    Route::get('/bank', [\App\Http\Controllers\BankController::class, 'start'])->name('start');
});

/**
 * Bank
 */
Route::name('pay.')->group(function () {
    Route::get('/pay', [\App\Http\Controllers\PayController::class, 'start'])->name('start');
});

/**
 * Profile
 */
Route::name('profile.')->group(function () {
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'start'])->name('start');
    Route::get('/profile/pay', [\App\Http\Controllers\ProfileController::class, 'pay'])->name('pay');
});

/**
 * News
 */
Route::name('news.')->group(function () {
    Route::get('/news', [\App\Http\Controllers\NewsController::class, 'start'])->name('start');
    Route::get('/news/add', [\App\Http\Controllers\NewsController::class, 'add'])->name('add');
    Route::get('/news/update', [\App\Http\Controllers\NewsController::class, 'update'])->name('update');
});

/**
 * Authority
 */
Route::name('authority.')->group(function () {
    Route::get('/authority', [\App\Http\Controllers\AuthorityController::class, 'start'])->name('start');
});

/**
 * Installment
 */
Route::name('installment.')->group(function () {
    Route::get('/installment', [\App\Http\Controllers\InstallmentController::class, 'start'])->name('start');
});

/**
 * Login
 */
Route::name('login.')->group(function () {
    Route::get('/login', [\App\Http\Controllers\LoginController::class, 'start'])->name('start')->middleware('site.login');
    Route::post('/login/login', [\App\Http\Controllers\LoginController::class, 'login'])->name('login')->middleware('site.login');
});

/**
 * Logout
 */
Route::name('logout.')->group(function () {
    Route::get('/logout', [\App\Http\Controllers\LogoutController::class, 'start'])->name('start')->middleware('site.logout');
});
