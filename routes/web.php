<?php

use App\Http\Controllers\PasteController;
use Illuminate\Support\Facades\Route;

// Main routes
Route::get('/', [PasteController::class, 'index'])->name('paste.create');
Route::post('/', [PasteController::class, 'store'])->name('paste.store');

// Paste viewing routes
Route::get('/{slug}', [PasteController::class, 'show'])->name('paste.show');
Route::post('/{slug}/password', [PasteController::class, 'verifyPassword'])->name('paste.password');
Route::get('/{slug}/raw', [PasteController::class, 'raw'])->name('paste.raw');

// API routes
Route::prefix('api')->group(function () {
    Route::post('/paste', [PasteController::class, 'apiStore'])->name('api.paste.store');
});
