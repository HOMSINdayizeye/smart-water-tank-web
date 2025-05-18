<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PermissionController;
use App\Http\Middleware\CheckRole;

// Public routes
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware(['web', 'auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');

    // Client routes
    Route::middleware([CheckRole::class.':client'])->group(function () {
        Route::post('/request-to-agent', [NotificationController::class, 'requestToAgent'])->name('client.request.agent');
    });

    // Agent routes
    Route::middleware([CheckRole::class.':agent'])->group(function () {
        Route::get('/agent/clients', [UserController::class, 'agentClients'])->name('agent.clients');
        Route::get('/agent/clients/create', [UserController::class, 'createClientForm'])->name('agent.create.client.form');
        Route::post('/agent/clients', [UserController::class, 'storeClient'])->name('agent.store.client');
        Route::get('/agent/notifications/send-to-admin', [NotificationController::class, 'showSendToAdminForm'])->name('agent.notifications.send_to_admin_form');
        Route::post('/agent/request-to-admin', [NotificationController::class, 'requestToAdmin'])->name('agent.request.admin');
    });

    // Admin routes
    Route::middleware([CheckRole::class.':admin'])->group(function () {
        Route::resource('users', UserController::class);
        Route::get('/permissions', [PermissionController::class, 'index'])->name('permissions.index');
        Route::resource('tanks', App\Http\Controllers\TankController::class);
    });

    // Notification routes
    Route::post('/notifications/{notification}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
    Route::post('/notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');
    Route::delete('/notifications/{notification}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
});
