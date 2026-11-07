<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ForumPostController;
use App\Http\Controllers\ForumCommentController;
use App\Http\Controllers\ForumLikeController;
use App\Http\Controllers\ForumProfileController;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('auth')->group(function () {
    Route::get('login', [AuthController::class, 'login'])->name('login');
    Route::post('login', [AuthController::class, 'authenticate']);
    Route::get('register', [AuthController::class, 'register'])->name('register');
    Route::post('register', [AuthController::class, 'store']);
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
});

Route::prefix('forum')->name('forum.')->group(function () {

    Route::get('/', [ForumPostController::class, 'index'])
        ->name('posts.index');
    
    Route::get('/pengguna/{user}', [ForumProfileController::class, 'show'])
        ->name('profile.show');

    Route::middleware('auth')->group(function () {

        Route::get('/buat', [ForumPostController::class, 'create'])
            ->name('posts.create');

        Route::post('/', [ForumPostController::class, 'store'])
            ->name('posts.store');

        Route::get('/{post:slug}/edit', [ForumPostController::class, 'edit'])
            ->name('posts.edit');

        Route::put('/{post:slug}', [ForumPostController::class, 'update'])
            ->name('posts.update');

        Route::delete('/{post:slug}', [ForumPostController::class, 'destroy'])
            ->name('posts.destroy');

        Route::post('/{post:slug}/comments', [ForumCommentController::class, 'store'])
            ->name('comments.store');

        Route::delete('/comments/{comment}', [ForumCommentController::class, 'destroy'])
            ->name('comments.destroy');

        Route::post('/{post:slug}/like', [ForumLikeController::class, 'toggle'])
            ->name('likes.toggle');
    });

    Route::get('/{post:slug}', [ForumPostController::class, 'show'])
        ->name('posts.show');
});