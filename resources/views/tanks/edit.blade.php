@extends('layouts.dashboard')

@section('title', 'Edit Tank: ' . $tank->id)

@section('sidebar')
    {{-- Sidebar content goes here if needed, otherwise leave empty --}}
@endsection



@section('content')


<style>
      .btn-primary {
        background-color: #2563eb;
        color: white;
        padding: 10px 16px;
        border: none;
        border-radius: 6px;
        font-size: 16px;
        font-weight: bold;
        transition: background-color 0.3s ease;
        cursor: pointer;
      }
</style>
<div class="main-content-padding">
    <div class="flex items-center mb-6">
        <a href="{{ route('tanks.index') }}" class="back-link">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
        </a>
        <h1 class="text-2xl font-bold">Edit Tank #{{ $tank->id }}</h1>
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
            <form action="{{ route('tanks.update', $tank) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-grid">
                    <div class="form-group">
                        <label for="location">Location</label>
                        <input type="text" name="location" id="location" value="{{ old('location', $tank->location) }}" required>
                        @error('location')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="capacity">Capacity (L)</label>
                        <input type="number" name="capacity" id="capacity" value="{{ old('capacity', $tank->capacity) }}" required>
                        @error('capacity')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="current_level">Current Level (L)</label>
                        <input type="number" name="current_level" id="current_level" value="{{ old('current_level', $tank->current_level) }}" required>
                        @error('current_level')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="ph_level">pH Level</label>
                        <input type="number" step="0.1" name="ph_level" id="ph_level" value="{{ old('ph_level', $tank->ph_level) }}" required>
                        @error('ph_level')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="chloride_level">Chloride Level (mg/L)</label>
                        <input type="number" name="chloride_level" id="chloride_level" value="{{ old('chloride_level', $tank->chloride_level) }}" required>
                        @error('chloride_level')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="fluoride_level">Fluoride Level (mg/L)</label>
                        <input type="number" step="0.1" name="fluoride_level" id="fluoride_level" value="{{ old('fluoride_level', $tank->fluoride_level) }}" required>
                        @error('fluoride_level')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="nitrate_level">Nitrate Level (mg/L)</label>
                        <input type="number" name="nitrate_level" id="nitrate_level" value="{{ old('nitrate_level', $tank->nitrate_level) }}" required>
                        @error('nitrate_level')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="status">Status</label>
                        <select name="status" id="status" required>
                            <option value="active" {{ old('status', $tank->status) == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status', $tank->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="maintenance" {{ old('status', $tank->status) == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                        </select>
                        @error('status')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Update Tank</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 