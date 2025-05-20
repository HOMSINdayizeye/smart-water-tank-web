@extends('layouts.dashboard')

@section('title', 'Tank Details: ' . $tank->id)

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
        <h1 class="text-2xl font-bold">Tank Details #{{ $tank->id }}</h1>
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
            <div class="form-grid">
                <div class="form-group">
                    <label>Tank ID:</label>
                    <p>{{ $tank->id }}</p>
                </div>

                <div class="form-group">
                    <label>Location:</label>
                    <p>{{ $tank->location }}</p>
                </div>

                <div class="form-group">
                    <label>Capacity (L):</label>
                    <p>{{ $tank->capacity }}</p>
                </div>

                <div class="form-group">
                    <label>Current Level (L):</label>
                    <p>{{ $tank->current_level }}</p>
                </div>

                <div class="form-group">
                    <label>Water Quality:</label>
                    <p>{{ $tank->water_quality }}</p>
                </div>

                 <div class="form-group">
                    <label>Status:</label>
                    <p>
                         <span class="badge badge-{{ $tank->status === 'active' ? 'success' : ($tank->status === 'maintenance' ? 'warning' : 'danger') }}">
                            {{ ucfirst($tank->status) }}
                        </span>
                    </p>
                </div>

                <div class="form-group">
                    <label>Last Updated:</label>
                    <p>{{ $tank->updated_at }}</p>
                </div>

                 {{-- Add more tank details as needed --}}

            </div>

            <div class="form-actions">
                <a href="{{ route('tanks.edit', $tank) }}" class="btn btn-secondary">Edit Tank</a>
                 <form action="{{ route('tanks.destroy', $tank) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this tank?')" class="inline-form">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Tank</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

<style>
    /* Add any specific styles for this page here if necessary */
    .inline-form {
        display: inline-block;
        margin-left: 10px;
    }
</style> 