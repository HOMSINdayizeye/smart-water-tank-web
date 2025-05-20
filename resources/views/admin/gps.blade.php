@extends('layouts.dashboard')

@section('title', 'GPS Integration')

@section('sidebar')
    {{-- Sidebar content goes here if needed, otherwise leave empty --}}
@endsection

@section('content')
<div class="main-content-padding">
    <div class="flex items-center mb-6">
        <a href="{{ route('dashboard') }}" class="back-link">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 15" stroke-width="1.2" stroke="currentColor" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
        </a>
        <h1 class="text-2xl font-bold page-title">GPS Integration</h1>
    </div>

    <div class="card gps-overview-card">
        <div class="card-body">
            <h2 class="card-title">Tank Location Overview</h2>
            <p>View the geographical location of all monitored tanks.</p>

            <div class="mt-6 gps-section">
                <h3 class="section-heading">Interactive Map</h3>
                <p>Embed an interactive map (e.g., Google Maps, Leaflet) showing the location of each tank.</p>
                {{-- Placeholder for map integration --}}
                <div class="placeholder-box map-placeholder">Interactive Map Placeholder</div>
            </div>

            <div class="mt-6 gps-section">
                <h3 class="section-heading">Tank Coordinates List</h3>
                <p>Display a table listing each tank with its precise GPS coordinates (latitude and longitude).</p>
                {{-- Placeholder for a table of tanks and coordinates --}}
                <div class="placeholder-box coordinates-placeholder">Tank Coordinates List Placeholder</div>
            </div>

             <div class="mt-6 gps-section">
                <h3 class="section-heading">GPS Settings / Geofencing</h3>
                <p>Configure GPS tracking settings or set up geofences for specific areas.</p>
                {{-- Placeholder for configuration options --}}
                 <div class="placeholder-box settings-placeholder">GPS Settings Placeholder</div>
            </div>

            {{-- Add more sections as needed for other GPS integration aspects --}}

        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Basic styles for clarity - Similar to communication page for consistency */
    .main-content-padding {
        padding: 1.5rem;
    }

    .page-title {
        margin-left: 1rem; /* Add some space after the back link */
    }

    .back-link {
        display: flex;
        /* align-items: center; */
        color: #374151; /* text-gray-700 */
        text-decoration: none;
    }

    .back-link:hover {
        color: #1f2937; /* text-gray-900 */
    }

    .card {
        background-color: #ffffff;
        border-radius: 0.5rem; /* rounded-lg */
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06); /* shadow-sm */
        margin-bottom: 1.5rem; /* mb-6 */
    }

    .card-body {
        padding: 1.5rem; /* p-6 */
    }

    .card-title {
        font-size: 1.25rem; /* text-xl */
        font-weight: 600; /* font-semibold */
        color: #1f2937; /* text-gray-900 */
        margin-bottom: 1rem; /* mb-4 */
    }

    .gps-section {
        margin-top: 1.5rem; /* mt-6 */
        padding-top: 1.5rem; /* Add padding for visual separation */
        border-top: 1px solid #e5e7eb; /* Add a border for separation */
    }

    .gps-section:first-of-type {
        margin-top: 0;
        padding-top: 0;
        border-top: none;
    }

    .section-heading {
         font-size: 1.125rem; /* text-lg */
         font-weight: 600; /* font-semibold */
         color: #374151; /* text-gray-700 */
         margin-bottom: 0.75rem; /* mb-3 */
    }

    .placeholder-box {
        border: 2px dashed #d1d5db; /* border-gray-300 */
        padding: 1rem; /* p-4 */
        margin-top: 1rem; /* mt-4 */
        text-align: center;
        color: #6b7280; /* text-gray-500 */
        font-style: italic;
        background-color: #f9fafb; /* bg-gray-50 */
    }

    /* Specific styles for GPS placeholders if needed */
     .map-placeholder {
         min-height: 300px; /* Give map placeholder a height */
     }

     .coordinates-placeholder {
         /* Specific styles for the coordinates list placeholder */
     }

     .settings-placeholder {
         /* Specific styles for settings placeholder */
     }

</style>
@endpush 