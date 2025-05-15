<?php

namespace App\Http\Controllers;

use App\Models\Tank;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TankController extends Controller {
    /**
     * Display a listing of the tanks
     */
    public function index()
    {
        $tanks = Tank::with('user')->get();
        return view('tanks.index', compact('tanks'));
    }

    /**
     * Show the form for creating a new tank
     */
    public function create()
    {
        $clients = User::where('role', 'client')->get();
        return view('tanks.create', compact('clients'));
    }

    /**
     * Store a newly created tank
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'location' => ['required', 'string', 'max:255'],
            'capacity' => ['required', 'numeric', 'min:1'],
            'current_level' => ['required', 'numeric', 'min:0'],
            'ph_level' => ['required', 'numeric', 'min:0', 'max:14'],
            'chloride_level' => ['required', 'numeric', 'min:0'],
            'fluoride_level' => ['required', 'numeric', 'min:0'],
            'nitrate_level' => ['required', 'numeric', 'min:0'],
        ]);

        // Check if user already has a tank
        $existingTank = Tank::where('user_id', $request->user_id)->first();
        if ($existingTank) {
            return redirect()->back()->withErrors(['user_id' => 'This client already has a tank assigned.']);
        }

        Tank::create([
            'user_id' => $request->user_id,
            'location' => $request->location,
            'capacity' => $request->capacity,
            'current_level' => $request->current_level,
            'ph_level' => $request->ph_level,
            'chloride_level' => $request->chloride_level,
            'fluoride_level' => $request->fluoride_level,
            'nitrate_level' => $request->nitrate_level,
            'status' => 'active',
        ]);

        return redirect()->route('tanks.index')->with('success', 'Tank created successfully');
    }

    /**
     * Display the specified tank
     */
    public function show(Tank $tank)
    {
        return view('tanks.show', compact('tank'));
    }

    /**
     * Show the form for editing the tank
     */
    public function edit(Tank $tank)
    {
        return view('tanks.edit', compact('tank'));
    }

    /**
     * Update the tank
     */
    public function update(Request $request, Tank $tank)
    {
        $request->validate([
            'location' => ['required', 'string', 'max:255'],
            'capacity' => ['required', 'numeric', 'min:1'],
            'current_level' => ['required', 'numeric', 'min:0'],
            'ph_level' => ['required', 'numeric', 'min:0', 'max:14'],
            'chloride_level' => ['required', 'numeric', 'min:0'],
            'fluoride_level' => ['required', 'numeric', 'min:0'],
            'nitrate_level' => ['required', 'numeric', 'min:0'],
            'status' => ['required', 'in:active,inactive,maintenance'],
        ]);

        $tank->update($request->all());

        return redirect()->route('tanks.index')->with('success', 'Tank updated successfully');
    }

    /**
     * Remove the tank
     */
    public function destroy(Tank $tank)
    {
        $tank->delete();
        
        return redirect()->route('tanks.index')->with('success', 'Tank deleted successfully');
    }

    /**
     * Display tank information for client
     */
    public function clientTankInfo()
    {
        $tank = Auth::user()->tank;
        
        if (!$tank) {
            return redirect()->route('dashboard')->with('error', 'No tank assigned to your account.');
        }
        
        return view('tanks.client-info', compact('tank'));
    }

    /**
     * Display tank monitoring for agent
     */
    public function monitoring()
    {
        $tanks = Tank::whereHas('user', function ($query) {
            $query->where('created_by', Auth::id());
        })->get();
        
        return view('tanks.monitoring', compact('tanks'));
    }
}