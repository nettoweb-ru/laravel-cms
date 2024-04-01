<?php

use Illuminate\Support\Facades\Route;
use Netto\Http\Controllers\AlbumController;
use Netto\Http\Controllers\AuthenticatedSessionController;
use Netto\Http\Controllers\BrowserController;
use Netto\Http\Controllers\ConfirmablePasswordController;
use Netto\Http\Controllers\CookieController;
use Netto\Http\Controllers\DownloadController;
use Netto\Http\Controllers\EmailVerificationNotificationController;
use Netto\Http\Controllers\EmailVerificationPromptController;
use Netto\Http\Controllers\ImageController;
use Netto\Http\Controllers\LanguageController;
use Netto\Http\Controllers\MenuController;
use Netto\Http\Controllers\MenuItemController;
use Netto\Http\Controllers\NewPasswordController;
use Netto\Http\Controllers\PasswordController;
use Netto\Http\Controllers\PasswordResetLinkController;
use Netto\Http\Controllers\PermissionController;
use Netto\Http\Controllers\ProfileController;
use Netto\Http\Controllers\PublicationController;
use Netto\Http\Controllers\RoleController;
use Netto\Http\Controllers\UserBalanceController;
use App\Http\Controllers\Admin\UserController;
use Netto\Http\Controllers\VerifyEmailController;

Route::prefix(CMS_LOCATION)->name('admin.')->group(function() {
    Route::middleware('admin.guest')->group(function() {
        Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
        Route::post('login', [AuthenticatedSessionController::class, 'store'])->name('login.store');

        Route::get('profile/password/forgot', [PasswordResetLinkController::class, 'create'])->name('password.request');
        Route::post('profile/password/forgot', [PasswordResetLinkController::class, 'store'])->name('password.email');

        Route::get('profile/password/reset/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
        Route::post('profile/password/reset', [NewPasswordController::class, 'store'])->name('password.store');
    });

    Route::middleware('admin')->group(function() {
        Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('profile', [ProfileController::class, 'update'])->name('profile.update');

        Route::put('profile/password', [PasswordController::class, 'update'])->name('profile.password.update');

        Route::get('profile/verify', EmailVerificationPromptController::class)->name('verification.notice');
        Route::post('profile/verify/send', [EmailVerificationNotificationController::class, 'store'])->middleware('throttle:6,1')->name('verification.send');
        Route::get('profile/verify/{id}/{hash}', VerifyEmailController::class)->middleware(['signed', 'throttle:6,1'])->name('verification.verify');

        Route::get('profile/password/confirm', [ConfirmablePasswordController::class, 'show'])->name('password.confirm');
        Route::post('profile/password/confirm', [ConfirmablePasswordController::class, 'store']);

        Route::post('setCookie', [CookieController::class, 'set'])->name('cookie.set');
        Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

        Route::middleware('verified')->group(function() {
            Route::get('/', [PublicationController::class, 'index'])->name('home');
            Route::resource('publication', PublicationController::class)->except(['toggle', 'index']);

            Route::resource('language', LanguageController::class)->except('toggle');
            Route::get('download', DownloadController::class)->name('download');

            Route::middleware('permission:manage-menu')->group(function() {
                Route::resource('menu', MenuController::class)->except('toggle');
                Route::resource('menu.menuItem', MenuItemController::class)->except('index');
            });

            Route::middleware('permission:manage-users')->group(function() {
                Route::resource('user', UserController::class)->except(['toggle']);
                Route::resource('user.balance', UserBalanceController::class)->except(['index', 'toggle']);
            });

            Route::middleware('permission:manage-access')->group(function() {
                Route::resource('role', RoleController::class)->except(['toggle']);
                Route::resource('permission', PermissionController::class)->except(['toggle', 'index']);
            });

            Route::get('browser', [BrowserController::class, 'create'])->name('browser');
            Route::get('browser/list', [BrowserController::class, 'list'])->name('browser.list');
            Route::post('browser/upload', [BrowserController::class, 'upload'])->name('browser.upload');
            Route::put('browser/directory', [BrowserController::class, 'directory'])->name('browser.directory');
            Route::post('browser/delete', [BrowserController::class, 'delete'])->name('browser.delete');;

            Route::resource('album', AlbumController::class)->except('toggle');
            Route::resource('album.image', ImageController::class)->except(['index', 'toggle']);
        });
    });
});
