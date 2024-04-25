<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

// Registered users account
use App\Http\Controllers\User\UserController as UserController;
use App\Http\Controllers\User\PostController as UserPostController;

Route::get('user', [UserController::class, 'dashboard'])->name('user');

Route::prefix('user')->name('user.')->group(function () {
    // profile
    Route::get('/profile', [UserController::class, 'profile'])->name('profile');
    Route::post('/profile', [UserController::class, 'update_profile']);
    Route::get('/profile/delete-avatar', [UserController::class, 'delete_avatar'])->name('profile.delete_avatar');
});
