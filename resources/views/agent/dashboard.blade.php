@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/dashboard-shared.css') }}">
<style>
    /* Specific styles for Agent Dashboard  */
    .quick-actions-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 1rem;
    }

    .notification-list .notification-item {
        border-bottom: 1px solid var(--border-color);
        padding-bottom: 1rem;
        margin-bottom: 1rem;
        transition: var(--transition);
        cursor: pointer;
    }

    .notification-list .notification-item:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }

    .notification-list .notification-item:hover {
        background-color: rgba(2, 132, 199, 0.05);
        transform: translateX(5px);
    }

    .notification-item .notification-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.5rem;
    }

    .notification-item .notification-title {
        font-size: 1rem;
        font-weight: 600;
        color: var(--secondary-color);
        margin: 0;
    }

    .notification-item .notification-time {
        font-size: 0.8rem;
        color: var(--secondary-color);
        opacity: 0.7;
    }

    .notification-item .notification-message {
        font-size: 0.9rem;
        color: var(--secondary-color);
        opacity: 0.9;
        margin-bottom: 0.75rem;
    }

     .notification-item .notification-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.8rem;
        color: var(--secondary-color);
        opacity: 0.7;
     }

     .notification-item .notification-status.status-read {
         color: var(--success-color);
         font-weight: 600;
     }

     .notification-item .notification-status.status-unread {
         color: var(--warning-color);
         font-weight: 600;
     }

    .data-table thead th {
        background-color: var(--light-bg);
        color: var(--secondary-color);
        font-weight: 600;
        text-align: left;
        padding: 1rem;
        border-bottom: 2px solid var(--border-color);
    }

    .data-table tbody td {
        padding: 1rem;
        border-bottom: 1px solid var(--border-color);
        color: var(--secondary-color);
    }

    .data-table tbody tr:hover td {
        background-color: rgba(2, 132, 199, 0.05);
        cursor: pointer;
    }

    .status-badge {
        padding: 0.35em 0.65em;
        font-size: 0.75em;
        font-weight: 600;
        border-radius: 0.5rem;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        color: white;
    }

    .status-badge.status-active {
        background-color: var(--success-color);
    }

    .status-badge.status-maintenance {
        background-color: var(--warning-color);
    }

    .status-badge.status-inactive {
        background-color: var(--danger-color);
    }

    /* Adjustments for sidebar overlap on smaller screens */
    @media (max-width: 768px) {
        .main-content {
            margin-left: 0;
        }
    }

</style>
@endpush

@section('content')
<div class="main-content">
    <div class="dashboard-header">
        <h1>Agent Dashboard</h1>
    </div>
    
    <div class="stats-container">
        <div class="stats-card">
            <div class="d-flex align-items-center gap-4">
                <div class="icon-container">
                     <i class="fas fa-users"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-blue-600">Total Clients</h3>
                    <p class="text-2xl font-bold text-gray-800">{{ $totalClients }}</p>
                </div>
            </div>
        </div>
        
        <div class="stats-card">
            <div class="d-flex align-items-center gap-4">
                <div class="icon-container">
                    <i class="fas fa-bell"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-blue-600">Alerts Sent</h3>
                    <p class="text-2xl font-bold text-gray-800">{{ $alertsSent }}</p>
                </div>
            </div>
        </div>
        
        <div class="stats-card">
            <div class="d-flex align-items-center gap-4">
                <div class="icon-container">
                     <i class="fas fa-tank"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-blue-600">Active Tanks</h3>
                    <p class="text-2xl font-bold text-gray-800">{{ $activeTanks }}</p>
                </div>
            </div>
        </div>
        
        <div class="stats-card">
            <div class="d-flex align-items-center gap-4">
                <div class="icon-container">
                     <i class="fas fa-wrench"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-blue-600">Maintenance Requests</h3>
                    <p class="text-2xl font-bold text-gray-800">{{ $maintenanceRequests }}</p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">My Clients</h2>
                <a href="{{ route('agent.create.client.form') }}" class="btn btn-primary btn-sm">Add Client</a>
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
                        <tr data-client-id="{{ $client->id }}">
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
            <div class="mt-3 text-end">
                 <a href="{{ route('agent.clients') }}" class="btn btn-secondary btn-sm">View all clients</a>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Recent Notifications</h2>
            </div>
            <div class="notification-list">
                @foreach(\App\Models\Notification::where('receiver_id', Auth::id())->latest()->take(5)->get() as $notification)
                <div class="notification-item" data-notification-id="{{ $notification->id }}">
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
            <div class="card-actions mt-3 text-end">
                <a href="{{ route('notifications.index') }}" class="btn btn-secondary btn-sm me-2">View all notifications</a>
                <a href="{{ route('agent.notifications.send_to_admin_form') }}" class="btn btn-primary btn-sm">Send Notification</a>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Get CSRF token from meta tag
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    // Set up axios defaults
    axios.defaults.headers.common['X-CSRF-TOKEN'] = token;
    axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

    // Handle notification clicks
    document.querySelectorAll('.notification-item').forEach(item => {
        item.addEventListener('click', function() {
            const notificationId = this.dataset.notificationId;
            if (!this.classList.contains('status-read')) {
                markNotificationAsRead(notificationId);
            }
        });
    });

    // Handle client row clicks
    document.querySelectorAll('.data-table tbody tr').forEach(row => {
        row.addEventListener('click', function() {
            const clientId = this.dataset.clientId;
            loadClientDetails(clientId);
        });
    });
});

