<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use Illuminate\Http\Request;
use App\Http\Controllers\Client\ActionController;
use App\Http\Controllers\Client\RequestController;

// Admin Routes
Route::middleware(['auth:sanctum', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard']);
    Route::post('/users', [AdminController::class, 'createUser']);
    Route::put('/users/{user}/role', [AdminController::class, 'updateUserRole']);
    Route::delete('/users/{user}', [AdminController::class, 'deleteUser']);
    Route::post('/maintenance/{maintenance}/assign', [AdminController::class, 'assignMaintenance']);

    // Permission Management Routes
    Route::get('/permissions', [AdminController::class, 'getAllPermissions']);
    Route::get('/users/{user}/permissions', [AdminController::class, 'getUserPermissions']);
    Route::post('/users/{user}/permissions', [AdminController::class, 'syncUserPermissions']);
});

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth:sanctum'])->group(function () {
    // Client API routes
    Route::prefix('client')->group(function () {
        Route::post('/action', [ActionController::class, 'handleAction']);
        
        // Request Management Routes
        Route::get('/requests', [RequestController::class, 'index']);
        Route::post('/request', [RequestController::class, 'store']);
        Route::put('/request/{clientRequest}', [RequestController::class, 'update']);
        Route::delete('/request/{clientRequest}', [RequestController::class, 'destroy']);
    });
}); 