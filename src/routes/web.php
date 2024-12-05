<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\RegisterUserController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ManagerController;
use App\Http\Controllers\ImageController;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use App\Http\Controllers\ReservationController;

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

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/done', [UserController::class, 'showDone'])->name('done');
    Route::post('/favorite/{id}', [UserController::class, 'favorite'])->name('favorite');
    Route::get('/search', [UserController::class, 'search'])->name('search');
    Route::get('/detail/{id}', [UserController::class, 'detail'])->name('detail');
    Route::get('restaurant/{id}/filter', [UserController::class, 'filter'])->name('reviews.filter');
    Route::get('/user/my-page', [UserController::class, 'show'])->name('my-page');
    Route::delete('/delete/{id}', [UserController::class, 'delete'])->name('delete');
    Route::patch('/update/{id}', [UserController::class, 'update'])->name('update');
    Route::post('/review/{id}', [UserController::class, 'storeReview'])->name('review');


    Route::post('/create-checkout-session', [ReservationController::class, 'createCheckoutSession'])->name('payment.createCheckoutSession');
    Route::get('reservation/success/{reservationId}', [ReservationController::class, 'success'])->name('payment.success');
    Route::get('reservation/cancel/{reservationId}', [ReservationController::class, 'cancel'])->name('payment.cancel');



    Route::get('/reservation/verify/{id}', [ReservationController::class, 'generateQrCode'])->name('reservation.QrCode');
    Route::get('/reservation/scan', [ReservationController::class, 'showQrScanner'])->name('reservation.scan');
    Route::post('/reservation/verify/confirm', [ReservationController::class, 'verifyReservation'])->name('reservation.verify');


});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/admin/user', [AdminController::class, 'index'])->name('admin.index');
    Route::get('/admin/search', [AdminController::class, 'search'])->name('admin.search');
    Route::post('/admin/store', [AdminController::class, 'store'])->name('admin.store');
    Route::patch('/admin/update/{id}', [AdminController::class, 'update'])->name('admin.update');
    Route::delete('/admin/delete/{id}', [AdminController::class, 'delete'])->name('admin.delete');
    Route::get('/admin/mailForm',[adminController::class,'showSendMailForm'])->name('admin.showSendMailForm');
    Route::post('/admin/send-mail', [adminController::class, 'sendMail'])->name('admin.sendMail');
});


Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/manager', [ManagerController::class, 'index'])->name('manager.index');
    Route::get('/manager/search', [ManagerController::class, 'search'])->name('manager.search');
    Route::patch('/manager/update/{id}', [ManagerController::class, 'update'])->name('manager.update');
    Route::delete('/manager/delete/{id}', [ManagerController::class, 'delete'])->name('manager.delete');
    Route::get('/manager/create', [ManagerController::class, 'show'])->name('manager.create');
    Route::get('/manager/detail', [ManagerController::class, 'showDetail'])->name('manager.detail');
    Route::post('/manager/store', [ManagerController::class, 'store'])->name('manager.store');
    Route::patch('/manager/restaurant/update/', [ManagerController::class, 'updateDetail'])->name('manager.updateDetail');
    Route::get('/manager/reviews', [ManagerController::class, 'showReviews'])->name('manager.showReviews');
});

Route::get('/menu/user', [UserController::class, 'showUserMenu']);
Route::get('/', [UserController::class, 'index'])->name('home');
Route::get('/login', [AuthenticatedSessionController::class, 'create'])
    ->name('login');
Route::post('/register', [RegisterUserController::class, 'store']);
Route::post('/login', [RegisterUserController::class, 'login']);
Route::get('/thanks', [UserController::class, 'showThanks'])->name('thanks');












Route::get('/import', [ImageController::class, 'import'])->name('restaurants.import');
Route::post('/import/file', [ImageController::class, 'importCsv'])->name('restaurants.importCsv');
Route::post('/upload/image', [ImageController::class, 'store'])->name('image.store');


