<div class="card">
    <h2 class="text-xl font-bold mb-4">Recent Notifications</h2>
    <div class="space-y-4">
        @foreach($recentNotifications as $notification)
        <div class="notification-item">
            <div class="notification-header">
                <span class="notification-title">{{ $notification->title }}</span>
                <span class="notification-date">{{ $notification->created_at->format('d/m/Y') }}</span>
            </div>
            <p class="notification-message">{{ $notification->message }}</p>
        </div>
        @endforeach
    </div>
</div> 