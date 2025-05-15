<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Tank;
use App\Models\Notification;

class DashboardController extends Controller
{
    /**
     * Display the appropriate dashboard based on user role
     */
    public function index()
    {
        $user = Auth::user();
        $unreadNotificationsCount = Notification::where('receiver_id', $user->id)->unread()->count();
        
        switch ($user->role) {
            case 'admin':
                $totalUsers = User::count();
                $totalClients = User::where('role', 'client')->count();
                $totalAgents = User::where('role', 'agent')->count();
                $activeTanks = Tank::where('status', 'active')->count();
                $alertsSent = Notification::where('sender_id', $user->id)->count();
                $maintenanceRequests = Notification::where('receiver_id', $user->id)
                    ->where('type', 'maintenance')
                    ->count();
                
                return view('admin', compact(
                    'user', 
                    'unreadNotificationsCount', 
                    'totalUsers', 
                    'totalClients', 
                    'totalAgents', 
                    'activeTanks', 
                    'alertsSent', 
                    'maintenanceRequests'
                ));
                
            case 'agent':
                $totalClients = User::where('role', 'client')
                    ->where('created_by', $user->id)
                    ->count();
                $activeTanks = Tank::whereHas('user', function ($query) use ($user) {
                    $query->where('created_by', $user->id);
                })->where('status', 'active')->count();
                $alertsSent = Notification::where('sender_id', $user->id)->count();
                $maintenanceRequests = Notification::where('receiver_id', $user->id)
                    ->where('type', 'maintenance')
                    ->count();
                
                return view('agent', compact(
                    'user', 
                    'unreadNotificationsCount', 
                    'totalClients', 
                    'activeTanks', 
                    'alertsSent', 
                    'maintenanceRequests'
                ));
                
            case 'client':
                $tank = $user->tank;
                
                return view('client', compact(
                    'user', 
                    'unreadNotificationsCount', 
                    'tank'
                ));
                
            default:
                return redirect()->route('welcome');
        }
    }
}