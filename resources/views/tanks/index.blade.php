@extends('layouts.dashboard')

@section('title', 'Tank Management')

@section('sidebar')
    {{-- Sidebar content goes here if needed, otherwise leave empty --}}
@endsection

@section('content')
<div class="main-content-padding">
    <div class="flex items-center justify-between mb-6">
        <a href="{{ route('dashboard') }}" class="back-link">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
        </a>
        <h1 class="text-2xl font-bold">Tank Management</h1>
        {{-- Assuming a route for creating tanks exists --}}
        <a href="{{ route('tanks.create') }}" class="btn btn-primary">Add New Tank</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
     @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Location</th>
                            <th>Capacity (L)</th>
                            <th>Current Level (L)</th>
                            <th>Water Quality</th>
                            <th>Status</th>
                            <th>Last Updated</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tanks as $tank)
                        <tr>
                            <td>{{ $tank->id }}</td>
                            <td>{{ $tank->location }}</td>
                            <td>{{ $tank->capacity }}</td>
                            <td>{{ $tank->current_level }}</td>
                            <td>{{ $tank->water_quality }}</td>
                            <td>
                                <span class="badge badge-{{ $tank->status === 'active' ? 'success' : ($tank->status === 'maintenance' ? 'warning' : 'danger') }}">
                                    {{ ucfirst($tank->status) }}
                                </span>
                            </td>
                            <td>{{ $tank->updated_at }}</td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('tanks.show', $tank) }}" class="btn btn-sm btn-info">View</a>
                                    <a href="{{ route('tanks.edit', $tank) }}" class="btn btn-sm btn-warning">Edit</a>
                                    <form action="{{ route('tanks.destroy', $tank) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this tank?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Add pagination links if using pagination in controller --}}
    {{-- <div class="pagination-links mt-4">
        {{ $tanks->links() }}
    </div> --}}
</div>
@endsection 