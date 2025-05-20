<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Tank;
use Illuminate\Support\Facades\Auth;

class AgentController extends Controller
{
    public function index()
    {
        return view('agent.dashboard');
    }

    public function customers()
    {
        return view('agent.customers');
    }

    public function monitoring()
    {
        $agent = Auth::user();
        $tanks = Tank::whereHas('user', function($query) use ($agent) {
            $query->where('created_by', $agent->id);
        })->get();

        return view('agent.monitoring', compact('tanks'));
    }

    public function maintenance()
    {
        return view('agent.maintenance');
    }

    public function reports()
    {
        return view('agent.reports');
    }

    public function gps()
    {
        return view('agent.gps');
    }

    public function communication()
    {
        return view('agent.communication');
    }

    public function getTankDetails($id)
    {
        $agent = Auth::user();
        $tank = Tank::whereHas('user', function($query) use ($agent) {
            $query->where('created_by', $agent->id);
        })->findOrFail($id);

        return response()->json($tank);
    }
} 