@extends('layouts.dashboard')

@section('title', 'Monitoring System Health')

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
        <h1 class="text-2xl font-bold">Monitoring System Health</h1>
    </div>

    <div class="card">
        <div class="card-body">
            <h2>System Status Overview</h2>
            <p>Here you can display a high-level overview of the system's health, such as uptime, resource usage, and connection status of critical components.</p>
            
            <div class="mt-6">
                <h3>Recent System Logs</h3>
                <p>Display a summary or snippets of recent system logs, especially error or warning logs.</p>
                {{-- Link to detailed logs page or a log viewer component --}}
            </div>

            <div class="mt-6">
                <h3>Database Health</h3>
                <p>Show the status and key metrics of the database connection and performance.</p>
                {{-- Database status indicators or charts --}}
            </div>

            <div class="mt-6">
                <h3>Sensor and Device Connectivity</h3>
                <p>Provide an overview of the connection status of the monitoring sensors and devices in the field.</p>
                {{-- List of devices and their last reported status --}}
            </div>

            {{-- Add more sections as needed for other monitoring aspects --}}

        </div>
    </div>
</div>
@endsection 