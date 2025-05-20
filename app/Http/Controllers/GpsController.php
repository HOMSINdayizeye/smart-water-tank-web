<?php

namespace App\Http\Controllers;

use App\Models\GpsRoute;
use App\Models\GpsStop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class GpsController extends Controller
{
    public function index()
    {
        $routes = GpsRoute::with(['stops', 'creator'])
            ->where('created_by', auth()->id())
            ->latest()
            ->get();

        return view('agent.gps', compact('routes'));
    }

    public function getRoutes()
    {
        $routes = GpsRoute::with(['stops', 'creator'])
            ->where('created_by', auth()->id())
            ->latest()
            ->get();

        return response()->json($routes);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_location' => 'required|array',
            'end_location' => 'required|array',
            'waypoints' => 'nullable|array',
            'optimization_type' => 'required|string',
            'vehicle_type' => 'required|string',
            'stops' => 'required|array'
        ]);

        try {
            DB::beginTransaction();

            // Calculate route details
            $routeDetails = $this->calculateRouteDetails(
                $validated['start_location'],
                $validated['end_location'],
                $validated['waypoints'] ?? [],
                $validated['optimization_type'],
                $validated['vehicle_type']
            );

            // Create route
            $route = GpsRoute::create([
                'name' => $validated['name'],
                'description' => $validated['description'],
                'start_location' => $validated['start_location'],
                'end_location' => $validated['end_location'],
                'waypoints' => $validated['waypoints'] ?? [],
                'optimization_type' => $validated['optimization_type'],
                'vehicle_type' => $validated['vehicle_type'],
                'total_distance' => $routeDetails['distance'],
                'estimated_time' => $routeDetails['time'],
                'fuel_cost' => $routeDetails['fuelCost'],
                'status' => 'active',
                'created_by' => auth()->id(),
                'updated_by' => auth()->id()
            ]);

            // Create stops
            foreach ($validated['stops'] as $index => $stop) {
                GpsStop::create([
                    'route_id' => $route->id,
                    'name' => $stop['name'],
                    'address' => $stop['address'],
                    'latitude' => $stop['latitude'],
                    'longitude' => $stop['longitude'],
                    'sequence' => $index + 1,
                    'estimated_arrival' => $routeDetails['arrivalTimes'][$index] ?? null,
                    'estimated_departure' => $routeDetails['departureTimes'][$index] ?? null,
                    'status' => 'pending',
                    'notes' => $stop['notes'] ?? null
                ]);
            }

            DB::commit();

            return response()->json([
                'message' => 'Route created successfully',
                'route' => $route->load('stops')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Route creation failed: ' . $e->getMessage());
            return response()->json(['message' => 'Failed to create route'], 500);
        }
    }

    public function optimize(Request $request)
    {
        $validated = $request->validate([
            'optimization_type' => 'required|string',
            'vehicle_type' => 'required|string',
            'stops' => 'required|array'
        ]);

        try {
            // Calculate optimized route
            $routeDetails = $this->calculateRouteDetails(
                $validated['stops'][0],
                end($validated['stops']),
                array_slice($validated['stops'], 1, -1),
                $validated['optimization_type'],
                $validated['vehicle_type']
            );

            return response()->json([
                'distance' => $routeDetails['distance'],
                'time' => $routeDetails['time'],
                'fuelCost' => $routeDetails['fuelCost'],
                'stops' => $this->formatStops($validated['stops'], $routeDetails),
                'route' => $routeDetails['route']
            ]);
        } catch (\Exception $e) {
            Log::error('Route optimization failed: ' . $e->getMessage());
            return response()->json(['message' => 'Failed to optimize route'], 500);
        }
    }

    public function updateStop(Request $request, GpsStop $stop)
    {
        $validated = $request->validate([
            'status' => 'required|string',
            'notes' => 'nullable|string'
        ]);

        try {
            $stop->update([
                'status' => $validated['status'],
                'notes' => $validated['notes']
            ]);

            return response()->json([
                'message' => 'Stop updated successfully',
                'stop' => $stop
            ]);
        } catch (\Exception $e) {
            Log::error('Stop update failed: ' . $e->getMessage());
            return response()->json(['message' => 'Failed to update stop'], 500);
        }
    }

    private function calculateRouteDetails($start, $end, $waypoints, $optimizationType, $vehicleType)
    {
        // This is a placeholder for actual route calculation logic
        // In a real application, you would integrate with a mapping service API
        // such as Google Maps, Mapbox, or OpenStreetMap

        $distance = 0;
        $time = 0;
        $fuelCost = 0;
        $arrivalTimes = [];
        $departureTimes = [];
        $route = [];

        // Simulate route calculation
        $currentTime = Carbon::now();
        $speed = $this->getVehicleSpeed($vehicleType);
        $fuelEfficiency = $this->getFuelEfficiency($vehicleType);
        $fuelPrice = 1.5; // $ per liter

        // Calculate route for each segment
        $points = array_merge([$start], $waypoints, [$end]);
        for ($i = 0; $i < count($points) - 1; $i++) {
            $segmentDistance = $this->calculateDistance(
                $points[$i]['latitude'],
                $points[$i]['longitude'],
                $points[$i + 1]['latitude'],
                $points[$i + 1]['longitude']
            );

            $segmentTime = ($segmentDistance / $speed) * 60; // Convert to minutes
            $segmentFuelCost = ($segmentDistance / 100) * $fuelEfficiency * $fuelPrice;

            $distance += $segmentDistance;
            $time += $segmentTime;
            $fuelCost += $segmentFuelCost;

            // Calculate arrival and departure times
            $arrivalTimes[] = $currentTime->copy()->addMinutes($time);
            $departureTimes[] = $currentTime->copy()->addMinutes($time + 5); // 5 minutes stop time

            // Add route points
            $route[] = [
                'lat' => $points[$i]['latitude'],
                'lng' => $points[$i]['longitude']
            ];
        }

        // Add final point
        $route[] = [
            'lat' => $end['latitude'],
            'lng' => $end['longitude']
        ];

        return [
            'distance' => $distance,
            'time' => $time,
            'fuelCost' => $fuelCost,
            'arrivalTimes' => $arrivalTimes,
            'departureTimes' => $departureTimes,
            'route' => $route
        ];
    }

    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        // Haversine formula to calculate distance between two points
        $earthRadius = 6371; // km

        $latDelta = deg2rad($lat2 - $lat1);
        $lonDelta = deg2rad($lon2 - $lon1);

        $a = sin($latDelta/2) * sin($latDelta/2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($lonDelta/2) * sin($lonDelta/2);
        
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        
        return $earthRadius * $c;
    }

    private function getVehicleSpeed($vehicleType)
    {
        return match($vehicleType) {
            'car' => 60, // km/h
            'van' => 50,
            'truck' => 40,
            default => 50
        };
    }

    private function getFuelEfficiency($vehicleType)
    {
        return match($vehicleType) {
            'car' => 8, // L/100km
            'van' => 10,
            'truck' => 15,
            default => 10
        };
    }

    private function formatStops($stops, $routeDetails)
    {
        return array_map(function($stop, $index) use ($routeDetails) {
            return [
                'id' => $stop['id'] ?? null,
                'name' => $stop['name'],
                'address' => $stop['address'],
                'latitude' => $stop['latitude'],
                'longitude' => $stop['longitude'],
                'estimated_arrival' => $routeDetails['arrivalTimes'][$index] ?? null,
                'estimated_departure' => $routeDetails['departureTimes'][$index] ?? null,
                'status' => $stop['status'] ?? 'pending'
            ];
        }, $stops, array_keys($stops));
    }
} 