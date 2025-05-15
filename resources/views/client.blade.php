@extends('layouts.app')

@section('content')
<div class="p-4">
    <h1 class="text-2xl font-bold mb-4">Client Dashboard</h1>
    
    @if($tank)
    <div class="bg-white p-6 rounded-lg shadow-md mb-6">
        <div class="flex flex-col md:flex-row gap-6">
            <div class="flex-1">
                <h2 class="text-xl font-bold mb-4">Tank Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-gray-600">Location:</p>
                        <p class="font-semibold">{{ $tank->location }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Status:</p>
                        <p class="font-semibold">
                            <span class="px-2 py-1 rounded-full text-sm
                                {{ $tank->status === 'active' ? 'bg-green-100 text-green-800' : 
                                   ($tank->status === 'maintenance' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                {{ ucfirst($tank->status) }}
                            </span>
                        </p>
                    </div>
                    <div>
                        <p class="text-gray-600">Capacity:</p>
                        <p class="font-semibold">{{ $tank->capacity }} liters</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Current Level:</p>
                        <p class="font-semibold">{{ $tank->current_level }} liters ({{ round(($tank->current_level / $tank->capacity) * 100) }}%)</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Last Maintenance:</p>
                        <p class="font-semibold">{{ $tank->last_maintenance ? $tank->last_maintenance->format('M d, Y') : 'Not available' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Water Quality:</p>
                        <p class="font-semibold">
                            <span class="px-2 py-1 rounded-full text-sm
                                {{ $tank->water_quality === 'safe' ? 'bg-green-100 text-green-800' : 
                                   ($tank->water_quality === 'moderate' ? 'bg-yellow-100 text-yellow-800' : 
                                   ($tank->water_quality === 'moderate_risk' ? 'bg-orange-100 text-orange-800' : 'bg-red-100 text-red-800')) }}">
                                {{ ucfirst(str_replace('_', ' ', $tank->water_quality)) }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="flex-1">
                <h2 class="text-xl font-bold mb-4">Water Quality Parameters</h2>
                <div class="space-y-4">
                    <div>
                        <div class="flex justify-between mb-1">
                            <span>pH Level: {{ $tank->ph_level }}</span>
                            <span class="text-sm {{ $tank->ph_level >= 6.5 && $tank->ph_level <= 8.5 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $tank->ph_level >= 6.5 && $tank->ph_level <= 8.5 ? 'Normal' : 'Abnormal' }}
                            </span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ min(($tank->ph_level / 14) * 100, 100) }}%"></div>
                        </div>
                    </div>
                    
                    <div>
                        <div class="flex justify-between mb-1">
                            <span>Chloride: {{ $tank->chloride_level }} mg/L</span>
                            <span class="text-sm {{ $tank->chloride_level <= 250 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $tank->chloride_level <= 250 ? 'Normal' : 'High' }}
                            </span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ min(($tank->chloride_level / 750) * 100, 100) }}%"></div>
                        </div>
                    </div>
                    
                    <div>
                        <div class="flex justify-between mb-1">
                            <span>Fluoride: {{ $tank->fluoride_level }} mg/L</span>
                            <span class="text-sm {{ $tank->fluoride_level <= 1.5 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $tank->fluoride_level <= 1.5 ? 'Normal' : 'High' }}
                            </span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ min(($tank->fluoride_level / 4) * 100, 100) }}%"></div>
                        </div>
                    </div>
                    
                    <div>
                        <div class="flex justify-between mb-1">
                            <span>Nitrate: {{ $tank->nitrate_level }} mg/L</span>
                            <span class="text-sm {{ $tank->nitrate_level <= 50 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $tank->nitrate_level <= 50 ? 'Normal' : 'High' }}
                            </span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ min(($tank->nitrate_level / 150) * 100, 100) }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="mt-6">
            <h3 class="font-semibold mb-2">Request Maintenance or Support</h3>
            <form action="{{ route('client.request.agent') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <input type="text" name="title" placeholder="Request Title" class="w-full p-2 border rounded-md" required>
                    </div>
                    <div>
                        <select name="type" class="w-full p-2 border rounded-md" required>
                            <option value="general">General Inquiry</option>
                            <option value="maintenance">Maintenance Request</option>
                            <option value="alert">Report Issue</option>
                        </select>
                    </div>
                </div>
                <div class="mt-2">
                    <textarea name="message" rows="3" placeholder="Describe your request or issue..." class="w-full p-2 border rounded-md" required></textarea>
                </div>
                <div class="mt-2 text-right">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md">Send Request</button>
                </div>
            </form>
        </div>
    </div>
    @else
    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm text-yellow-700">
                    No tank is currently assigned to your account. Please contact your agent or administrator.
                </p>
            </div>
        </div>
    </div>
    @endif
    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white p-4 rounded-lg shadow">
            <h2 class="text-xl font-bold mb-4">Recent Notifications</h2>
            <div class="space-y-4">
                @foreach(\App\Models\Notification::where('receiver_id', Auth::id())->latest()->take(5)->get() as $notification)
                <div class="border-l-4 border-blue-500 pl-4 py-2">
                    <div class="flex justify-between">
                        <h3 class="font-semibold">{{ $notification->title }}</h3>
                        <span class="text-sm text-gray-500">{{ $notification->created_at->diffForHumans() }}</span>
                    </div>
                    <p class="text-sm text-gray-600 truncate">{{ $notification->message }}</p>
                    <div class="mt-1 flex items-center gap-2">
                        <span class="text-xs text-gray-500">From: {{ $notification->sender->name }}</span>
                        <span class="text-xs {{ $notification->read_at ? 'text-green-500' : 'text-red-500' }}">
                            {{ $notification->read_at ? 'Read' : 'Unread' }}
                        </span>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="mt-4 text-right">
                <a href="{{ route('notifications.index') }}" class="text-blue-500 hover:underline">View all notifications</a>
            </div>
        </div>
        
        <div class="bg-white p-4 rounded-lg shadow">
            <h2 class="text-xl font-bold mb-4">Water Usage Tips</h2>
            <div class="space-y-4">
                <div class="flex items-start gap-3">
                    <div class="bg-blue-100 p-2 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-blue-600">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 18v-5.25m0 0a6.01 6.01 0 001.5-.189m-1.5.189a6.01 6.01 0 01-1.5-.189m3.75 7.478a12.06 12.06 0 01-4.5 0m3.75 2.383a14.406 14.406 0 01-3 0M14.25 18v-.192c0-.983.658-1.823 1.508-2.316a7.5 7.5 0 10-7.517 0c.85.493 1.509 1.333 1.509 2.316V18" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold">Fix Leaky Faucets</h3>
                        <p class="text-sm text-gray-600">A dripping faucet can waste up to 20 gallons of water per day. Regularly check and fix leaks.</p>
                    </div>
                </div>
                
                <div class="flex items-start gap-3">
                    <div class="bg-blue-100 p-2 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-blue-600">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 18v-5.25m0 0a6.01 6.01 0 001.5-.189m-1.5.189a6.01 6.01 0 01-1.5-.189m3.75 7.478a12.06 12.06 0 01-4.5 0m3.75 2.383a14.406 14.406 0 01-3 0M14.25 18v-.192c0-.983.658-1.823 1.508-2.316a7.5 7.5 0 10-7.517 0c.85.493 1.509 1.333 1.509 2.316V18" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold">Use Water-Efficient Appliances</h3>
                        <p class="text-sm text-gray-600">Modern dishwashers and washing machines use significantly less water than older models.</p>
                    </div>
                </div>
                
                <div class="flex items-start gap-3">
                    <div class="bg-blue-100 p-2 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-blue-600">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 18v-5.25m0 0a6.01 6.01 0 001.5-.189m-1.5.189a6.01 6.01 0 01-1.5-.189m3.75 7.478a12.06 12.06 0 01-4.5 0m3.75 2.383a14.406 14.406 0 01-3 0M14.25 18v-.192c0-.983.658-1.823 1.508-2.316a7.5 7.5 0 10-7.517 0c.85.493 1.509 1.333 1.509 2.316V18" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold">Collect Rainwater</h3>
                        <p class="text-sm text-gray-600">Use rain barrels to collect water for gardening and outdoor cleaning tasks.</p>
                    </div>
                </div>
                
                <div class="flex items-start gap-3">
                    <div class="bg-blue-100 p-2 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-blue-600">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 18v-5.25m0 0a6.01 6.01 0 001.5-.189m-1.5.189a6.01 6.01 0 01-1.5-.189m3.75 7.478a12.06 12.06 0 01-4.5 0m3.75 2.383a14.406 14.406 0 01-3 0M14.25 18v-.192c0-.983.658-1.823 1.508-2.316a7.5 7.5 0 10-7.517 0c.85.493 1.509 1.333 1.509 2.316V18" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold">Take Shorter Showers</h3>
                        <p class="text-sm text-gray-600">Reducing shower time by just 2 minutes can save up to 10 gallons of water.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection