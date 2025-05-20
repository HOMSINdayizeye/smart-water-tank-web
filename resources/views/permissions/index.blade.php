@extends('layouts.dashboard')

@section('title', 'Permissions Management')

@section('sidebar')
    {{-- Sidebar content goes here if needed, otherwise leave empty --}}
@endsection

@section('content')
<div class="main-content-padding">
    <div class="flex items-center mb-6">
        <a href="{{ route('dashboard') }}" class="back-link">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
        </a>
        <h1 class="text-2xl font-bold">Permissions Management</h1>
        {{-- Add a link to assign permissions if needed --}}
        {{-- <a href="{{ route('permissions.assign') }}" class="btn btn-primary">Assign Permissions</a> --}}
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

    <div class="card mb-6">
        <div class="card-body">
            <h2>Available Permissions</h2>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($permissions as $permission)
                        <tr>
                            <td>{{ $permission->id }}</td>
                            <td>{{ $permission->name }}</td>
                            <td>{{ $permission->description ?? 'N/A' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <h2>Assign Permissions to Agents</h2>
            {{-- A basic form structure to assign permissions --}}
            <form action="{{ route('permissions.assign') }}" method="POST">
                @csrf
                <div class="form-grid">
                     <div class="form-group">
                        <label for="agent">Select Agent</label>
                        <select name="user_id" id="agent" class="form-control" required>
                            <option value="">Select Agent</option>
                            @foreach($agents as $agent)
                                <option value="{{ $agent->id }}">{{ $agent->name }} ({{ $agent->email }})</option>
                            @endforeach
                        </select>
                         @error('user_id')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="permissions">Select Permissions to Assign</label>
                        <select name="permissions[]" id="permissions" class="form-control" multiple>
                            @foreach($permissions as $permission)
                                <option value="{{ $permission->id }}">{{ $permission->name }}</option>
                            @endforeach
                        </select>
                         @error('permissions')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                 <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Assign Permissions</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection 