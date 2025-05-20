<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Request as ClientRequest;

class RequestController extends Controller
{
    public function store(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|string|in:maintenance,support,emergency'
        ]);

        // Create the request
        $clientRequest = ClientRequest::create([
            'user_id' => auth()->id(),
            'title' => $validated['title'],
            'description' => $validated['description'],
            'type' => $validated['type'],
            'status' => 'pending'
        ]);

        return response()->json([
            'message' => 'Request created successfully',
            'request' => $clientRequest
        ], 201);
    }
} 