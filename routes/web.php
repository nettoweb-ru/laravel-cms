<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use Netto\Http\Controllers\Admin\{
    AlbumController,
    AuthenticatedSessionController,
    PublicDiskController,
    ConfirmablePasswordController,
    EmailVerificationNotificationController,
    EmailVerificationPromptController,
    HelperController,
    ImageController,
    LanguageController,
    MenuController,
    MenuItemController,
    NavigationController,
    NavigationItemController,
    NewPasswordController,
    PasswordController,
    PasswordResetLinkController,
    PermissionController,
    ProfileController,
    PublicationController,
    RoleController,
    UserBalanceController,
    VerifyEmailController,
};

Route::prefix(config('cms.location'))->name(config('cms.location').'.')->group(function() {
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

        Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

        Route::middleware('verified')->group(function() {
            Route::get('/', [HelperController::class, 'home'])->name('home');

            Route::middleware('permission:admin-publications')->group(function() {
                Route::resource('publication', PublicationController::class)->except(['toggle']);
            });

            Route::middleware('permission:admin-photo-albums')->group(function() {
                Route::resource('album', AlbumController::class)->except('toggle');
                Route::resource('album/image', ImageController::class)->names([
                    'store' => 'album-image.store',
                    'create' => 'album-image.create',
                    'delete' => 'album-image.delete',
                    'list' => 'album-image.list',
                    'update' => 'album-image.update',
                    'destroy' => 'album-image.destroy',
                    'edit' => 'album-image.edit',
                ])->except(['index', 'toggle']);
            });

            Route::middleware('permission:admin-languages')->group(function() {
                Route::resource('language', LanguageController::class)->except('toggle');
            });

            Route::middleware('permission:admin-menu')->group(function() {
                Route::resource('menu', MenuController::class)->except('toggle');
                Route::resource('menu/item', MenuItemController::class)->names([
                    'store' => 'menu-item.store',
                    'create' => 'menu-item.create',
                    'delete' => 'menu-item.delete',
                    'list' => 'menu-item.list',
                    'toggle' => 'menu-item.toggle',
                    'update' => 'menu-item.update',
                    'destroy' => 'menu-item.destroy',
                    'edit' => 'menu-item.edit',
                ])->except('index');
            });

            Route::middleware('permission:admin-navigation')->group(function() {
                Route::resource('navigation', NavigationController::class);
                Route::resource('navigation/item', NavigationItemController::class)->names([
                    'store' => 'navigation-item.store',
                    'create' => 'navigation-item.create',
                    'delete' => 'navigation-item.delete',
                    'list' => 'navigation-item.list',
                    'toggle' => 'navigation-item.toggle',
                    'update' => 'navigation-item.update',
                    'destroy' => 'navigation-item.destroy',
                    'edit' => 'navigation-item.edit',
                ])->except('index');
            });

            Route::middleware('permission:admin-users')->group(function() {
                Route::resource('user', UserController::class)->except(['toggle']);
                Route::resource('user/balance', UserBalanceController::class)->names([
                    'store' => 'user-balance.store',
                    'create' => 'user-balance.create',
                    'delete' => 'user-balance.delete',
                    'list' => 'user-balance.list',
                    'update' => 'user-balance.update',
                    'destroy' => 'user-balance.destroy',
                    'edit' => 'user-balance.edit',
                ])->except(['index', 'toggle']);
            });

            Route::middleware('permission:admin-access')->group(function() {
                Route::resource('role', RoleController::class)->except(['toggle']);
                Route::resource('permission', PermissionController::class)->except(['toggle', 'index']);
            });

            Route::middleware('permission:admin-public-browser')->group(function() {
                Route::get('browser', [PublicDiskController::class, 'index'])->name('browser');
                Route::get('browser/list', [PublicDiskController::class, 'list'])->name('browser.list');
                Route::post('browser/upload', [PublicDiskController::class, 'upload'])->name('browser.upload');
                Route::put('browser/directory', [PublicDiskController::class, 'directory'])->name('browser.directory');
                Route::post('browser/delete', [PublicDiskController::class, 'delete'])->name('browser.delete');;
            });

            Route::get('tools/download', [HelperController::class, 'download']);
            Route::get('tools/transliterate', [HelperController::class, 'transliterate']);
            Route::post('tools/cookie', [HelperController::class, 'cookie']);
        });
    });
});
