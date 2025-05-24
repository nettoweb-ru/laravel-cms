<?php

use Illuminate\Support\Facades\Route;

Route::middleware('public')->group(function() {
    Route::get('/', [\App\Http\Controllers\Public\Controller::class, 'home'])->name('ru.home');
});
