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
    Route::get('/', [\App\Http\Controllers\StartController::class, 'start'])->middleware('app.logout')->name('start');
    Route::get('/404', [\App\Http\Controllers\StartController::class, 'danger'])->name('danger');
});

Route::middleware('app.login')->group(function () {
    /**
     * Login
     */
    Route::name('login.')->group(function () {
        Route::get('/login', [\App\Http\Controllers\LoginController::class, 'start'])->name('start');
        Route::post('/login/login', [\App\Http\Controllers\LoginController::class, 'login'])->name('login');
    });
});

Route::middleware('app.logout')->group(function () {
    /**
     * Logout
     */
    Route::name('logout.')->group(function () {
        Route::get('/logout', [\App\Http\Controllers\LogoutController::class, 'start'])->name('start');
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
        Route::get('/seller/update/{id}', [\App\Http\Controllers\SellerController::class, 'update'])->name('update');
        Route::get('/seller/pay/{id}', [\App\Http\Controllers\SellerController::class, 'pay'])->name('pay');
        Route::post('/seller/post/add', [\App\Http\Controllers\SellerController::class, 'postAdd'])->name('post.add');
        Route::post('/seller/post/update', [\App\Http\Controllers\SellerController::class, 'postUpdate'])->name('post.update');
        Route::post('/seller/post/list', [\App\Http\Controllers\SellerController::class, 'postList'])->name('post.list');
        Route::post('/seller/post/remove', [\App\Http\Controllers\SellerController::class, 'postRemove'])->name('post.remove');
    });
    /**
     * Bank
     */
    Route::name('bank.')->group(function () {
        Route::get('/bank/{id?}', [\App\Http\Controllers\BankController::class, 'start'])->name('start');
        Route::post('/bank/settings', [\App\Http\Controllers\BankController::class, 'settings'])->name('settings');
    });
    /**
     * Pay
     */
    Route::name('pay.')->group(function () {
        Route::get('/pay/list', [\App\Http\Controllers\PayController::class, 'list'])->name('list');
        Route::post('/pay/postList', [\App\Http\Controllers\PayController::class, 'postList'])->name('postList');
        Route::get('/pay/screen', [\App\Http\Controllers\PayController::class, 'screen'])->name('screen');
        Route::get('/pay/dashboard', [\App\Http\Controllers\PayController::class, 'dashboard'])->name('dashboard');
        Route::post('/pay/request', [\App\Http\Controllers\PayController::class, 'payRequest'])->name('request');
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
        Route::get('/authority/{id?}', [\App\Http\Controllers\AuthorityController::class, 'start'])->name('start');
        Route::post('/authority/set', [\App\Http\Controllers\AuthorityController::class, 'set'])->name('set');
    });
    /**
     * Installment
     */
    Route::name('installment.')->group(function () {
        Route::get('/installment/{id?}', [\App\Http\Controllers\InstallmentController::class, 'start'])->name('start');
        Route::post('/installment/set', [\App\Http\Controllers\InstallmentController::class, 'set'])->name('set');
        Route::post('/installment/get', [\App\Http\Controllers\BankController::class, 'get'])->name('get');
    });
});