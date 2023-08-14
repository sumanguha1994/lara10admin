<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\AuthController;
use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\admin\CmsController;

#Admin routes
Route::get('/', function(){
    return redirect()->route('admin.dashboard');
});
Route::match(['GET', 'POST'], '/', [AuthController::class, 'login'])->name('login');
Route::match(['GET', 'POST'], 'forgot-password', [AuthController::class, 'forgotpassword'])->name('forgot.password');
Route::match(['GET', 'POST'], 'password-recovery/{rowid?}', [AuthController::class, 'passwordrecovery'])->name('password.recovery');
Route::get('logoutscreen', [AuthController::class, 'logoutscreen'])->name('logoutscreen');
Route::get('logout', [AuthController::class, 'logout'])->name('logout');
Route::middleware(['auth'])->group(function (){ 
    Route::get('dashboard', [DashboardController::class, 'dashboard'])->name('dashboard'); #dashboard
    Route::match(['GET', 'POST'], 'banner', [DashboardController::class, 'banner'])->name('banner'); #banner
    Route::match(['GET', 'POST'], 'profile/{type?}', [DashboardController::class, 'profile'])->name('profile'); #profile
    Route::match(['GET', 'POST'], 'setting/{type?}', [DashboardController::class, 'setting'])->name('setting'); #setting
    Route::prefix('cms')->name('cms.')->group(function () {
        Route::match(['GET', 'POST'], 'page', [CmsController::class, 'page'])->name('page');
        Route::match(['GET', 'POST'], 'content', [CmsController::class, 'content'])->name('content');
    });
    Route::get('get-single/{type?}/{rowid?}', [DashboardController::class, 'getsingle'])->name('get.single');  #all
    Route::post('status-change', [DashboardController::class, 'statuschange'])->name('status.change');  #all
});
