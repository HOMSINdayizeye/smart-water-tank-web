<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class AuthController extends Controller {
    /**
     * Show the login form
     */
    // public function showLoginForm()
    // {
    //     return view('auth.login');
    // }
    public function showLoginForm()
{
    return view('login'); // Change from 'auth.login' to 'login'
}
public function login(Request $request)
{
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
        'role' => ['required', 'in:admin,agent,client'],
    ]);

    $role = $credentials['role'];
    unset($credentials['role']);

    if (!Auth::attempt($credentials)) {
        \Log::error('Login failed for email: ' . $credentials['email']);
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput();
    }

    if (Auth::user()->role !== $role) {
        Auth::logout();
        \Log::error('Role mismatch: Expected ' . $role . ' but found ' . Auth::user()->role);
        return back()->withErrors([
            'role' => 'You do not have access as ' . $role,
        ])->withInput();
    }

    $request->session()->regenerate();
    return redirect()->intended('dashboard');
}


    /**
     * Handle login request
     */
    // public function login(Request $request)
    // {
    //     $credentials = $request->validate([
    //         'email' => ['required', 'email'],
    //         'password' => ['required'],
    //         'role' => ['required', 'in:admin,agent,client'],
    //     ]);

    //     $role = $credentials['role'];
    //     unset($credentials['role']);

    //     if (Auth::attempt($credentials)) {
    //         // Check if user has the selected role
    //         if (Auth::user()->role !== $role) {
    //             Auth::logout();
    //             return back()->withErrors([
    //                 'role' => 'You do not have access as ' . $role,
    //             ])->withInput();
    //         }

    //         $request->session()->regenerate();
    //         return redirect()->intended('dashboard');
    //     }

    //     return back()->withErrors([
    //         'email' => 'The provided credentials do not match our records.',
    //     ])->withInput();
    //     //debbgging to see what is the cause of invalid lpogin
    //     if (Auth::attempt($credentials)) {
    //         dd(Auth::user()); // See what Laravel is returning
    //     }
        
    // }

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
     * Handle logout request
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/');
    }
}