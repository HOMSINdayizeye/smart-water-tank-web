<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\User;
use Illuminate\Http\Request;

class PermissionController extends Controller {
    /**
     * Display a listing of the permissions
     */
    public function index()
    {
        $permissions = Permission::all();
        $agents = User::where('role', 'agent')->get();
        
        return view('permissions.index', compact('permissions', 'agents'));
    }

    /**
     * Assign permissions to a user
     */
    public function assignPermissions(Request $request)
    {
        $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'permissions' => ['array'],
            'permissions.*' => ['exists:permissions,id'],
        ]);

        $user = User::find($request->user_id);
        
        // Check if user is an agent
        if ($user->role !== 'agent') {
            return redirect()->back()->with('error', 'Permissions can only be assigned to agents.');
        }
        
        // Sync permissions
        $user->permissions()->sync($request->permissions ?? []);
        
        return redirect()->back()->with('success', 'Permissions assigned successfully');
    }
}