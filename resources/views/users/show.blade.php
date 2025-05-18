@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h1 class="h3 mb-0">User Details</h1>
                    <div>
                        <a href="{{ route('users.edit', $user) }}" class="btn btn-warning">Edit</a>
                        <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this user?')">Delete</button>
                        </form>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-4 text-center">
                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto" style="width: 100px; height: 100px; font-size: 2.5rem;">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                        </div>
                        <div class="col-md-8">
                            <h2 class="h4 mb-3">{{ $user->name }}</h2>
                            <p class="mb-1"><strong>Email:</strong> {{ $user->email }}</p>
                            <p class="mb-1">
                                <strong>Role:</strong>
                                <span class="badge bg-{{ $user->role === 'admin' ? 'danger' : ($user->role === 'agent' ? 'primary' : 'success') }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </p>
                            <p class="mb-1"><strong>Created:</strong> {{ $user->created_at->format('M d, Y H:i') }}</p>
                            <p class="mb-1">
                                <strong>Status:</strong>
                                <span class="badge bg-{{ $user->is_active ? 'success' : 'danger' }}">
                                    {{ $user->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </p>
                        </div>
                    </div>

                    @if($user->role === 'client' && $user->tank)
                        <div class="card mt-4">
                            <div class="card-header">
                                <h3 class="h5 mb-0">Tank Information</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Location:</strong> {{ $user->tank->location }}</p>
                                        <p><strong>Capacity:</strong> {{ $user->tank->capacity }} L</p>
                                        <p><strong>Current Level:</strong> {{ $user->tank->current_level }} L</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>pH Level:</strong> {{ $user->tank->ph_level }}</p>
                                        <p><strong>Chloride Level:</strong> {{ $user->tank->chloride_level }} mg/L</p>
                                        <p><strong>Fluoride Level:</strong> {{ $user->tank->fluoride_level }} mg/L</p>
                                        <p><strong>Nitrate Level:</strong> {{ $user->tank->nitrate_level }} mg/L</p>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <p>
                                        <strong>Status:</strong>
                                        <span class="badge bg-{{ $user->tank->status === 'active' ? 'success' : 'warning' }}">
                                            {{ ucfirst($user->tank->status) }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="mt-4">
                        <a href="{{ route('users.index') }}" class="btn btn-secondary">Back to Users</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection