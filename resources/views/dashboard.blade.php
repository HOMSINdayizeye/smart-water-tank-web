@extends('layouts.app')

@section('content')
<style>
body { background: #fff; }
.agent-dashboard-topbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0.5rem 1.2rem 0.5rem 0.7rem;
    border-bottom: 4px solid #3b82f6;
    background: #fff;
    border-radius: 0 0 8px 8px;
    margin-bottom: 0.5rem;
}
.topbar-left {
    display: flex;
    align-items: center;
}
.topbar-home {
    width: 40px; height: 40px;
    background: #283593;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    color: #fff; font-size: 1.5rem;
    margin-right: 0.5rem;
}
.topbar-right {
    display: flex; align-items: center; gap: 1.1rem;
    color: #1976d2;
    font-size: 1.3rem;
}
.dashboard-main {
    display: flex;
    flex-direction: row;
    width: 100%;
    min-height: 80vh;
    background: #fff;
    padding: 0 0 0 0;
}
.dashboard-sidebar {
    width: 260px;
    background: #f3f4f6;
    border-radius: 10px;
    margin: 0 0.5rem 0 0.5rem;
    padding: 1.5rem 1rem;
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    min-width: 220px;
}
.profile-icon {
    width: 70px;
    height: 70px;
    background: #e0e7ef;
    border-radius: 50%;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2.5rem;
    color: #0284c7;
}
.sidebar-title {
    font-weight:700; margin-bottom:1.2rem; font-size:1.1rem;
}
.sidebar-menu {
    width: 100%;
    padding: 0;
    margin: 0;
}
.sidebar-menu li {
    list-style: none;
    margin-bottom: 1.1rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    justify-content: space-between;
    color: #22223b;
    font-size: 1rem;
}
.sidebar-menu li:last-child { margin-bottom: 0; }
.sidebar-menu .highlight { color: #1d4ed8; }
.sidebar-menu .menu-icon { margin-left: 0.5rem; color: #0284c7; }
.dashboard-center {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    justify-content: flex-start;
    padding: 1.5rem 1.5rem 0 1.5rem;
    position: relative;
    min-width: 320px;
}
.features-list {
    display: flex;
    flex-direction: column;
    gap: 1.2rem;
    margin-bottom: 1.5rem;
}
.feature-row {
    display: flex;
    align-items: center;
    gap: 1rem;
}
.feature-icon {
    width: 56px;
    height: 56px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #e0f2fe;
    border-radius: 50%;
    font-size: 2.1rem;
    color: #0284c7;
}
.dashboard-summary {
    font-weight: 600;
    margin-top: 1.5rem;
    color: #22223b;
    max-width: 420px;
    text-align: left;
    font-size: 1.05rem;
    z-index: 2;
}
.bubble {
    position: absolute;
    left: 180px;
    top: 30px;
    width: 32px;
    height: 32px;
    background: #3b82f6;
    border-radius: 50%;
    opacity: 0.18;
    z-index: 1;
}
.bubble2 {
    position: absolute;
    left: 220px;
    top: 60px;
    width: 16px;
    height: 16px;
    background: #3b82f6;
    border-radius: 50%;
    opacity: 0.22;
    z-index: 1;
}
.waterdrop {
    position: absolute;
    right: 0;
    bottom: 0;
    width: 260px;
    height: 180px;
    z-index: 0;
    opacity: 0.85;
}
.dashboard-right {
    width: 260px;
    margin-right: 1.5rem;
    display: grid;
    flex-direction: column;
    align-items: flex-start;
    min-width: 200px;
}
.overview-title {
    font-weight: 700;
    font-size: 1.1rem;
    margin-bottom: 1rem;
}
.overview-card {
    background: #e5e7eb;
    border-radius: 8px;
    padding: 0.7rem 1rem;
    margin-bottom: 0.7rem;
    width: 100%;
    font-weight: 600;
    color: #22223b;
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 1rem;
}
.overview-card b { font-weight: 700; }
@media (max-width: 1100px) {
    .dashboard-main { flex-direction: column; }
    .dashboard-sidebar, .dashboard-right { width: 100%; margin: 0 0 1rem 0; min-width: 0; }
    .dashboard-center { padding: 1rem 0.5rem 0 0.5rem; }
}
@media (max-width: 700px) {
    .dashboard-main { flex-direction: column; }
    .dashboard-sidebar, .dashboard-right { width: 100%; margin: 0 0 1rem 0; min-width: 0; }
    .dashboard-center { padding: 1rem 0.2rem 0 0.2rem; }
    .overview-card { font-size: 0.95rem; }
}
</style>

<!-- Top Bar -->
<div class="agent-dashboard-topbar">
    <div class="topbar-left">
        <div class="topbar-home"><i class="fas fa-home"></i></div>
    </div>
    <div class="topbar-right">
        <i class="fas fa-wifi"></i>
        <i class="fas fa-bell"></i>
        <i class="fas fa-signal"></i>
        <i class="fas fa-battery-full"></i>
    </div>
</div>

<div class="dashboard-main">
    <!-- Sidebar -->
    <div class="dashboard-sidebar">
        <div class="profile-icon">
            <i class="fas fa-user"></i>
        </div>
        <div class="sidebar-title">AGENTS PROFILE</div>
        <ul class="sidebar-menu">
            <li>Customer Management <span class="menu-icon"><i class="fas fa-chevron-right"></i></span></li>
            <li>Real-Time Tank Monitoring <span class="menu-icon"><i class="fas fa-chevron-right"></i></span></li>
            <li>Maintenance Scheduling and Tracking <span class="menu-icon"><i class="fas fa-chevron-right"></i></span></li>
            <li>Reporting and Analytics <span class="menu-icon"><i class="fas fa-chevron-right"></i></span></li>
            <li class="highlight">GPS and Route Optimization <span class="menu-icon"><i class="fas fa-chevron-right"></i></span></li>
            <li>Communication and Collaboration Tools <span class="menu-icon"><i class="fas fa-chevron-right"></i></span></li>
        </ul>
    </div>

    <!-- Center Features -->
    <div class="dashboard-center" style="position:relative;">
        <!-- Decorative bubbles -->
        <div class="bubble"></div>
        <div class="bubble2"></div>
        <!-- Features -->
        <div class="features-list">
            <div class="feature-row">
                <span class="feature-icon"><i class="fas fa-map-marker-alt"></i></span>
                <span><b>Tank Location Mapping</b></span>
            </div>
            <div class="feature-row">
                <span class="feature-icon"><i class="fas fa-route"></i></span>
                <span><b>Optimized Routes</b></span>
            </div>
            <div class="feature-row">
                <span class="feature-icon"><i class="fas fa-wifi"></i></span>
                <span><b>Real-Time Tracking</b></span>
            </div>
            <div class="feature-row">
                <span class="feature-icon"><i class="fas fa-sync-alt"></i></span>
                <span><b>Dynamic Updates</b></span>
            </div>
        </div>
        <div class="dashboard-summary">
            Empower your work with real-time data, automated maintenance, and efficient tank management for seamless operations.
        </div>
        <!-- Water drop SVG -->
        <svg class="waterdrop" viewBox="0 0 300 200" fill="none" xmlns="http://www.w3.org/2000/svg">
            <ellipse cx="220" cy="170" rx="80" ry="40" fill="#3b82f6" fill-opacity="0.5"/>
            <ellipse cx="250" cy="180" rx="30" ry="15" fill="#60a5fa" fill-opacity="0.7"/>
            <ellipse cx="180" cy="160" rx="20" ry="10" fill="#2563eb" fill-opacity="0.4"/>
        </svg>
    </div>

    <!-- Right Overview Cards -->
    <div class="dashboard-right">
        <div class="overview-title">Overview Cards:</div>
        <div class="overview-card"><b>Total  clients:</b> <span>{{ $totalClients }}</span></div>
        <div class="overview-card"><b>Alerts Sent:</b> <span>{{ $alertsSent }}</span></div>
        <div class="overview-card"><b>Active Tanks:</b> <span>{{ $activeTanks }}</span></div>
        <div class="overview-card"><b>Maintenance Requests:</b> <span>{{ $maintenanceRequests }}</span></div>
    </div>
</div>
<a href="{{ route('login') }}" class="back-btn">⬅ Log out</a>

@endsection 