function markNotificationAsRead(notificationId) {
    fetch(`/agent/notifications/${notificationId}/read`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const notification = document.querySelector(`[data-notification-id="${notificationId}"]`);
            notification.classList.add('status-read');
            notification.classList.remove('status-unread');
            notification.querySelector('.notification-status').textContent = 'Read';
        }
    })
    .catch(error => console.error('Error marking notification as read:', error));
}

function loadClientDetails(clientId) {
    fetch(`/agent/clients/${clientId}`)
        .then(response => response.json())
        .then(client => {
            // You can implement a modal or side panel to show client details
            console.log('Client details:', client);
        })
        .catch(error => console.error('Error loading client details:', error));
}

// Real-time updates using WebSocket
const ws = new WebSocket('ws://' + window.location.host + '/ws');

ws.onmessage = function(event) {
    const data = JSON.parse(event.data);
    
    switch(data.type) {
        case 'notification':
            addNewNotification(data.notification);
            break;
        case 'tank_status':
            updateTankStatus(data.tank);
            break;
        case 'maintenance_request':
            updateMaintenanceRequest(data.request);
            break;
    }
};

function addNewNotification(notification) {
    const notificationsContainer = document.querySelector('.notification-list'); // Updated selector
    const notificationHtml = `
        <div class="notification-item" data-notification-id="${notification.id}">
            <div class="notification-header">
                <h3 class="notification-title">${notification.title}</h3>
                <span class="notification-time">Just now</span>
            </div>
            <p class="notification-message">${notification.message}</p>
            <div class="notification-footer">
                <span class="notification-sender">From: ${notification.sender?.name || 'System'}</span>
                <span class="notification-status status-unread">Unread</span>
            </div>
        </div>
    `;
    
    notificationsContainer.insertAdjacentHTML('afterbegin', notificationHtml);
    updateAlertCount(); // Call updateAlertCount for notification count
}

function updateTankStatus(tank) {
    const tankRow = document.querySelector(`[data-tank-id="${tank.id}"]`);
    if (tankRow) {
        const statusBadge = tankRow.querySelector('.status-badge');
        statusBadge.className = `status-badge status-${tank.status}`;
        statusBadge.textContent = tank.status.charAt(0).toUpperCase() + tank.status.slice(1);
    }
}

function updateMaintenanceRequest(request) {
    // Update maintenance request count
    const maintenanceCount = document.querySelector('.stats-card:nth-child(4) p'); // Updated selector
    const currentCount = parseInt(maintenanceCount.textContent);
    maintenanceCount.textContent = currentCount + 1;
}

function updateAlertCount() {
    const alertCountElement = document.querySelector('.stats-card:nth-child(2) p'); // Updated selector
    if (alertCountElement) {
        const currentCount = parseInt(alertCountElement.textContent);
        alertCountElement.textContent = currentCount + 1;
    }
}
</script>
@endsection 