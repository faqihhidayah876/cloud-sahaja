<?php

use App\Http\Controllers\FileController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return view('welcome');
})->name('welcome');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    
    // File Manager - Specific / static routes first
    Route::get('/files', [FileController::class, 'index'])->name('files.index');
    Route::post('/files', [FileController::class, 'store'])->name('files.store');
    Route::post('/files/chunk', [FileController::class, 'uploadChunk'])->name('files.uploadChunk');
    Route::post('/files/folder', [FileController::class, 'createFolder'])->name('files.createFolder');
    Route::get('/files/starred', [FileController::class, 'starredIndex'])->name('files.starred');
    Route::get('/files/trash', [FileController::class, 'trashIndex'])->name('files.trash');
    Route::delete('/files/trash/empty', [FileController::class, 'emptyTrash'])->name('files.emptyTrash');
    
    // File Actions by {id}
    Route::get('/files/{id}/preview', [FileController::class, 'preview'])->name('files.preview');
    Route::get('/files/{id}/siblings', [FileController::class, 'siblings'])->name('files.siblings');
    Route::get('/files/{id}/download', [FileController::class, 'download'])->name('files.download');
    Route::delete('/files/{id}', [FileController::class, 'destroy'])->name('files.destroy');
    Route::post('/files/{id}/star', [FileController::class, 'toggleStar'])->name('files.star');
    Route::post('/files/{id}/share', [FileController::class, 'share'])->name('files.share');
    Route::delete('/files/{id}/share', [FileController::class, 'unshare'])->name('files.unshare');
    Route::post('/files/{id}/restore', [FileController::class, 'restore'])->name('files.restore');
    Route::delete('/files/{id}/force', [FileController::class, 'forceDelete'])->name('files.forceDelete');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile/avatar', [ProfileController::class, 'deleteAvatar'])->name('profile.deleteAvatar');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Route publik untuk share link (di luar grup auth)
Route::get('/share/{token}', [FileController::class, 'showShared'])->name('files.shared');
Route::get('/share/{token}/preview', [FileController::class, 'previewShared'])->name('files.sharedPreview');
Route::get('/share/{token}/download', [FileController::class, 'downloadShared'])->name('files.sharedDownload');

// Google OAuth
Route::get('auth/google', [\App\Http\Controllers\Auth\GoogleController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('auth/google/callback', [\App\Http\Controllers\Auth\GoogleController::class, 'handleGoogleCallback']);

// Admin Panel
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\AdminController::class, 'index'])->name('dashboard');
    Route::post('/users/{id}/quota', [\App\Http\Controllers\AdminController::class, 'updateQuota'])->name('users.updateQuota');
    Route::post('/users/{id}/toggle-disable', [\App\Http\Controllers\AdminController::class, 'toggleDisable'])->name('users.toggleDisable');
    Route::delete('/users/{id}', [\App\Http\Controllers\AdminController::class, 'destroy'])->name('users.destroy');
    Route::delete('/users/{id}/reset-files', [\App\Http\Controllers\AdminController::class, 'resetFiles'])->name('users.resetFiles');
});

require __DIR__.'/auth.php';
