<?php

namespace App\Http\Controllers;

use App\Models\Alert;
use App\Models\Tank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AlertController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $alerts = match($user->role) {
            'admin' => Alert::with(['tank.user'])->latest()->paginate(20),
            'agent' => Alert::whereHas('tank.user', function($query) use ($user) {
                $query->where('created_by', $user->id);
            })->with(['tank.user'])->latest()->paginate(20),
            'client' => Alert::whereHas('tank', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })->with(['tank'])->latest()->paginate(20),
            default => collect(),
        };

        return view('alerts.index', compact('alerts'));
    }

    public function show(Alert $alert)
    {
        $this->authorize('view', $alert);
        
        return view('alerts.show', compact('alert'));
    }

    public function resolve(Request $request, Alert $alert)
    {
        $this->authorize('update', $alert);

        $validated = $request->validate([
            'resolution_notes' => 'required|string|max:1000'
        ]);

        $alert->update([
            'resolved_at' => now(),
            'resolved_by' => Auth::id(),
            'resolution_notes' => $validated['resolution_notes']
        ]);

        return redirect()->route('alerts.show', $alert)
            ->with('success', 'Alert resolved successfully.');
    }

    public function destroy(Alert $alert)
    {
        $this->authorize('delete', $alert);
        
        $alert->delete();

        return redirect()->route('alerts.index')
            ->with('success', 'Alert deleted successfully.');
    }

    public function getUnresolvedAlerts()
    {
        $user = Auth::user();
        
        $alerts = match($user->role) {
            'admin' => Alert::whereNull('resolved_at')->with(['tank.user'])->latest()->get(),
            'agent' => Alert::whereNull('resolved_at')
                ->whereHas('tank.user', function($query) use ($user) {
                    $query->where('created_by', $user->id);
                })->with(['tank.user'])->latest()->get(),
            'client' => Alert::whereNull('resolved_at')
                ->whereHas('tank', function($query) use ($user) {
                    $query->where('user_id', $user->id);
                })->with(['tank'])->latest()->get(),
            default => collect(),
        };

        return response()->json($alerts);
    }
} 