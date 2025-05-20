@extends('layouts.app')

@section('content')

<div class="p-4">
    <div class="flex justify-between items-center mb-6">
        {{-- Home Button --}}
        
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


<style>
    body {
        background: #f8fafc;
        margin: 0;
        padding: 0;
        font-family: 'Figtree', sans-serif;
        overflow-x: hidden;
    }

    .admin-layout-container {
        display: flex;
        min-height: 100vh;
        width: 100%;
        box-sizing: border-box;
    }

    .admin-left-bar {
        width: 100px;
        background-color: #0ea5e9;
        display: flex;
        flex-direction: column;
        align-items: center;
        padding-top: 1.5rem;
        box-sizing: border-box;
        flex-shrink: 0;
    }

    .admin-home-icon-container {
        background-color: #2563eb;
        color: white;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        text-decoration: none;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        transition: background-color 0.3s ease;
    }

    .admin-home-icon-container:hover {
        background-color: #1e40af;
    }

    .admin-sidebar {
        width: 280px;
        background: #e2e8f0;
        padding: 2rem 1.5rem;
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        min-width: 250px;
        box-sizing: border-box;
        flex-shrink: 0;
    }

    .profile-icon {
        width: 80px;
        height: 80px;
        background: #cbd5e1;
        border-radius: 50%;
        margin-bottom: 1.8rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3rem;
        color: #475569;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .sidebar-title {
        font-weight: 700;
        margin-bottom: 1.5rem;
        font-size: 1.2rem;
        color: #1e293b;
        text-transform: uppercase;
    }

    .sidebar-menu {
        width: 100%;
        padding: 0;
        margin: 0;
        list-style: none;
    }

    .sidebar-menu li {
        margin-bottom: 1rem;
        font-weight: 500;
        display: flex;
        align-items: center;
        justify-content: flex-start;
        color: #475569;
        font-size: 1.05rem;
        transition: color 0.2s ease, background-color 0.2s ease;
    }

    .sidebar-menu li:last-child {
        margin-bottom: 0;
    }

    .sidebar-menu li a {
        color: inherit;
        text-decoration: none;
        display: flex;
        align-items: center;
        width: 100%;
        padding: 0.6rem 0.8rem;
        border-radius: 0.375rem;
        gap: 1rem;
    }

    .sidebar-menu li a:hover {
        background-color: #d1d5db;
        color: #1e293b;
    }

    .sidebar-menu li.highlight a {
        background-color: #bfdbfe;
        color: #1e3a8a;
        font-weight: 600;
    }

    .main-content {
        flex-grow: 1;
        padding: 4rem;
        background: #f8fafc;
        overflow-y: auto;
    }

    @media (max-width: 768px) {
        .admin-layout-container {
            flex-direction: column;
        }

        .admin-left-bar {
            width: 100%;
            height: 60px;
            flex-direction: row;
            padding: 0.5rem;
            justify-content: center;
            align-items: center;
            padding-top: 0;
        }

        .admin-sidebar {
            width: 100%;
            margin: 0.5rem 0;
            min-width: 0;
            align-items: center;
            padding: 1.5rem 1rem;
        }

        .sidebar-menu li {
            justify-content: center;
            margin-bottom: 0.8rem;
        }
    }

    @media (max-width: 480px) {
        .admin-left-bar {
            height: 50px;
            padding: 0.4rem;
        }

        .admin-sidebar {
            padding: 1rem 0.5rem;
        }

        .sidebar-menu li {
            margin-bottom: 0.6rem;
            font-size: 1rem;
        }
    }
</style>

<div class="admin-layout-container">
    <!-- Left Blue Bar with Home Icon -->
    <div class="admin-left-bar">
        <a href="{{ route('dashboard') }}" class="admin-home-icon-container">
            <i class="fas fa-home"></i>
        </a>

    </div>

    <!-- Sidebar -->
    <div class="admin-sidebar">
        <div class="profile-icon">
            <i class="fas fa-user-shield"></i>
        </div>
        <div class="sidebar-title">ADMIN PROFILE</div>
        <ul class="sidebar-menu">
            <li>
                <a href="{{ route('users.index') }}">
                    <i class="fas fa-users"></i>
                    User Management
                </a>
            </li>
            <li class="highlight">
                <a href="{{ route('permissions.index') }}">
                    <i class="fas fa-lock"></i>
                    Optimizing System Performance
                </a>
            </li>
            <li>
                <a href="{{ route('tanks.index') }}">
                    <i class="fas fa-chart-line"></i>
                    Monitoring System Health
                </a>
            </li>
            <li>
                <a href="{{ route('notifications.index') }}">
                    <i class="fas fa-bell"></i>
                    Reviewing Alerts & Reports
                </a>
            </li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        @yield('admin-content')
    </div>
</div>


{{-- Fixed top-left Home button (Icon) --}}
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

@endsection
