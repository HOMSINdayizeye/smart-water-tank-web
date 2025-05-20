<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Maintenance;
use App\Models\Notification;
use App\Models\Alert;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function dashboard()
    {
        $stats = [
            'totalUsers' => User::count(),
            'totalAgents' => User::where('role', 'agent')->count(),
            'totalClients' => User::where('role', 'client')->count(),
            'totalMaintenance' => Maintenance::count(),
            'pendingMaintenance' => Maintenance::where('status', 'pending')->count(),
            'totalAlerts' => Alert::count(),
            'criticalAlerts' => Alert::where('severity', 'critical')->count(),
            'totalNotifications' => Notification::count(),
        ];

        $users = User::latest()->get();
        $notifications = Notification::latest()->take(10)->get();
        $alerts = Alert::latest()->take(10)->get();
        $maintenance = Maintenance::with('user')->latest()->take(10)->get();

        return response()->json([
            'stats' => $stats,
            'users' => $users,
            'notifications' => $notifications,
            'alerts' => $alerts,
            'maintenance' => $maintenance,
        ]);
    }

    public function createUser(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => ['required', Rule::in(['admin', 'agent', 'client'])],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'status' => 'active',
        ]);

        return response()->json($user, 201);
    }

    public function updateUserRole(Request $request, User $user)
    {
        $validated = $request->validate([
            'role' => ['required', Rule::in(['admin', 'agent', 'client'])],
        ]);

        $user->update([
            'role' => $validated['role'],
        ]);

        return response()->json($user);
    }

    public function assignMaintenance(Request $request, Maintenance $maintenance)
    {
        $validated = $request->validate([
            'teamId' => 'required|exists:teams,id',
        ]);

        $maintenance->update([
            'team_id' => $validated['teamId'],
            'status' => 'in-progress',
        ]);

        return response()->json($maintenance);
    }

    public function deleteUser(User $user)
    {
        $user->delete();
        return response()->json(null, 204);
    }

    public function getAllPermissions()
    {
        $permissions = Permission::orderBy('category')->orderBy('name')->get();
        return response()->json($permissions);
    }

    public function getUserPermissions(User $user)
    {
        $permissions = $user->permissions()->orderBy('category')->orderBy('name')->get();
        return response()->json($permissions);
    }

    public function syncUserPermissions(Request $request, User $user)
    {
        $validated = $request->validate([
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $user->permissions()->sync($validated['permissions']);

        return response()->json([
            'message' => 'Permissions updated successfully',
            'permissions' => $user->permissions()->orderBy('category')->orderBy('name')->get()
        ]);
    }
} 