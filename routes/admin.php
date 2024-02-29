<?php

use App\Http\Controllers\Admin\AdminAuthentificationController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LanguageController;
use App\Http\Controllers\Admin\NewsController;
use App\Http\Controllers\Admin\ProfileController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::get('login', [AdminAuthentificationController::class, 'login'])->name('login');
    Route::post('login', [AdminAuthentificationController::class, 'handleLogin'])->name('handle-login');
    Route::post('logout', [AdminAuthentificationController::class, 'logout'])->name('logout');

    //Reset Password
    Route::get('forgot-password', [AdminAuthentificationController::class, 'forgotPassword'])->name('forgot-password');
    Route::post('forgot-password', [AdminAuthentificationController::class, 'sendResetLink'])->name('forgot-password.send');

    //Reset Link
    Route::get('reset-password/{token}', [AdminAuthentificationController::class, 'resetPassword'])->name('reset-password');
    Route::post('reset-password', [AdminAuthentificationController::class, 'handleResetPassword'])->name('reset-password.send');
});


Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['admin']], function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    //
    Route::put('profile-password-update/{id}', [ProfileController::class, 'passwordUpdate'])->name('profile-password.update');
    Route::resource('profile', ProfileController::class);

    //Language
    Route::resource('language', LanguageController::class);

    Route::resource('category', CategoryController::class);

    //NewsPost
    Route::get('fetch-news-category', [NewsController::class, 'fetchCategory'])->name('fetch-news-category');
    Route::get('toggle-news-category', [NewsController::class, 'toggleNewsCategory'])->name('toggle-news-category');
    Route::get('news-copy/{id}', [NewsController::class, 'copyNews'])->name('news-copy');
    Route::resource('news', NewsController::class);
});
