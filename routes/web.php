<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TravelController;
use App\Http\Controllers\ShareController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// ── Public share view (no auth needed) ──────────────────────────────────────
Route::get('/share/{token}', [ShareController::class, 'view'])->name('travel.share.view');

// ── Auth routes ──────────────────────────────────────────────────────────────
Route::get('/login',  [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->middleware('guest');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ── Authenticated routes ──────────────────────────────────────────────────────
Route::middleware(['auth', 'throttle:60,1'])->group(function () {

    Route::get('/', [TravelController::class, 'index'])->name('travels.index');
    Route::get('/travels/trash',            [TravelController::class, 'trash'])->name('travels.trash');
    Route::post('/travels/{id}/restore',    [TravelController::class, 'restore'])->name('travels.restore');
    Route::delete('/travels/{id}/force',    [TravelController::class, 'forceDelete'])->name('travels.force-delete');
    Route::delete('/travels/empty-trash',   [TravelController::class, 'emptyTrash'])->name('travels.empty-trash');

    Route::get('/travels/all', [TravelController::class, 'all'])->name('travels.all');
    Route::resource('travels', TravelController::class);
    Route::get('/travels/all', [TravelController::class, 'all'])->name('travels.all');
    Route::resource('travels', TravelController::class);

    // Share token management (admin only)
    Route::post('/travels/{travel}/share/generate', [ShareController::class, 'generate'])
        ->name('travel.share.generate')
        ->middleware('throttle:10,1')
        ->middleware('role:admin');

    Route::post('/travels/{travel}/share/revoke', [ShareController::class, 'revoke'])
        ->name('travel.share.revoke')
        ->middleware('role:admin');

    // User management (admin only)
    Route::middleware('role:admin')->group(function () {
        Route::resource('users', UserController::class);
    });
});
