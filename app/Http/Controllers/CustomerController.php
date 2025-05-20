<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    /**
     * Display a listing of the customers.
     */
    public function index()
    {
        $customers = Customer::with(['tanks', 'creator', 'updater'])
            ->withCount('tanks')
            ->latest()
            ->paginate(10);

        return view('agent.customers', compact('customers'));
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
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'status' => 'required|in:active,inactive,pending'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $customer = Customer::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'status' => $request->status,
                'created_by' => Auth::id(),
                'updated_by' => Auth::id()
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Customer created successfully',
                'customer' => $customer
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error creating customer: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified customer.
     */
    public function show(Customer $customer)
    {
        $customer->load(['tanks', 'maintenanceRecords', 'alerts', 'creator', 'updater']);
        
        return response()->json([
            'success' => true,
            'customer' => $customer,
            'tanks' => $customer->tanks,
            'recent_maintenance' => $customer->recent_maintenance,
            'recent_alerts' => $customer->recent_alerts
        ]);
    }

    /**
     * Show the form for editing the specified customer.
     */
    public function edit(Customer $customer)
    {
        return view('agent.customers.edit', compact('customer'));
    }

    /**
     * Update the specified customer in storage.
     */
    public function update(Request $request, Customer $customer)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email,' . $customer->id,
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'status' => 'required|in:active,inactive,pending'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $customer->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'status' => $request->status,
                'updated_by' => Auth::id()
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Customer updated successfully',
                'customer' => $customer
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error updating customer: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified customer from storage.
     */
    public function destroy(Customer $customer)
    {
        try {
            DB::beginTransaction();

            // Delete associated records
            $customer->tanks()->delete();
            $customer->maintenanceRecords()->delete();
            $customer->alerts()->delete();

            // Delete the customer
            $customer->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Customer deleted successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error deleting customer: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export customers to CSV.
     */
    public function export()
    {
        $customers = Customer::with(['tanks', 'creator'])
            ->withCount('tanks')
            ->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="customers.csv"',
        ];

        $callback = function() use ($customers) {
            $file = fopen('php://output', 'w');
            
            // Add headers
            fputcsv($file, [
                'ID',
                'Name',
                'Email',
                'Phone',
                'Address',
                'Status',
                'Tanks Count',
                'Created At',
                'Created By'
            ]);

            // Add data
            foreach ($customers as $customer) {
                fputcsv($file, [
                    $customer->id,
                    $customer->name,
                    $customer->email,
                    $customer->phone,
                    $customer->address,
                    $customer->status,
                    $customer->tanks_count,
                    $customer->created_at,
                    $customer->creator->name ?? 'N/A'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Search customers.
     */
    public function search(Request $request)
    {
        $query = $request->get('query');
        $status = $request->get('status');

        $customers = Customer::with(['tanks', 'creator'])
            ->withCount('tanks')
            ->when($query, function($q) use ($query) {
                return $q->where(function($q) use ($query) {
                    $q->where('name', 'like', "%{$query}%")
                      ->orWhere('email', 'like', "%{$query}%")
                      ->orWhere('phone', 'like', "%{$query}%");
                });
            })
            ->when($status && $status !== 'all', function($q) use ($status) {
                return $q->where('status', $status);
            })
            ->latest()
            ->paginate(10);

        return response()->json([
            'success' => true,
            'customers' => $customers
        ]);
    }
} 