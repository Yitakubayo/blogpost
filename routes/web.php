<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\EmailSubscriptionController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/', [PostController::class, 'index'])->name('home');

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Posts
    Route::resource('posts', PostController::class)->except(['index', 'show']);
    Route::post('/posts/{post}/share/{platform}', [PostController::class, 'share'])->name('posts.share');

    // Comments
    Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('posts.comments.store');
    Route::put('/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

    // Likes
    Route::post('/likes/toggle', [LikeController::class, 'toggleLike'])->name('likes.toggle');
    Route::get('/likes/count', [LikeController::class, 'getLikesCount'])->name('likes.count');

    // Email subscriptions
    Route::post('/subscribe', [EmailSubscriptionController::class, 'subscribe'])->name('subscribe');
    Route::post('/unsubscribe', [EmailSubscriptionController::class, 'unsubscribe'])->name('unsubscribe');
});

// Public routes
Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');

require __DIR__.'/auth.php';
