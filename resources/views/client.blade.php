@extends('layouts.app')

@section('content')
<div class="flex h-screen bg-gray-100 font-sans antialiased">
    <!-- Left Sidebar -->
    <div class="w-64 bg-gray-200 text-gray-800 flex flex-col shadow-lg border-r border-gray-300">
        <div class="p-4 text-2xl font-bold text-center border-b border-gray-300 bg-blue-600 text-white">
            <i class="fas fa-tint mr-2 text-blue-200"></i> Smart Tank
        </div>
        <div class="p-4 text-center border-b border-gray-300">
            <div class="w-20 h-20 rounded-full bg-gray-300 flex items-center justify-center mx-auto mb-3">
                <i class="fas fa-user text-gray-600 text-4xl"></i>
            </div>
            <h3 class="font-semibold text-lg text-gray-800">CLIENT PROFILE</h3>
        </div>
        <nav class="flex-1 px-4 py-4 space-y-2 text-gray-700">
            <a href="#" class="flex items-center py-2 px-3 rounded transition duration-200 hover:bg-gray-300"><i class="fas fa-tachometer-alt mr-3 text-lg"></i> Dashboard</a>
            <a href="#" class="flex items-center py-2 px-3 rounded transition duration-200 hover:bg-gray-300"><i class="fas fa-chart-bar mr-3 text-lg"></i> Real-Time Monitoring</a>
            <a href="#" class="flex items-center py-2 px-3 rounded transition duration-200 hover:bg-gray-300"><i class="fas fa-bell mr-3 text-lg"></i> Smart Alerts & Notifications</a>
            <a href="#" class="flex items-center py-2 px-3 rounded transition duration-200 hover:bg-gray-300"><i class="fas fa-brain mr-3 text-lg"></i> AI-Powered Predictions</a>
            <a href="#" class="flex items-center py-2 px-3 rounded transition duration-200 hover:bg-gray-300 text-blue-600 font-semibold"><i class="fas fa-lightbulb mr-3 text-lg"></i> Usage Recommendations <i class="fas fa-check-circle text-blue-600 ml-auto"></i></a>
            <a href="#" class="flex items-center py-2 px-3 rounded transition duration-200 hover:bg-gray-300"><i class="fas fa-calendar-alt mr-3 text-lg"></i> Maintenance Scheduling</a>
            <a href="#" class="flex items-center py-2 px-3 rounded transition duration-200 hover:bg-gray-300"><i class="fas fa-file-alt mr-3 text-lg"></i> Reports</a>
        </nav>
    </div>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <!-- Top Bar -->
        <header class="flex justify-between items-center p-4 bg-blue-600 text-white shadow-md">
            <div class="text-2xl font-bold"><i class="fas fa-home mr-3 text-blue-200"></i> Client Dashboard</div>
            <div class="flex items-center space-x-4">
                <i class="fas fa-wifi text-blue-200 text-xl"></i>
                <i class="fas fa-signal text-blue-200 text-xl"></i>
                <i class="fas fa-battery-three-quarters text-blue-200 text-xl"></i>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
            <div class="flex flex-wrap -mx-4">
                <!-- Main Column -->
                <div class="w-full lg:w-2/3 mx-auto px-4">
                    @if($tank)
                    <!-- Tank Info -->
                    <div class="bg-white p-6 rounded-lg shadow-md mb-8">
                        <h2 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">Tank Information</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-y-4 gap-x-6 text-gray-700 text-sm">
                            <div>
                                <p class="text-gray-600">Location:</p>
                                <p class="font-semibold text-gray-800">{{ $tank->location }}</p>
                            </div>
                            <div>
                                <p class="text-gray-600">Status:</p>
                                <p class="font-semibold text-gray-800">
                                    <span class="px-2 py-1 rounded-full text-xs font-semibold
                                        {{ $tank->status === 'active' ? 'bg-green-100 text-green-800' :
                                           ($tank->status === 'maintenance' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                        {{ ucfirst($tank->status) }}
                                    </span>
                                </p>
                            </div>
                            <div>
                                <p class="text-gray-600">Capacity:</p>
                                <p class="font-semibold text-gray-800">{{ $tank->capacity }} liters</p>
                            </div>
                            <div>
                                <p class="text-gray-600">Current Level:</p>
                                <p class="font-semibold text-gray-800">{{ $tank->current_level }} liters ({{ round(($tank->current_level / $tank->capacity) * 100) }}%)</p>
                            </div>
                            <div>
                                <p class="text-gray-600">Last Maintenance:</p>
                                <p class="font-semibold text-gray-800">{{ $tank->last_maintenance ? $tank->last_maintenance->format('M d, Y') : 'Not available' }}</p>
                            </div>
                            <div>
                                <p class="text-gray-600">Water Quality:</p>
                                <p class="font-semibold text-gray-800">
                                    <span class="px-2 py-1 rounded-full text-xs font-semibold
                                        {{ $tank->water_quality === 'safe' ? 'bg-green-100 text-green-800' :
                                           ($tank->water_quality === 'moderate' ? 'bg-yellow-100 text-yellow-800' :
                                           ($tank->water_quality === 'moderate_risk' ? 'bg-orange-100 text-orange-800' : 'bg-red-100 text-red-800')) }}">
                                        {{ ucfirst(str_replace('_', ' ', $tank->water_quality)) }}
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Water Quality Parameters -->
                    <div class="bg-white p-6 rounded-lg shadow-md mb-8">
                        <h2 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">Water Quality Parameters</h2>
                        <div class="space-y-4 text-gray-700">
                            @php
                                $params = [
                                    'pH Level' => ['value' => $tank->ph_level, 'normal' => $tank->ph_level >= 6.5 && $tank->ph_level <= 8.5, 'max' => 14],
                                    'Chloride' => ['value' => $tank->chloride_level, 'normal' => $tank->chloride_level <= 250, 'max' => 750, 'unit' => 'mg/L'],
                                    'Fluoride' => ['value' => $tank->fluoride_level, 'normal' => $tank->fluoride_level <= 1.5, 'max' => 4, 'unit' => 'mg/L'],
                                    'Nitrate' => ['value' => $tank->nitrate_level, 'normal' => $tank->nitrate_level <= 50, 'max' => 150, 'unit' => 'mg/L'],
                                ];
                            @endphp

                            @foreach($params as $label => $data)
                            <div>
                                <div class="flex justify-between mb-1 text-sm">
                                    <span>{{ $label }}: {{ $data['value'] }} {{ $data['unit'] ?? '' }}</span>
                                    <span class="font-semibold {{ $data['normal'] ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $data['normal'] ? 'Normal' : 'High' }}
                                    </span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2.5">
                                    <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ min(($data['value'] / $data['max']) * 100, 100) }}%"></div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Maintenance Request Form -->
                    <div class="bg-white p-6 rounded-lg shadow-md mb-8">
                        <h3 class="font-bold text-gray-800 mb-4 border-b pb-2">Request Maintenance or Support</h3>
                        <form action="{{ route('client.request.agent') }}" method="POST">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <input type="text" name="title" placeholder="Request Title" class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-700" required>
                                </div>
                                <div>
                                    <select name="type" class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-700" required>
                                        <option value="general">General Inquiry</option>
                                        <option value="maintenance">Maintenance Request</option>
                                        <option value="alert">Report Issue</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-4">
                                <textarea name="message" rows="4" placeholder="Describe your request or issue..." class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-700" required></textarea>
                            </div>
                            <div class="text-right">
                                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-md transition duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">Send Request</button>
                            </div>
                        </form>
                    </div>
                    @else
                    <!-- No Tank Alert -->
                    <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-800 p-4 rounded-md mb-8" role="alert">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 mr-3">
                                <i class="fas fa-exclamation-triangle text-yellow-500"></i>
                            </div>
                            <div>
                                <p class="text-sm">
                                    No tank is currently assigned to your account. Please contact your agent or administrator.
                                </p>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </main>
    </div>

    {{-- Fixed top-left Home button --}}
    <div style="position: fixed; top: 20px; left: 20px; z-index: 1000;">
        <a href="{{ route('dashboard') }}" style="display: flex; items-center justify-center; width: 40px; height: 40px; background-color: #0284c7; border-radius: 50%;">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ffffff" style="width: 24px; height: 24px;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.736-8.736a2.25 2.25 0 013.182 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
            </svg>
        </a>
    </div>

    {{-- Fixed bottom-left logout button --}}
    <div style="position: fixed; bottom: 20px; left: 20px;">
        <form action="{{ route('logout') }}" method="POST" class="inline">
            @csrf
            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-md">Logout</button>
        </form>
    </div>
</div>
<form method="GET" action="{{ route('users.index') }}" class="mb-4 flex items-center">
    <select name="role" class="border rounded px-3 py-2 mr-2">
        <option value="">All Roles</option>
        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
        <option value="agent" {{ request('role') == 'agent' ? 'selected' : '' }}>Agent</option>
        <option value="client" {{ request('role') == 'client' ? 'selected' : '' }}>Client</option>
    </select>
    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded">Filter</button>
</form>
@endsection
