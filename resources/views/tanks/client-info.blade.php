@extends('layouts.app')

@section('content')
<div class="container mx-auto py-6">
    <h1 class="text-2xl font-semibold mb-6">Client and Tank Information</h1>

    @if(Auth::user())
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-bold mb-4">Client Information</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <div class="flex justify-between">
                        <p class="text-gray-600">Name:</p>
                        <p class="font-semibold">{{ Auth::user()->name }}</p>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between">
                        <p class="text-gray-600">Email:</p>
                        <p class="font-semibold">{{ Auth::user()->email }}</p>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between">
                        <p class="text-gray-600">User ID:</p>
                        <p class="font-semibold">{{ Auth::user()->id }}</p>
                    </div>
                </div>
                {{-- Add other client information fields here --}}
            </div>
        </div>

        @if($tank)
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-xl font-bold mb-4">Tank Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <div class="flex justify-between">
                            <p class="text-gray-600">Tank ID:</p>
                            <p class="font-semibold">{{ $tank->id }}</p>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between">
                            <p class="text-gray-600">Capacity:</p>
                            <p class="font-semibold">{{ $tank->capacity }} L</p>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between">
                            <p class="text-gray-600">Current Level:</p>
                            <p class="font-semibold">{{ $tank->current_level }} L</p>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between">
                            <p class="text-gray-600">Location:</p>
                            <p class="font-semibold">{{ $tank->location ?? 'N/A' }}</p>
                        </div>
                    </div>
                    {{-- Add other tank information fields here --}}
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-xl font-bold mb-4">Water Quality Overview</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-lg font-semibold mb-2">Current Parameters</h3>
                         <div class="space-y-2">
                            <div>
                                <p><strong class="text-gray-600">pH Level:</strong> {{ $tank->ph_level }}</p>
                            </div>
                            <div>
                                <p><strong class="text-gray-600">Chloride Level:</strong> {{ $tank->chloride_level }} mg/L</p>
                            </div>
                            <div>
                                <p><strong class="text-gray-600">Fluoride Level:</strong> {{ $tank->fluoride_level }} mg/L</p>
                            </div>
                            <div>
                                <p><strong class="text-gray-600">Nitrate Level:</strong> {{ $tank->nitrate_level }} mg/L</p>
                            </div>
                         </div>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold mb-2">Overall Status</h3>
                        @php
                            $status = 'Safe';
                            if (($tank->ph_level < 5 || $tank->ph_level > 10) ||
                                ($tank->chloride_level > 750) ||
                                ($tank->fluoride_level > 4) ||
                                ($tank->nitrate_level > 150)) {
                                $status = 'Unsafe';
                            } elseif (($tank->ph_level >= 5 && $tank->ph_level <= 6) || ($tank->ph_level >= 9 && $tank->ph_level <= 10) ||
                                      ($tank->chloride_level >= 500 && $tank->chloride_level <= 750) ||
                                      ($tank->fluoride_level >= 3 && $tank->fluoride_level <= 4) ||
                                      ($tank->nitrate_level >= 100 && $tank->nitrate_level <= 150)) {
                                $status = 'Moderate Risk';
                            }
                        @endphp

                        @if($status == 'Safe')
                            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4" role="alert">
                                <p class="font-bold">Safe</p>
                                <p>Current water quality is within safe limits.</p>
                            </div>
                        @elseif($status == 'Moderate Risk')
                            <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4" role="alert">
                                <p class="font-bold">Moderate Risk</p>
                                <p>Water quality indicates moderate risk. Further testing recommended.</p>
                            </div>
                        @else {{-- Unsafe --}}
                            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4" role="alert">
                                <p class="font-bold">Unsafe</p>
                                <p>Water quality is unsafe. Immediate action required.</p>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="mt-6 text-center text-gray-600">
                   <p>Stay in control with real-time water monitoring, proactive maintenance, and sustainable efficiency.</p>
                </div>
            </div>
        @else
            <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4" role="alert">
                <p class="font-bold">No Tank Assigned</p>
                <p>No tank is currently assigned to your account. Please contact your agent or administrator.</p>
            </div>
        @endif

    @else
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4" role="alert">
            <p class="font-bold">User Information Not Available</p>
            <p>User information not available. Please log in.</p>
        </div>
    @endif
</div>
@endsection 