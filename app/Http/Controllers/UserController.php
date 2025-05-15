<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller {

    /**
     * Display a listing of the users
     */
    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user
     */
    public function create()
    {
        $roles = ['admin', 'agent', 'client'];
        return view('users.create', compact('roles'));
    }

    /**
     * Store a newly created user
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:admin,agent,client'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'created_by' => Auth::id(),
        ]);

        // If creating a client, also create a tank
        if ($request->role === 'client') {
            $user->tank()->create([
                'location' => $request->location ?? 'Default Location',
                'capacity' => $request->capacity ?? 1000,
                'current_level' => $request->current_level ?? 500,
                'ph_level' => $request->ph_level ?? 7.0,
                'chloride_level' => $request->chloride_level ?? 200,
                'fluoride_level' => $request->fluoride_level ?? 1.0,
                'nitrate_level' => $request->nitrate_level ?? 40,
                'status' => 'active',
            ]);
        }

        return redirect()->route('users.index')->with('success', 'User created successfully');
    }

    /**
     * Show the form for editing the user
     */
    public function edit(User $user)
    {
        $roles = ['admin', 'agent', 'client'];
        $permissions = Permission::all();
        $userPermissions = $user->permissions->pluck('id')->toArray();
        
        return view('users.edit', compact('user', 'roles', 'permissions', 'userPermissions'));
    }

    /**
     * Update the user
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'role' => ['required', 'in:admin,agent,client'],
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ]);

        if ($request->filled('password')) {
            $request->validate([
                'password' => ['confirmed', Rules\Password::defaults()],
            ]);
            
            $user->update([
                'password' => Hash::make($request->password),
            ]);
        }

        return redirect()->route('users.index')->with('success', 'User updated successfully');
    }

    /**
     * Remove the user
     */
    public function destroy(User $user)
    {
        // Delete associated tank if exists
        if ($user->tank) {
            $user->tank->delete();
        }
        
        $user->delete();
        
        return redirect()->route('users.index')->with('success', 'User deleted successfully');
    }

    /**
     * Display clients created by the agent
     */
    public function agentClients()
    {
        $clients = User::where('role', 'client')
            ->where('created_by', Auth::id())
            ->get();
            
        return view('users.agent-clients', compact('clients'));
    }

    /**
     * Show form to create a client (for agents)
     */
    public function createClientForm()
    {
        return view('users.create-client');
    }

    /**
     * Store a new client (for agents)
     */
    public function storeClient(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'location' => ['required', 'string', 'max:255'],
        ]);

        $client = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'client',
            'created_by' => Auth::id(),
        ]);

        // Create tank for the client
        $client->tank()->create([
            'location' => $request->location,
            'capacity' => $request->capacity ?? 1000,
            'current_level' => $request->current_level ?? 500,
            'ph_level' => $request->ph_level ?? 7.0,
            'chloride_level' => $request->chloride_level ?? 200,
            'fluoride_level' => $request->fluoride_level ?? 1.0,
            'nitrate_level' => $request->nitrate_level ?? 40,
            'status' => 'active',
        ]);

        return redirect()->route('agent.clients')->with('success', 'Client created successfully');
    }
}