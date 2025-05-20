<?php

namespace App\Http\Controllers;

use App\Models\MaintenanceRecord;
use App\Models\Tank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MaintenanceController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $maintenanceRecords = match($user->role) {
            'admin' => MaintenanceRecord::with(['tank.user'])->latest()->paginate(20),
            'agent' => MaintenanceRecord::whereHas('tank.user', function($query) use ($user) {
                $query->where('created_by', $user->id);
            })->with(['tank.user'])->latest()->paginate(20),
            'client' => MaintenanceRecord::whereHas('tank', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })->with(['tank'])->latest()->paginate(20),
            default => collect(),
        };

        return view('maintenance.index', compact('maintenanceRecords'));
    }

    public function show(MaintenanceRecord $maintenanceRecord)
    {
        $this->authorize('view', $maintenanceRecord);
        
        return view('maintenance.show', compact('maintenanceRecord'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tank_id' => 'required|exists:tanks,id',
            'type' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'scheduled_at' => 'required|date|after:now',
            'notes' => 'nullable|string|max:1000'
        ]);

        $maintenanceRecord = MaintenanceRecord::create([
            'user_id' => Auth::id(),
            'status' => 'scheduled',
            ...$validated
        ]);

        return redirect()->route('maintenance.show', $maintenanceRecord)
            ->with('success', 'Maintenance record created successfully.');
    }

    public function update(Request $request, MaintenanceRecord $maintenanceRecord)
    {
        $this->authorize('update', $maintenanceRecord);

        $validated = $request->validate([
            'type' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'status' => 'required|in:scheduled,in_progress,completed,cancelled',
            'scheduled_at' => 'required|date',
            'completed_at' => 'nullable|date',
            'notes' => 'nullable|string|max:1000'
        ]);

        $maintenanceRecord->update($validated);

        return redirect()->route('maintenance.show', $maintenanceRecord)
            ->with('success', 'Maintenance record updated successfully.');
    }

    public function destroy(MaintenanceRecord $maintenanceRecord)
    {
        $this->authorize('delete', $maintenanceRecord);
        
        $maintenanceRecord->delete();

        return redirect()->route('maintenance.index')
            ->with('success', 'Maintenance record deleted successfully.');
    }

    public function calendar()
    {
        $user = Auth::user();
        
        $maintenanceRecords = match($user->role) {
            'admin' => MaintenanceRecord::with(['tank.user'])->get(),
            'agent' => MaintenanceRecord::whereHas('tank.user', function($query) use ($user) {
                $query->where('created_by', $user->id);
            })->with(['tank.user'])->get(),
            'client' => MaintenanceRecord::whereHas('tank', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })->with(['tank'])->get(),
            default => collect(),
        };

        return view('maintenance.calendar', compact('maintenanceRecords'));
    }

    public function search(Request $request)
    {
        $query = $request->get('query');
        $user = Auth::user();
        
        $maintenanceRecords = match($user->role) {
            'admin' => MaintenanceRecord::where('type', 'like', "%{$query}%")
                ->orWhere('description', 'like', "%{$query}%")
                ->with(['tank.user'])
                ->latest()
                ->paginate(20),
            'agent' => MaintenanceRecord::whereHas('tank.user', function($q) use ($user) {
                $q->where('created_by', $user->id);
            })
            ->where(function($q) use ($query) {
                $q->where('type', 'like', "%{$query}%")
                    ->orWhere('description', 'like', "%{$query}%");
            })
            ->with(['tank.user'])
            ->latest()
            ->paginate(20),
            'client' => MaintenanceRecord::whereHas('tank', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->where(function($q) use ($query) {
                $q->where('type', 'like', "%{$query}%")
                    ->orWhere('description', 'like', "%{$query}%");
            })
            ->with(['tank'])
            ->latest()
            ->paginate(20),
            default => collect(),
        };

        return view('maintenance.index', compact('maintenanceRecords'));
    }

    public function getTanks()
    {
        $user = Auth::user();
        
        $tanks = match($user->role) {
            'admin' => Tank::all(),
            'agent' => Tank::whereHas('user', function($query) use ($user) {
                $query->where('created_by', $user->id);
            })->get(),
            'client' => Tank::where('user_id', $user->id)->get(),
            default => collect(),
        };

        return response()->json($tanks);
    }
} 