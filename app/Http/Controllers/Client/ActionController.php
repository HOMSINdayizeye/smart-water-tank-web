<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ActionController extends Controller
{
    public function handleAction(Request $request)
    {
        $action = $request->input('action');
        
        // Validate the action
        if (!in_array($action, ['maintenance', 'support', 'emergency'])) {
            return response()->json(['message' => 'Invalid action'], 400);
        }

        // Handle different actions
        switch ($action) {
            case 'maintenance':
                // Logic for maintenance request
                return response()->json(['message' => 'Maintenance request received']);
            
            case 'support':
                // Logic for support request
                return response()->json(['message' => 'Support request received']);
            
            case 'emergency':
                // Logic for emergency request
                return response()->json(['message' => 'Emergency request received']);
            
            default:
                return response()->json(['message' => 'Unknown action'], 400);
        }
    }
} 