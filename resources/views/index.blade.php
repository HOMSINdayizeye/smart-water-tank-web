@extends('layouts.app')

@section('content')
<div class="p-4">
    <h1 class="text-2xl font-bold mb-4">Notifications</h1>
    
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="p-4 border-b">
            <div class="flex justify-between items-center">
                <h2 class="text-lg font-semibold">All Notifications</h2>
                <div class="text-sm text-gray-500">
                    Unread: {{ \App\Models\Notification::where('receiver_id', Auth::id())->whereNull('read_at')->count() }}
                </div>
            </div>
        </div>
        
        <div class="divide-y divide-gray-200">
            @forelse($notifications as $notification)
                <div class="p-4 {{ $notification->read_at ? 'bg-white' : 'bg-blue-50' }}">
                    <div class="flex justify-between">
                        <h3 class="font-semibold">{{ $notification->title }}</h3>
                        <span class="text-sm text-gray-500">{{ $notification->created_at->format('M d, Y H:i') }}</span>
                    </div>
                    <p class="text-gray-600 mt-1">{{ Str::limit($notification->message, 150) }}</p>
                    <div class="mt-2 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="text-xs text-gray-500">From: {{ $notification->sender->name }}</span>
                            <span class="text-xs {{ $notification->read_at ? 'text-green-500' : 'text-red-500' }}">
                                {{ $notification->read_at ? 'Read' : 'Unread' }}
                            </span>
                        </div>
                        <div class="flex gap-2">
                            <a href="{{ route('notifications.show', $notification) }}" class="text-blue-500 hover:underline text-sm">View</a>
                            @if(!$notification->read_at)
                                <form action="{{ route('notifications.markAsRead', $notification) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-green-500 hover:underline text-sm">Mark as Read</button>
                                </form>
                            @endif
                            <form action="{{ route('notifications.destroy', $notification) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:underline text-sm">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-4 text-center text-gray-500">
                    No notifications found.
                </div>
            @endforelse
        </div>
        
        <div class="p-4 border-t">
            {{ $notifications->links() }}
        </div>
    </div>
</div>
@endsection