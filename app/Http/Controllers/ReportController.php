<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Tank;
use App\Models\Maintenance;
use App\Models\Alert;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->input('type', 'overview');
        $startDate = $request->input('start_date', Carbon::now()->subDays(30));
        $endDate = $request->input('end_date', Carbon::now());

        $data = $this->generateReport($type, $startDate, $endDate);

        return response()->json($data);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|string',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        try {
            DB::beginTransaction();

            $data = $this->generateReport(
                $validated['type'],
                $validated['start_date'],
                $validated['end_date']
            );

            $report = Report::create([
                'type' => $validated['type'],
                'title' => $validated['title'],
                'description' => $validated['description'],
                'data' => $data,
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'],
                'created_by' => auth()->id(),
                'updated_by' => auth()->id(),
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Report generated successfully',
                'report' => $report
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Report generation failed: ' . $e->getMessage());
            return response()->json(['message' => 'Failed to generate report'], 500);
        }
    }

    public function show(Report $report)
    {
        return response()->json($report);
    }

    public function export(Request $request)
    {
        $type = $request->input('type', 'overview');
        $startDate = $request->input('start_date', Carbon::now()->subDays(30));
        $endDate = $request->input('end_date', Carbon::now());

        $data = $this->generateReport($type, $startDate, $endDate);
        
        // Generate CSV content
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="report.csv"',
        ];

        $callback = function() use ($data, $type) {
            $file = fopen('php://output', 'w');
            
            // Write headers based on report type
            switch ($type) {
                case 'overview':
                    fputcsv($file, ['Metric', 'Value', 'Trend']);
                    foreach ($data['metrics'] as $metric) {
                        fputcsv($file, [$metric['label'], $metric['value'], $metric['trend']]);
                    }
                    break;
                case 'tank_performance':
                    fputcsv($file, ['Tank ID', 'Name', 'Location', 'Water Usage', 'Efficiency']);
                    foreach ($data['tanks'] as $tank) {
                        fputcsv($file, [
                            $tank['id'],
                            $tank['name'],
                            $tank['location'],
                            $tank['water_usage'],
                            $tank['efficiency']
                        ]);
                    }
                    break;
                // Add more cases for other report types
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function generateReport($type, $startDate, $endDate)
    {
        switch ($type) {
            case 'overview':
                return $this->generateOverviewReport($startDate, $endDate);
            case 'tank_performance':
                return $this->generateTankPerformanceReport($startDate, $endDate);
            case 'maintenance':
                return $this->generateMaintenanceReport($startDate, $endDate);
            case 'alerts':
                return $this->generateAlertReport($startDate, $endDate);
            case 'customer':
                return $this->generateCustomerReport($startDate, $endDate);
            default:
                throw new \InvalidArgumentException('Invalid report type');
        }
    }

    private function generateOverviewReport($startDate, $endDate)
    {
        $totalWaterUsage = Tank::whereBetween('created_at', [$startDate, $endDate])
            ->sum('water_usage');
        
        $totalAlerts = Alert::whereBetween('created_at', [$startDate, $endDate])
            ->count();
        
        $maintenanceTasks = Maintenance::whereBetween('created_at', [$startDate, $endDate])
            ->count();
        
        $activeCustomers = Customer::where('status', 'active')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        return [
            'metrics' => [
                [
                    'label' => 'Total Water Usage',
                    'value' => number_format($totalWaterUsage) . ' L',
                    'trend' => $this->calculateTrend($totalWaterUsage, $startDate, $endDate)
                ],
                [
                    'label' => 'Total Alerts',
                    'value' => $totalAlerts,
                    'trend' => $this->calculateTrend($totalAlerts, $startDate, $endDate)
                ],
                [
                    'label' => 'Maintenance Tasks',
                    'value' => $maintenanceTasks,
                    'trend' => $this->calculateTrend($maintenanceTasks, $startDate, $endDate)
                ],
                [
                    'label' => 'Active Customers',
                    'value' => $activeCustomers,
                    'trend' => $this->calculateTrend($activeCustomers, $startDate, $endDate)
                ]
            ]
        ];
    }

    private function generateTankPerformanceReport($startDate, $endDate)
    {
        $tanks = Tank::with(['customer', 'maintenanceRecords', 'alerts'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get()
            ->map(function ($tank) {
                return [
                    'id' => $tank->id,
                    'name' => $tank->name,
                    'location' => $tank->location,
                    'water_usage' => number_format($tank->water_usage) . ' L',
                    'efficiency' => $this->calculateEfficiency($tank)
                ];
            });

        return [
            'tanks' => $tanks
        ];
    }

    private function generateMaintenanceReport($startDate, $endDate)
    {
        $maintenance = Maintenance::with(['tank', 'creator'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get()
            ->groupBy('status')
            ->map(function ($tasks) {
                return $tasks->map(function ($task) {
                    return [
                        'id' => $task->id,
                        'title' => $task->title,
                        'tank' => $task->tank->name,
                        'due_date' => $task->due_date->format('M d, Y'),
                        'priority' => $task->priority,
                        'status' => $task->status
                    ];
                });
            });

        return [
            'maintenance' => $maintenance
        ];
    }

    private function generateAlertReport($startDate, $endDate)
    {
        $alerts = Alert::with(['tank'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get()
            ->groupBy('type')
            ->map(function ($alerts) {
                return $alerts->map(function ($alert) {
                    return [
                        'id' => $alert->id,
                        'message' => $alert->message,
                        'tank' => $alert->tank->name,
                        'created_at' => $alert->created_at->format('M d, Y H:i'),
                        'status' => $alert->status
                    ];
                });
            });

        return [
            'alerts' => $alerts
        ];
    }

    private function generateCustomerReport($startDate, $endDate)
    {
        $customers = Customer::with(['tanks'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get()
            ->map(function ($customer) {
                return [
                    'id' => $customer->id,
                    'name' => $customer->name,
                    'email' => $customer->email,
                    'tanks_count' => $customer->tanks->count(),
                    'status' => $customer->status
                ];
            });

        return [
            'customers' => $customers
        ];
    }

    private function calculateTrend($currentValue, $startDate, $endDate)
    {
        $previousStartDate = Carbon::parse($startDate)->subDays(30);
        $previousEndDate = Carbon::parse($endDate)->subDays(30);

        $previousValue = $this->getPreviousValue($currentValue, $previousStartDate, $previousEndDate);

        if ($previousValue === 0) {
            return 0;
        }

        return (($currentValue - $previousValue) / $previousValue) * 100;
    }

    private function calculateEfficiency($tank)
    {
        $totalCapacity = $tank->capacity;
        $currentLevel = $tank->current_level;
        $maintenanceCount = $tank->maintenanceRecords->count();
        $alertCount = $tank->alerts->count();

        // Simple efficiency calculation based on water level and maintenance/alert frequency
        $efficiency = ($currentLevel / $totalCapacity) * 100;
        $efficiency -= ($maintenanceCount * 5); // Reduce efficiency for each maintenance
        $efficiency -= ($alertCount * 10); // Reduce efficiency for each alert

        return max(0, min(100, $efficiency)) . '%';
    }

    private function getPreviousValue($currentValue, $startDate, $endDate)
    {
        // This is a placeholder. Implement actual logic to get previous period's value
        return $currentValue * 0.9; // Example: 90% of current value
    }
} 