@extends('layouts.app')

@section('content')
<div class="p-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Admin Dashboard</h1>
        <a href="{{ route('users.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md">Create New User</a>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
        <!-- Total Users Card -->
        <div class="bg-white p-4 rounded-lg shadow">
            <div class="flex items-center gap-1">
                <div class="bg-blue-100 rounded-full flex-shrink-0 w-4 h-4 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" 
                         fill="none" 
                         viewBox="0 0 24 24" 
                         stroke-width="1.5" 
                         stroke="currentColor" 
                         class="w-4 h-4 text-blue-600">
                        <path stroke-linecap="round" 
                              stroke-linejoin="round" 
                              d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold">Total Users</h3>
                    <p class="text-2xl font-bold">{{ $totalUsers }}</p>
                </div>
            </div>
        </div>
        
        <!-- Alerts Sent Card -->
        <div class="bg-white p-4 rounded-lg shadow">
            <div class="flex items-center gap-1">
                <div class="bg-blue-100 rounded-full flex-shrink-0 w-4 h-4 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" 
                         fill="none" 
                         viewBox="0 0 24 24" 
                         stroke-width="1.5" 
                         stroke="currentColor" 
                         class="w-4 h-4 text-blue-600">
                        <path stroke-linecap="round" 
                              stroke-linejoin="round" 
                              d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold">Alerts Sent</h3>
                    <p class="text-2xl font-bold">{{ $alertsSent }}</p>
                </div>
            </div>
        </div>
        
        <!-- Active Tanks Card -->
        <div class="bg-white p-4 rounded-lg shadow">
            <div class="flex items-center gap-1">
                <div class="bg-blue-100 rounded-full flex-shrink-0 w-4 h-4 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" 
                         fill="none" 
                         viewBox="0 0 24 24" 
                         stroke-width="1.5" 
                         stroke="currentColor" 
                         class="w-4 h-4 text-blue-600">
                        <path stroke-linecap="round" 
                              stroke-linejoin="round" 
                              d="M3.75 3v11.25A2.25 2.25 0 006 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0118 16.5h-2.25m-7.5 0h7.5m-7.5 0l-1 3m8.5-3l1 3m0 0l.5 1.5m-.5-1.5h-9.5m0 0l-.5 1.5m.75-9l3-3 2.148 2.148A12.061 12.061 0 0116.5 7.605" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold">Active Tanks</h3>
                    <p class="text-2xl font-bold">{{ $activeTanks }}</p>
                </div>
            </div>
        </div>
        
        <!-- Maintenance Requests Card -->
        <div class="bg-white p-4 rounded-lg shadow">
            <div class="flex items-center gap-1">
                <div class="bg-blue-100 rounded-full flex-shrink-0 w-4 h-4 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" 
                         fill="none" 
                         viewBox="0 0 24 24" 
                         stroke-width="1.5" 
                         stroke="currentColor" 
                         class="w-4 h-4 text-blue-600">
                        <path stroke-linecap="round" 
                              stroke-linejoin="round" 
                              d="M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 11-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 004.486-6.336l-3.276 3.277a3.004 3.004 0 01-2.25-2.25l3.276-3.276a4.5 4.5 0 00-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 3.75l1.5-1.5L7.5 4.5v1.409l4.26 4.26m-1.745 1.437l1.745-1.437m6.615 8.206L15.75 15.75M4.867 19.125h.008v.008h-.008v-.008z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold">Maintenance Requests</h3>
                    <p class="text-2xl font-bold">{{ $maintenanceRequests }}</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Recent Users & Notifications -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white p-4 rounded-lg shadow">
            <h2 class="text-xl font-bold mb-4">Recent Users</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach(\App\Models\User::latest()->take(5)->get() as $user)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $user->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $user->email }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-800' : 
                                       ($user->role === 'agent' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800') }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="bg-white p-4 rounded-lg shadow">
            <h2 class="text-xl font-bold mb-4">Recent Notifications</h2>
            <div class="space-y-4">
                @foreach(\App\Models\Notification::latest()->take(5)->get() as $notification)
                <div class="border-l-4 border-blue-500 pl-4 py-2">
                    <div class="flex justify-between">
                        <h3 class="font-semibold">{{ $notification->title }}</h3>
                        <span class="text-sm text-gray-500">{{ $notification->created_at->diffForHumans() }}</span>
                    </div>
                    <p class="text-sm text-gray-600 truncate">{{ $notification->message }}</p>
                    <div class="mt-1 flex items-center gap-2">
                        <span class="text-xs text-gray-500">From: {{ $notification->sender->name }}</span>
                        <span class="text-xs text-gray-500">To: {{ $notification->receiver->name }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
