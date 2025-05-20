@extends('layouts.dashboard')

@section('title', 'Security & Authentication')

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
        <h1 class="text-2xl font-bold">Security & Authentication</h1>
    </div>

    <div class="card">
        <div class="card-body">
            <h2>Security Settings Overview</h2>
            <p>Manage and review security-related configurations and events for the system.</p>

            <div class="mt-6">
                <h3>Activity Logs / Audit Trail</h3>
                <p>View a log of user actions and system events for auditing and security analysis.</p>
                {{-- Link to a detailed activity log viewer --}}
            </div>

            <div class="mt-6">
                <h3>Login Attempts and Security Events</h3>
                <p>Monitor successful and failed login attempts, and other security-related incidents.</p>
                {{-- Display charts or tables of login attempts --}}
            </div>

             <div class="mt-6">
                <h3>Authentication Configuration</h3>
                <p>Configure settings related to user authentication, such as password policies or multi-factor authentication.</p>
                {{-- Form or links to authentication settings --}}
            </div>

            <div class="mt-6">
                <h3>User Permissions Summary</h3>
                <p>Overview of user roles and permissions structure, with a link to the detailed Permissions Management page.</p>
                <a href="{{ route('permissions.index') }}" class="btn btn-secondary btn-sm mt-2">Go to Permissions Management</a>
            </div>

            {{-- Add more sections as needed for other security aspects --}}
        </div>
    </div>
</div>
@endsection 