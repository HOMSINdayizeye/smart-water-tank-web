<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PermissionController extends Controller
{
    public function index()
    {
        $agents = User::where('role', 'agent')->get();
        $permissions = Permission::all();
        
        return view('agent.permissions', compact('agents', 'permissions'));
    }

    public function assign(Request $request)
    {
        $request->validate([
            'agent_id' => 'required|exists:users,id',
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,id'
        ]);

        try {
            DB::beginTransaction();

            $agent = User::findOrFail($request->agent_id);
            
            // Sync permissions (this will remove all existing permissions and add the new ones)
            $agent->syncPermissions($request->permissions);

            DB::commit();

            return redirect()->route('agent.permissions.index')
                ->with('success', 'Permissions assigned successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('agent.permissions.index')
                ->with('error', 'Failed to assign permissions. Please try again.');
        }
    }

    public function getAgentPermissions($agentId)
    {
        $agent = User::findOrFail($agentId);
        $permissions = $agent->getAllPermissions();
        
        return response()->json($permissions);
    }

    public function revokeAllPermissions($agentId)
    {
        try {
            DB::beginTransaction();

            $agent = User::findOrFail($agentId);
            $agent->syncPermissions([]); // Remove all permissions

            DB::commit();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Failed to revoke permissions'], 500);
        }
    }
} 