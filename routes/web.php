<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PermissionController;
use App\Http\Middleware\CheckRole;

// Public routes
Route::get('/', function () {
    return view('welcome');
})->name('welcome');
Route::get('/contact', function () {
    return view('contact');
})->name('contact');


Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware(['web', 'auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');

    // Logout route
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

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
        
        // User Permissions routes
        Route::get('/users/{user}/permissions', [UserController::class, 'editPermissions'])->name('users.permissions.edit');
        Route::put('/users/{user}/permissions', [UserController::class, 'updatePermissions'])->name('users.permissions.update');
    });

    // Notification routes
    Route::post('/notifications/{notification}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
    Route::post('/notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');
    Route::delete('/notifications/{notification}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
});


// Login routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

// Registration routes
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register.form');
Route::post('/register', [AuthController::class, 'register'])->name('register');

// Protected routes (requires authentication)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');

    // Client routes
    Route::middleware([CheckRole::class . ':client'])->group(function () {
        Route::post('/request-to-agent', [NotificationController::class, 'requestToAgent'])->name('client.request.agent');
    });
    Route::get('/about', function () {
    return view('about');
})->name('about');


    // Agent routes
    Route::middleware([CheckRole::class . ':agent'])->group(function () {
        Route::get('/agent/clients', [UserController::class, 'agentClients'])->name('agent.clients');
        Route::get('/agent/clients/create', [UserController::class, 'createClientForm'])->name('agent.create.client.form');
        Route::post('/agent/clients', [UserController::class, 'storeClient'])->name('agent.store.client');
        Route::get('/agent/notifications/send-to-admin', [NotificationController::class, 'showSendToAdminForm'])->name('agent.notifications.send_to_admin_form');
        Route::post('/agent/request-to-admin', [NotificationController::class, 'requestToAdmin'])->name('agent.request.admin');
        Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
        Route::get('/agent/notifications', [NotificationController::class, 'agentNotifications'])->name('agent.notifications');
        Route::post('/agent/notifications', [NotificationController::class, 'sendNotification'])->name('agent.notifications.send');
        Route::get('/agent/maintenance-requests', [NotificationController::class, 'maintenanceRequests'])->name('agent.maintenance_requests');
        Route::post('/agent/maintenance-requests/{notification}/approve', [NotificationController::class, 'approveMaintenanceRequest'])->name('agent.maintenance_requests.approve');
        Route::post('/agent/maintenance-requests/{notification}/reject', [NotificationController::class, 'rejectMaintenanceRequest'])->name('agent.maintenance_requests.reject');
        Route::get('/agent/notifications/{notification}/view', [NotificationController::class, 'viewNotification'])->name('agent.notifications.view');
        Route::post('/agent/notifications/{notification}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('agent.notifications.markAsRead');
        Route::post('/agent/notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('agent.notifications.markAllAsRead');
        Route::delete('/agent/notifications/{notification}', [NotificationController::class, 'destroy'])->name('agent.notifications.destroy');
        Route::get('/agent/profile', [UserController::class, 'profile'])->name('agent.profile');
        Route::post('/agent/profile/update', [UserController::class, 'updateProfile'])->name('agent.profile.update');
        Route::get('/agent/profile/change-password', [UserController::class, 'showChangePasswordForm'])->name('agent.profile.change_password');
        Route::post('/agent/profile/change-password', [UserController::class, 'changePassword'])->name('agent.profile.change_password.post');
        Route::get('/agent/profile/edit', [UserController::class, 'editProfile'])->name('agent.profile.edit');
        Route::post('/agent/profile/update', [UserController::class, 'updateProfile'])->name('agent.profile.update');
        Route::get('/agent/profile/delete', [UserController::class, 'deleteProfile'])->name('agent.profile.delete');
        Route::post('/agent/profile/delete', [UserController::class, 'destroyProfile'])->name('agent.profile.destroy');
        Route::get('/agent/profile/notifications', [NotificationController::class, 'userNotifications'])->name('agent.profile.notifications');
        Route::post('/agent/profile/notifications/mark-as-read', [NotificationController::class, 'markProfileNotificationsAsRead'])->name('agent.profile.notifications.markAsRead');
        Route::post('/agent/profile/notifications/mark-all-as-read', [NotificationController::class, 'markAllProfileNotificationsAsRead'])->name('agent.profile.notifications.markAllAsRead');
        Route::delete('/agent/profile/notifications/{notification}', [NotificationController::class, 'destroyProfileNotification'])->name('agent.profile.notifications.destroy');
        Route::get('/agent/profile/notifications/{notification}/mark-as-read', [NotificationController::class, 'markProfileNotificationAsRead'])->name('agent.profile.notifications.markAsRead');
        Route::get('/agent/profile/notifications/{notification}/delete', [NotificationController::class, 'deleteProfileNotification'])->name('agent.profile.notifications.delete');
        Route::get('/agent/profile/notifications/{notification}/view', [NotificationController::class, 'viewProfileNotification'])->name('agent.profile.notifications.view');
        Route::get('/agent/profile/notifications/{notification}/unread', [NotificationController::class, 'markProfileNotificationAsUnread'])->name('agent.profile.notifications.markAsUnread');
        Route::get('/agent/profile/notifications/{notification}/read', [NotificationController::class, 'markProfileNotificationAsRead'])->name('agent.profile.notifications.markAsRead');
    });

    // Admin routes
    Route::middleware([CheckRole::class . ':admin'])->group(function () {
        Route::resource('users', UserController::class);
        Route::get('/permissions', [PermissionController::class, 'index'])->name('permissions.index');
        Route::resource('tanks', App\Http\Controllers\TankController::class);
        Route::resource('users', UserController::class);
        Route::get('/admin/notifications/send-to-agent', [NotificationController::class, 'showSendToAgentForm'])->name('admin.notifications.send_to_agent_form');
        Route::post('/admin/request-to-agent', [NotificationController::class, 'requestToAgent'])->name('admin.request.agent');
        Route::get('/admin/notifications/send-to-client', [NotificationController::class, 'showSendToClientForm'])->name('admin.notifications.send_to_client_form');
        Route::post('/admin/request-to-client', [NotificationController::class, 'requestToClient'])->name('admin.request.client');
        Route::get('/admin/maintenance-requests', [NotificationController::class, 'maintenanceRequests'])->name('admin.maintenance_requests');
        Route::post('/admin/maintenance-requests/{notification}/approve', [NotificationController::class, 'approveMaintenanceRequest'])->name('admin.maintenance_requests.approve');
        Route::post('/admin/maintenance-requests/{notification}/reject', [NotificationController::class, 'rejectMaintenanceRequest'])->name('admin.maintenance_requests.reject');
        Route::get('/admin/notifications', [NotificationController::class, 'adminNotifications'])->name('admin.notifications');
        Route::post('/admin/notifications', [NotificationController::class, 'sendNotification'])->name('admin.notifications.send');
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
        Route::get('/admin/profile', [UserController::class, 'profile'])->name('admin.profile');
        Route::post('/admin/profile/update', [UserController::class, 'updateProfile'])->name('admin.profile.update');
        Route::get('/admin/profile/change-password', [UserController::class, 'showChangePasswordForm'])->name('admin.profile.change_password');
        Route::post('/admin/profile/change-password', [UserController::class, 'changePassword'])->name('admin.profile.change_password.post');
        Route::get('/admin/profile/edit', [UserController::class, 'editProfile'])->name('admin.profile.edit');
        Route::post('/admin/profile/update', [UserController::class, 'updateProfile'])->name('admin.profile.update');
        Route::get('/admin/profile/delete', [UserController::class, 'deleteProfile'])->name('admin.profile.delete');
        Route::post('/admin/profile/delete', [UserController::class, 'destroyProfile'])->name('admin.profile.destroy');
        Route::get('/admin/profile/notifications', [NotificationController::class, 'userNotifications'])->name('admin.profile.notifications');
        Route::post('/admin/profile/notifications/mark-as-read', [NotificationController::class, 'markProfileNotificationsAsRead'])->name('admin.profile.notifications.markAsRead');
        Route::post('/admin/profile/notifications/mark-all-as-read', [NotificationController::class, 'markAllProfileNotificationsAsRead'])->name('admin.profile.notifications.markAllAsRead');
        Route::delete('/admin/profile/notifications/{notification}', [NotificationController::class, 'destroyProfileNotification'])->name('admin.profile.notifications.destroy');
        Route::get('/admin/profile/notifications/{notification}/mark-as-read', [NotificationController::class, 'markProfileNotificationAsRead'])->name('admin.profile.notifications.markAsRead');
        Route::get('/admin/profile/notifications/{notification}/delete', [NotificationController::class, 'deleteProfileNotification'])->name('admin.profile.notifications.delete');
        Route::get('/admin/profile/notifications/{notification}/view', [NotificationController::class, 'viewProfileNotification'])->name('admin.profile.notifications.view');
        Route::get('/admin/profile/notifications/{notification}/unread', [NotificationController::class, 'markProfileNotificationAsUnread'])->name('admin.profile.notifications.markAsUnread');

    });

    // Notification routes
    Route::post('/notifications/{notification}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
    Route::post('/notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');
    Route::delete('/notifications/{notification}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/profile', [UserController::class, 'profile'])->name('profile');
    Route::post('/profile/update', [UserController::class, 'updateProfile'])->name('profile.update');
    Route::get('/profile/change-password', [UserController::class, 'showChangePasswordForm'])->name('profile.change_password');
    Route::post('/profile/change-password', [UserController::class, 'changePassword'])->name('profile.change_password.post');
    Route::get('/profile/edit', [UserController::class, 'editProfile'])->name('profile.edit');
    Route::post('/profile/update', [UserController::class, 'updateProfile'])->name('profile.update');
    Route::get('/profile/delete', [UserController::class, 'deleteProfile'])->name('profile.delete');
    Route::post('/profile/delete', [UserController::class, 'destroyProfile'])->name('profile.destroy');
    Route::get('/profile/notifications', [NotificationController::class, 'userNotifications'])->name('profile.notifications');
    Route::post('/profile/notifications/mark-as-read', [NotificationController::class, 'markProfileNotificationsAsRead'])->name('profile.notifications.markAsRead');
    Route::post('/profile/notifications/mark-all-as-read', [NotificationController::class, 'markAllProfileNotificationsAsRead'])->name('profile.notifications.markAllAsRead');
    Route::delete('/profile/notifications/{notification}', [NotificationController::class, 'destroyProfileNotification'])->name('profile.notifications.destroy');
    Route::get('/profile/notifications/{notification}/mark-as-read', [NotificationController::class, 'markProfileNotificationAsRead'])->name('profile.notifications.markAsRead');
    Route::get('/profile/notifications/{notification}/delete', [NotificationController::class, 'deleteProfileNotification'])->name('profile.notifications.delete');
    Route::get('/profile/notifications/{notification}/view', [NotificationController::class, 'viewProfileNotification'])->name('profile.notifications.view');
    Route::get('/profile/notifications/{notification}/unread', [NotificationController::class, 'markProfileNotificationAsUnread'])->name('profile.notifications.markAsUnread');
    Route::get('/profile/notifications/{notification}/read', [NotificationController::class, 'markProfileNotificationAsRead'])->name('profile.notifications.markAsRead');
    Route::get('/profile/notifications/{notification}/delete', [NotificationController::class, 'deleteProfileNotification'])->name('profile.notifications.delete');
    Route::get('/profile/notifications/{notification}/view', [NotificationController::class, 'viewProfileNotification'])->name('profile.notifications.view');
    Route::get('/profile/notifications/{notification}/unread', [NotificationController::class, 'markProfileNotificationAsUnread'])->name('profile.notifications.markAsUnread');
    Route::get('/profile/notifications/{notification}/read', [NotificationController::class, 'markProfileNotificationAsRead'])->name('profile.notifications.markAsRead');
    Route::get('/profile/notifications/{notification}/delete', [NotificationController::class, 'deleteProfileNotification'])->name('profile.notifications.delete');
    Route::get('/profile/notifications/{notification}/view', [NotificationController::class, 'viewProfileNotification'])->name('profile.notifications.view');
    Route::get('/profile/notifications/{notification}/unread', [NotificationController::class, 'markProfileNotificationAsUnread'])->name('profile.notifications.markAsUnread');
    Route::get('/profile/notifications/{notification}/read', [NotificationController::class, 'markProfileNotificationAsRead'])->name('profile.notifications.markAsRead');
    Route::get('/profile/notifications/{notification}/delete', [NotificationController::class, 'deleteProfileNotification'])->name('profile.notifications.delete');
    Route::get('/profile/notifications/{notification}/view', [NotificationController::class, 'viewProfileNotification'])->name('profile.notifications.view');
    Route::get('/profile/notifications/{notification}/unread', [NotificationController::class, 'markProfileNotificationAsUnread'])->name('profile.notifications.markAsUnread');
    Route::get('/profile/notifications/{notification}/read', [NotificationController::class, 'markProfileNotificationAsRead'])->name('profile.notifications.markAsRead');
    Route::get('/profile/notifications/{notification}/delete', [NotificationController::class, 'deleteProfileNotification'])->name('profile.notifications.delete');
    Route::get('/profile/notifications/{notification}/view', [NotificationController::class, 'viewProfileNotification'])->name('profile.notifications.view');
    Route::get('/profile/notifications/{notification}/unread', [NotificationController::class, 'markProfileNotificationAsUnread'])->name('profile.notifications.markAsUnread');
    Route::get('/profile/notifications/{notification}/read', [NotificationController::class, 'markProfileNotificationAsRead'])->name('profile.notifications.markAsRead');
    Route::get('/profile/notifications/{notification}/delete', [NotificationController::class, 'deleteProfileNotification'])->name('profile.notifications.delete');
    Route::get('/profile/notifications/{notification}/view', [NotificationController::class, 'viewProfileNotification'])->name('profile.notifications.view');
    Route::get('/profile/notifications/{notification}/unread', [NotificationController::class, 'markProfileNotificationAsUnread'])->name('profile.notifications.markAsUnread');
    Route::get('/profile/notifications/{notification}/read', [NotificationController::class, 'markProfileNotificationAsRead'])->name('profile.notifications.markAsRead');
    Route::get('/profile/notifications/{notification}/delete', [NotificationController::class, 'deleteProfileNotification'])->name('profile.notifications.delete');
    Route::get('/profile/notifications/{notification}/view', [NotificationController::class, 'viewProfileNotification'])->name('profile.notifications.view');
    Route::get('/profile/notifications/{notification}/unread', [NotificationController::class, 'markProfileNotificationAsUnread'])->name('profile.notifications.markAsUnread');
    Route::get('/profile/notifications/{notification}/read', [NotificationController::class, 'markProfileNotificationAsRead'])->name('profile.notifications.markAsRead');
    Route::get('/profile/notifications/{notification}/delete', [NotificationController::class, 'deleteProfileNotification'])->name('profile.notifications.delete');
    Route::get('/profile/notifications/{notification}/view', [NotificationController::class, 'viewProfileNotification'])->name('profile.notifications.view');
    Route::get('/profile/notifications/{notification}/unread', [NotificationController::class, 'markProfileNotificationAsUnread'])->name('profile.notifications.markAsUnread');
    Route::get('/profile/notifications/{notification}/read', [NotificationController::class, 'markProfileNotificationAsRead'])->name('profile.notifications.markAsRead');
});

