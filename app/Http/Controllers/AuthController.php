<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use App\Models\Tank;

class AuthController extends Controller {
    /**
     * Show the login form
     */
    public function showLoginForm()
    {
        return view('login');
    }

    /**
     * Handle a login request
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
            'role' => ['required', 'in:admin,agent,client'],
        ]);

        // Attempt to log the user in
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();

            // Check if user role matches the selected role
            if ($user->role !== $request->role) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return back()->withErrors([
                    'email' => 'The provided credentials do not match our records or the selected role.',
                ])->onlyInput('email');
            }

            // Redirect to intended page or dashboard based on role
            switch ($user->role) {
                case 'admin':
                    return redirect()->intended(route('dashboard'));
                case 'agent':
                    return redirect()->intended(route('dashboard'));
                case 'client':
                    return redirect()->intended(route('dashboard'));
                default:
                    Auth::logout();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();
                    return redirect('/')->with('error', 'Your account has an invalid role.');
            }
        }

        // If login attempt fails
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Show the registration form
     */
    public function showRegistrationForm()
    {
        // Only admin can see all roles, agent can only create clients
        $roles = Auth::user()->role === 'admin' ? ['agent', 'client'] : ['client'];
        
        return view('auth.register', compact('roles'));
    }

    /**
     * Handle registration request
     */
    public function register(Request $request)
    {
        $user = Auth::user();
        
        // Validate based on user role
        if ($user->role === 'admin') {
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
                'role' => ['required', 'in:agent,client'],
            ]);
        } else {
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
                'role' => ['required', 'in:client'],
            ]);
        }

        // Create the user
        $newUser = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'created_by' => $user->id,
        ]);

        // If creating a client, also create a tank
        if ($request->role === 'client') {
            $newUser->tank()->create([
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
     * Log the user out of the application
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}