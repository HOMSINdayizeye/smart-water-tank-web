@extends('layouts.app')

@section('content')
<div class="container mx-auto py-6">
    <h1 class="text-2xl font-semibold mb-6">Client Alerts</h1>

    <div class="bg-white rounded-lg shadow-md p-6">
        @forelse ($notifications as $notification)
            <div class="border-l-4 {{ $notification->read_at ? 'border-gray-400' : 'border-blue-500' }} pl-4 py-3 mb-4 last:mb-0">
                <div class="flex justify-between items-center">
                    <h3 class="font-semibold text-lg {{ $notification->read_at ? 'text-gray-600' : 'text-blue-800' }}">{{ $notification->title }}</h3>
                    <span class="text-sm text-gray-500">{{ $notification->created_at->diffForHumans() }}</span>
                </div>
                <p class="mt-1 text-gray-700">{{ $notification->message }}</p>
                <div class="mt-2 text-sm text-gray-600">
                    From: {{ $notification->sender->name ?? 'System' }}
                </div>
            </div>
        @empty
            <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4" role="alert">
                <p class="font-bold">No New Alerts</p>
                <p>You have no new alerts at the moment.</p>
            </div>
        @endforelse

        {{-- Add pagination links if using paginate() in controller --}}
        {{-- {{ $notifications->links() }} --}}
    </div>
</div>
@endsection 