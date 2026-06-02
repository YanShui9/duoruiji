<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\LectureController as FrontLectureController;
use App\Http\Controllers\Frontend\ExpertController as FrontExpertController;
use App\Http\Controllers\Frontend\VideoController as FrontVideoController;

// 前台路由
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/lectures', [FrontLectureController::class, 'index'])->name('lectures.index');
Route::get('/lectures/{id}', [FrontLectureController::class, 'show'])->name('lectures.show');
Route::get('/experts', [FrontExpertController::class, 'index'])->name('experts.index');
Route::get('/experts/{id}', [FrontExpertController::class, 'show'])->name('experts.show');
Route::get('/videos', [FrontVideoController::class, 'index'])->name('videos.index');
Route::get('/videos/{id}', [FrontVideoController::class, 'show'])->name('videos.show');

// 后台路由
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::get('/', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::resource('experts', App\Http\Controllers\Admin\ExpertController::class);
    Route::resource('lectures', App\Http\Controllers\Admin\LectureController::class);
    Route::resource('videos', App\Http\Controllers\Admin\VideoController::class);
    Route::resource('users', App\Http\Controllers\Admin\UserController::class)->only(['index', 'edit', 'update', 'destroy']);
});

Auth::routes();
