<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientController extends Controller
{
    /**
     * Display a listing of the customers.
     */
    public function index()
    {
        $customers = Customer::with(['tanks', 'maintenanceRecords', 'alerts'])
            ->where('created_by', Auth::id())
            ->latest()
            ->paginate(10);

        return view('agent.customers.index', compact('customers'));
    }

    /**
     * Show the form for creating a new customer.
     */
    public function create()
    {
        return view('agent.customers.create');
    }

    /**
     * Store a newly created customer in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'status' => 'required|in:active,inactive,pending'
        ]);

        $validated['created_by'] = Auth::id();
        $validated['updated_by'] = Auth::id();

        Customer::create($validated);

        return redirect()->route('agent.customers')
            ->with('success', 'Customer created successfully.');
    }

    /**
     * Display the specified customer.
     */
    public function show(Customer $customer)
    {
        $this->authorize('view', $customer);

        $customer->load(['tanks', 'maintenanceRecords', 'alerts']);
        
        return view('agent.customers.show', compact('customer'));
    }

    /**
     * Show the form for editing the specified customer.
     */
    public function edit(Customer $customer)
    {
        $this->authorize('update', $customer);

        return view('agent.customers.edit', compact('customer'));
    }

    /**
     * Update the specified customer in storage.
     */
    public function update(Request $request, Customer $customer)
    {
        $this->authorize('update', $customer);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email,' . $customer->id,
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'status' => 'required|in:active,inactive,pending'
        ]);

        $validated['updated_by'] = Auth::id();

        $customer->update($validated);

        return redirect()->route('agent.customers')
            ->with('success', 'Customer updated successfully.');
    }

    /**
     * Remove the specified customer from storage.
     */
    public function destroy(Customer $customer)
    {
        $this->authorize('delete', $customer);

        $customer->delete();

        return redirect()->route('agent.customers')
            ->with('success', 'Customer deleted successfully.');
    }

    /**
     * Get customer statistics for the dashboard.
     */
    public function statistics()
    {
        $totalCustomers = Customer::where('created_by', Auth::id())->count();
        $activeCustomers = Customer::where('created_by', Auth::id())->where('status', 'active')->count();
        $pendingCustomers = Customer::where('created_by', Auth::id())->where('status', 'pending')->count();
        $inactiveCustomers = Customer::where('created_by', Auth::id())->where('status', 'inactive')->count();

        return response()->json([
            'total' => $totalCustomers,
            'active' => $activeCustomers,
            'pending' => $pendingCustomers,
            'inactive' => $inactiveCustomers
        ]);
    }
} 