<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ForumPostController;
use App\Http\Controllers\ForumCommentController;
use App\Http\Controllers\ForumLikeController;
use App\Http\Controllers\ForumProfileController;

use App\Http\Controllers\Panel\PanelDashboardController;
use App\Http\Controllers\Panel\ForumCategoryController;
use App\Http\Controllers\Panel\ForumPostModerationController;
use App\Http\Controllers\Panel\ForumCommentModerationController;
use App\Http\Controllers\Panel\UserManagementController;

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

// Dashboard Admin
Route::middleware('auth')->prefix('panel')->group(function () {
    Route::get('/', [PanelDashboardController::class, 'index'])
        ->name('panel.dashboard');

    Route::get('/users', [UserManagementController::class, 'index'])
    ->name('panel.users.index');

    Route::get('/users/{user}', [UserManagementController::class, 'show'])
    ->name('panel.users.show');

    Route::patch('/users/{user}/deactivate', [UserManagementController::class,'deactivate'])
    ->name('panel.users.deactivate');

    Route::patch('/users/{user}/activate', [UserManagementController::class,'activate'])
        ->name('panel.users.activate');
});

// Manajemen Forum Admin
Route::middleware('auth')->prefix('panel/forum')->name('panel.forum.')->group(function () {
    // Manajemen Kategori
    Route::prefix('categories')->name('categories.')->group(function () {
        Route::get('/', [ForumCategoryController::class, 'index'])
            ->name('index');

        Route::post('/', [ForumCategoryController::class, 'store'])
            ->name('store');

        Route::put('/{category}', [ForumCategoryController::class, 'update'])
            ->name('update');

        Route::delete('/{category}', [ForumCategoryController::class, 'destroy'])
            ->name('destroy');
    });

    // Moderasi Postingan
    Route::prefix('posts')->name('posts.')->group(function () {
        Route::get('/', [ForumPostModerationController::class, 'index'])
            ->name('index');

        Route::patch('/{post}/status', [ForumPostModerationController::class, 'updateStatus'])
            ->name('status');

        Route::delete('/{post}', [ForumPostModerationController::class, 'destroy'])
            ->name('destroy');

        // Halaman Sampah
        Route::get('/trash', [ForumPostModerationController::class, 'trash'])
            ->name('trash');

        // Restore postingan
        Route::patch('/{post}/restore', [ForumPostModerationController::class, 'restore'])
            ->withTrashed()
            ->name('restore');
        
        // Force delete postingan
        Route::delete('/{post}/force-delete', [ForumPostModerationController::class, 'forceDestroy'])
            ->withTrashed()
            ->name('force-destroy');
    });

    // Moderasi Komentar
    Route::prefix('comments')->name('comments.')->group(function () {
        Route::get('/', [ForumCommentModerationController::class, 'index'])
            ->name('index');

        Route::get('/trash', [ForumCommentModerationController::class, 'trash'])
            ->name('trash');

        Route::delete('/{comment}', [ForumCommentModerationController::class, 'destroy'])
            ->name('destroy');

        Route::patch('/{comment}/restore', [ForumCommentModerationController::class, 'restore'])
            ->withTrashed()
            ->name('restore');

        Route::delete('/{comment}/force-delete', [ForumCommentModerationController::class,'forceDestroy'])
            ->withTrashed()
            ->name('force-destroy');
    });
});

Route::view('/periksa', 'periksa')->name('periksa');
Route::view('/evaluasi', 'evaluasi')->name('evaluasi');
Route::view('/tentang', 'tentang')->name('tentang');