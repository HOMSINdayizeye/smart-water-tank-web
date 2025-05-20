@extends('layouts.app')

@section('content')

<div class="dashboard-container">
    <h1 class="dashboard-title">Agent Dashboard</h1>
    {{-- Logout Button --}}


<div class="stats-container">

    
    <div class="stat-card">
        <div class="stat-icon-container">
            <div class="stat-icon">👥</div>
        </div>
        <div class="stat-info">
            <h3>Clients</h3>
            <p>{{ $totalClients ?? 0 }}</p>
        </div>
    </div>

              Alerts Sent
    <div class="stat-card">
        <div class="stat-icon-container">
            <div class="stat-icon">📢</div>
        </div>
        <div class="stat-info">
            <h3>Alerts Sent</h3>
            <p>{{ $totalAlerts ?? 0 }}</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon-container">
            <div class="stat-icon">🛢️</div>
        </div>
        <div class="stat-info">
            <h3>Active Tanks</h3>
            <p>{{ $activeTanks ?? 0 }}</p>
        </div>
    </div>

    
        <div class="stat-icon-container">
            <div class="stat-icon">🛠️</div>
        </div>
        <div class="stat-info">
            <h3>Maintenance Requests</h3>
            <p>{{ $maintenanceRequests ?? 0 }}</p>
        </div>
    </div>

</div> 

<style>
    .stats-container {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        justify-content: center;
        padding: 20px;
        background: #f9fafb;
    }

    .stat-card {
        background: white;
        border-radius: 10px;
        box-shadow: 0 3px 6px rgba(0,0,0,0.1);
        width: 200px;
        display: flex;
        align-items: center;
        padding: 15px;
        transition: transform 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 12px rgba(0,0,0,0.15);
    }

    .stat-icon-container {
        flex-shrink: 0;
        width: 50px;
        height: 50px;
        background: #3b82f6;
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        margin-right: 15px;
        color: white;
        font-size: 24px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    }

    .stat-info h3 {
        margin: 0 0 5px 0;
        font-size: 18px;
        color: #111827;
    }

    .stat-info p {
        margin: 0;
        font-size: 22px;
        font-weight: bold;
        color: #1e293b;
    }
</style>

<div class="content-container">

      <!-- My Clients -->
    <div class="content-card">
        <div class="card-header">
            <h2>My Clients</h2>
            <a href="{{ route('agent.create.client.form') }}" class="add-button">Add Client</a>

<div class="dashboard-container">
    <h1 class="dashboard-title">Agent Dashboard</h1>
    
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-card-content">
                <div class="stat-icon-container">

                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="stat-icon">

                    <svg xmlns="" fill="none" viewBox="0 0 24 24" stroke-width="0.5px" stroke="currentColor" class="stat-icon">

                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                    </svg>
                </div>
                <div class="stat-info">
                    <h3>Total Clients</h3>
                    <p>{{ $totalClients }}</p>
                </div>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-card-content">
                <div class="stat-icon-container">

                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="stat-icon">

                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="0.5px" stroke="currentColor" class="stat-icon">

                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                    </svg>
                </div>
                <div class="stat-info">
                    <h3>Alerts Sent</h3>
                    <p>{{ $alertsSent }}</p>
                </div>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-card-content">
                <div class="stat-icon-container">

                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="stat-icon">

                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="0.5px" stroke="currentColor" class="stat-icon">

                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 006 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0118 16.5h-2.25m-7.5 0h7.5m-7.5 0l-1 3m8.5-3l1 3m0 0l.5 1.5m-.5-1.5h-9.5m0 0l-.5 1.5m.75-9l3-3 2.148 2.148A12.061 12.061 0 0116.5 7.605" />
                    </svg>
                </div>
                <div class="stat-info">
                    <h3>Active Tanks</h3>
                    <p>{{ $activeTanks }}</p>
                </div>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-card-content">
                <div class="stat-icon-container">

                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="stat-icon">

                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="0.5px" stroke="currentColor" class="stat-icon">

                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 11-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 004.486-6.336l-3.276 3.277a3.004 3.004 0 01-2.25-2.25l3.276-3.276a4.5 4.5 0 00-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 3.75l1.5-1.5L7.5 4.5v1.409l4.26 4.26m-1.745 1.437l1.745-1.437m6.615 8.206L15.75 15.75M4.867 19.125h.008v.008h-.008v-.008z" />
                    </svg>
                </div>
                <div class="stat-info">
                    <h3>Maintenance Requests</h3>
                    <p>{{ $maintenanceRequests }}</p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="content-grid">
        <div class="content-card">
            <div class="card-header">
                <h2 class="card-title">My Clients</h2>
                <a href="{{ route('agent.create.client.form') }}" class="add-button">Add Client</a>
            </div>
            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Tank Location</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(\App\Models\User::where('role', 'client')->where('created_by', Auth::id())->take(5)->get() as $client)
                        <tr>
                            <td>{{ $client->name }}</td>
                            <td>{{ $client->email }}</td>
                            <td>{{ $client->tank ? $client->tank->location : 'No tank' }}</td>
                            <td>
                                <span class="status-badge {{ $client->tank && $client->tank->status === 'active' ? 'status-active' : 
                                    ($client->tank && $client->tank->status === 'maintenance' ? 'status-maintenance' : 'status-inactive') }}">
                                    {{ $client->tank ? ucfirst($client->tank->status) : 'No tank' }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <a href="{{ route('agent.clients') }}" class="view-all-link">View all clients</a>
        </div>
        
        <div class="content-card">
            <h2 class="card-title">Recent Notifications</h2>
            <div>
                @foreach(\App\Models\Notification::where('receiver_id', Auth::id())->latest()->take(5)->get() as $notification)
                <div class="notification-item">
                    <div class="notification-header">
                        <h3 class="notification-title">{{ $notification->title }}</h3>
                        <span class="notification-time">{{ $notification->created_at->diffForHumans() }}</span>
                    </div>
                    <p class="notification-message">{{ $notification->message }}</p>
                    <div class="notification-footer">
                        <span class="notification-sender">From: {{ $notification->sender->name ?? 'System' }}</span>
                        <span class="notification-status {{ $notification->read_at ? 'status-read' : 'status-unread' }}">
                            {{ $notification->read_at ? 'Read' : 'Unread' }}
                        </span>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="card-actions mt-3">
                <a href="{{ route('notifications.index') }}" class="view-all-link">View all notifications</a>
                <a href="{{ route('agent.notifications.send_to_admin_form') }}" class="add-button">Send Notification to Admin</a>
            </div>
        </div>
    </div>

    {{-- Fixed bottom-left logout button --}}
    <div style="position: fixed; bottom: 20px; left: 20px;">
        <form action="{{ route('logout') }}" method="POST" class="inline">
            @csrf
            <button type="submit" class="add-button">Logout</button>
        </form>
    </div>
</div>
@endsection
<!-- agent.blade.php -->
 


