<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller {
    /**
     * Display a listing of the notifications
     */
    public function index()
    {
        $notifications = Notification::where('receiver_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('notifications.index', compact('notifications'));
    }

    /**
     * Display the specified notification
     */
    public function show(Notification $notification)
    {
        // Check if the notification belongs to the authenticated user
        if ($notification->receiver_id !== Auth::id()) {
            abort(403);
        }
        
        // Mark as read if not already
        if (!$notification->read_at) {
            $notification->update(['read_at' => now()]);
        }
        
        return view('notifications.show', compact('notification'));
    }

    /**
     * Mark the notification as read
     */
    public function markAsRead(Notification $notification)
    {
        // Check if the notification belongs to the authenticated user
        if ($notification->receiver_id !== Auth::id()) {
            abort(403);
        }
        
        $notification->update(['read_at' => now()]);
        
        return redirect()->back()->with('success', 'Notification marked as read');
    }

    /**
     * Remove the notification
     */
    public function destroy(Notification $notification)
    {
        // Check if the notification belongs to the authenticated user
        if ($notification->receiver_id !== Auth::id()) {
            abort(403);
        }
        
        $notification->delete();
        
        return redirect()->route('notifications.index')->with('success', 'Notification deleted successfully');
    }

    /**
     * Send a request notification
     */
    public function sendRequest(Request $request)
    {
        $request->validate([
            'receiver_id' => ['required', 'exists:users,id'],
            'title' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string'],
            'type' => ['required', 'string', 'in:general,maintenance,alert'],
        ]);

        Notification::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'title' => $request->title,
            'message' => $request->message,
            'type' => $request->type,
        ]);

        return redirect()->back()->with('success', 'Request sent successfully');
    }

    /**
     * Client sends request to agent
     */
    public function requestToAgent(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string'],
        ]);

        // Find the agent who created this client
        $agent = User::find(Auth::user()->created_by);
        
        if (!$agent) {
            return redirect()->back()->with('error', 'No agent found for your account.');
        }

        Notification::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $agent->id,
            'title' => $request->title,
            'message' => $request->message,
            'type' => $request->type ?? 'general',
        ]);

        return redirect()->back()->with('success', 'Request sent to your agent');
    }

    /**
     * Show the form for sending a notification to an admin.
     */
    public function showSendToAdminForm()
    {
        // Optionally, you could fetch a list of admins here to pass to the view
        // $admins = User::where('role', 'admin')->get();
        return view('agent.send-notification-to-admin');
    }

    /**
     * Agent sends request to admin
     */
    public function requestToAdmin(Request $request)
    {
        $request->validate([
            'admin_email' => ['required', 'string', 'email', 'exists:users,email'],
            'title' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string'],
        ]);

        // Find the admin user by email
        $admin = User::where('email', $request->admin_email)->first();

        // Verify the receiver is an admin
        if (!$admin || $admin->role !== 'admin') {
            return redirect()->back()->with('error', 'Invalid admin selected.');
        }

        Notification::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $admin->id,
            'title' => $request->title,
            'message' => $request->message,
            'type' => $request->type ?? 'general',
        ]);

        return redirect()->back()->with('success', 'Request sent to admin');
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        Notification::where('receiver_id', Auth::id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
        
        return redirect()->back()->with('success', 'All notifications marked as read');
    }
}