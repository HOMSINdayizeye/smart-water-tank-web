<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Tank;
use App\Models\Notification;
use App\Models\MaintenanceRequest;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $agent = Auth::user();
        
        // Get statistics
        $totalClients = User::where('role', 'client')
            ->where('created_by', $agent->id)
            ->count();
            
        $alertsSent = Notification::where('sender_id', $agent->id)
            ->count();
            
        $activeTanks = Tank::whereHas('user', function($query) use ($agent) {
                $query->where('created_by', $agent->id);
            })
            ->where('status', 'active')
            ->count();
            
        $maintenanceRequests = MaintenanceRequest::whereHas('tank.user', function($query) use ($agent) {
                $query->where('created_by', $agent->id);
            })
            ->where('status', 'pending')
            ->count();
            
        // Get recent clients
        $recentClients = User::where('role', 'client')
            ->where('created_by', $agent->id)
            ->with('tank')
            ->latest()
            ->take(5)
            ->get();
            
        // Get recent notifications
        $recentNotifications = Notification::where('receiver_id', $agent->id)
            ->with('sender')
            ->latest()
            ->take(5)
            ->get();
            
        return view('agent.dashboard', compact(
            'totalClients',
            'alertsSent',
            'activeTanks',
            'maintenanceRequests',
            'recentClients',
            'recentNotifications'
        ));
    }
    
    public function markNotificationAsRead($id)
    {
        $notification = Notification::findOrFail($id);
        
        if ($notification->receiver_id === Auth::id()) {
            $notification->update(['read_at' => now()]);
            return response()->json(['success' => true]);
        }
        
        return response()->json(['success' => false], 403);
    }
    
    public function getClientDetails($id)
    {
        $client = User::where('role', 'client')
            ->where('created_by', Auth::id())
            ->with('tank')
            ->findOrFail($id);
            
        return response()->json($client);
    }
} 