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

/**
 * Start
 */
Route::name('home.')->group(function () {
    Route::get('/', [\App\Http\Controllers\StartController::class, 'start'])->name('start');//->middleware('app.login')
    Route::get('/404', [\App\Http\Controllers\StartController::class, 'danger'])->name('danger');//->middleware('app.login')
});

/**
 * Login
 */
Route::name('login.')->group(function () {
    Route::get('/login', [\App\Http\Controllers\LoginController::class, 'start'])->name('start')->middleware('app.login');
    Route::post('/login/login', [\App\Http\Controllers\LoginController::class, 'login'])->name('login')->middleware('app.login');
});

/**
 * Logout
 */
Route::name('logout.')->group(function () {
    Route::get('/logout', [\App\Http\Controllers\LogoutController::class, 'start'])->name('start')->middleware('app.logout');
});

/**
 * Notification
 */
Route::name('notification.')->group(function () {
    Route::post('/notification/approve', [\App\Http\Controllers\NotificationsController::class, 'approve'])->name('approve');
});

/**
 * Seller
 */
Route::name('seller.')->group(function () {
    Route::get('/seller', [\App\Http\Controllers\SellerController::class, 'start'])->name('start');
    Route::get('/seller/add', [\App\Http\Controllers\SellerController::class, 'add'])->name('add');
    Route::get('/seller/update', [\App\Http\Controllers\SellerController::class, 'update'])->name('update');
    Route::get('/seller/pay', [\App\Http\Controllers\SellerController::class, 'pay'])->name('pay');
    Route::post('/seller/post/add', [\App\Http\Controllers\SellerController::class, 'postAdd'])->name('post.add');
    Route::post('/seller/post/update', [\App\Http\Controllers\SellerController::class, 'postUpdate'])->name('post.update');
    Route::post('/seller/post/list', [\App\Http\Controllers\SellerController::class, 'postList'])->name('post.list');
    Route::post('/seller/post/remove', [\App\Http\Controllers\SellerController::class, 'postRemove'])->name('post.remove');
});

/**
 * Bank
 */
Route::name('bank.')->group(function () {
    Route::get('/bank', [\App\Http\Controllers\BankController::class, 'start'])->name('start');
    Route::post('/bank/settings', [\App\Http\Controllers\BankController::class, 'settings'])->name('settings');
    Route::post('/bank/plusInstallment', [\App\Http\Controllers\BankController::class, 'plusInstallment'])->name('plusInstallment');
    Route::post('/bank/getInstallment', [\App\Http\Controllers\BankController::class, 'getInstallment'])->name('getInstallment');
});

/**
 * Pay
 */
Route::name('pay.')->group(function () {
    Route::get('/pay/list', [\App\Http\Controllers\PayController::class, 'list'])->name('list');
    Route::post('/pay/postList', [\App\Http\Controllers\PayController::class, 'postList'])->name('postList');
    Route::get('/pay/screen', [\App\Http\Controllers\PayController::class, 'screen'])->name('screen');
    Route::get('/pay/dashboard', [\App\Http\Controllers\PayController::class, 'dashboard'])->name('dashboard');
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
    Route::post('/news/postAdd', [\App\Http\Controllers\NewsController::class, 'postAdd'])->name('postAdd');
    Route::post('/news/postUpdate', [\App\Http\Controllers\NewsController::class, 'postUpdate'])->name('postUpdate');
    Route::post('/news/postList', [\App\Http\Controllers\NewsController::class, 'postList'])->name('postList');
    Route::post('/news/postRemove', [\App\Http\Controllers\NewsController::class, 'postRemove'])->name('postRemove');
});

/**
 * Authority
 */
Route::name('authority.')->group(function () {
    Route::get('/authority', [\App\Http\Controllers\AuthorityController::class, 'start'])->name('start');
    Route::post('/authority/transactionConstraint', [\App\Http\Controllers\AuthorityController::class, 'transactionConstraint'])->name('transactionConstraint');
});

/**
 * Installment
 */
Route::name('installment.')->group(function () {
    Route::get('/installment', [\App\Http\Controllers\InstallmentController::class, 'start'])->name('start');
});