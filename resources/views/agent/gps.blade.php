@extends('layouts.app')

@section('content')
<div class="gps-container">
    <div class="page-header">
        <h1>GPS and Route Optimization</h1>
        <div class="header-actions">
            <button class="btn btn-primary" id="optimizeRoutes">
                <i class="fas fa-route"></i> Optimize Routes
            </button>
            <button class="btn btn-secondary" id="exportRoutes">
                <i class="fas fa-download"></i> Export Routes
            </button>
        </div>
    </div>

    <div class="gps-grid">
        <!-- Map Section -->
        <div class="map-section">
            <div id="map" class="map-container"></div>
            <div class="map-controls">
                <button class="btn-icon" title="Zoom In" onclick="map.zoomIn()">
                    <i class="fas fa-plus"></i>
                </button>
                <button class="btn-icon" title="Zoom Out" onclick="map.zoomOut()">
                    <i class="fas fa-minus"></i>
                </button>
                <button class="btn-icon" title="Center Map" onclick="centerMap()">
                    <i class="fas fa-crosshairs"></i>
                </button>
            </div>
        </div>

        <!-- Route List -->
        <div class="route-list">
            <div class="list-header">
                <h3>Today's Routes</h3>
                <div class="list-actions">
                    <select id="routeFilter" class="filter-select">
                        <option value="all">All Routes</option>
                        <option value="pending">Pending</option>
                        <option value="in-progress">In Progress</option>
                        <option value="completed">Completed</option>
                    </select>
                </div>
            </div>
            
            <div class="route-items">
                @foreach($routes ?? [] as $route)
                <div class="route-item" data-route-id="{{ $route->id }}">
                    <div class="route-info">
                        <h4>Route #{{ $route->id }}</h4>
                        <p>{{ $route->stops_count }} stops</p>
                    </div>
                    <div class="route-status">
                        <span class="status-badge {{ $route->status }}">
                            {{ ucfirst($route->status) }}
                        </span>
                    </div>
                    <div class="route-actions">
                        <button class="btn-icon" title="View Route" onclick="viewRoute({{ $route->id }})">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="btn-icon" title="Start Route" onclick="startRoute({{ $route->id }})">
                            <i class="fas fa-play"></i>
                        </button>
                        <button class="btn-icon" title="Edit Route" onclick="editRoute({{ $route->id }})">
                            <i class="fas fa-edit"></i>
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Route Details Panel -->
        <div class="route-details-panel" id="routeDetailsPanel">
            <div class="panel-header">
                <h3>Route Details</h3>
                <button class="btn-icon" onclick="closeRouteDetails()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div class="panel-content">
                <div class="details-section">
                    <h4>Route Information</h4>
                    <div class="info-grid">
                        <div class="info-item">
                            <label>Route ID</label>
                            <span id="routeId">-</span>
                        </div>
                        <div class="info-item">
                            <label>Total Distance</label>
                            <span id="routeDistance">-</span>
                        </div>
                        <div class="info-item">
                            <label>Estimated Time</label>
                            <span id="routeTime">-</span>
                        </div>
                        <div class="info-item">
                            <label>Status</label>
                            <span id="routeStatus">-</span>
                        </div>
                    </div>
                </div>

                <div class="details-section">
                    <h4>Stops</h4>
                    <div class="stops-list" id="routeStops">
                        <!-- Stops will be loaded dynamically -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.gps-container {
    padding: 20px;
}

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.header-actions {
    display: flex;
    gap: 10px;
}

.gps-grid {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 20px;
    height: calc(100vh - 120px);
}

.map-section {
    position: relative;
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    overflow: hidden;
}

.map-container {
    width: 100%;
    height: 100%;
}

.map-controls {
    position: absolute;
    top: 10px;
    right: 10px;
    display: flex;
    flex-direction: column;
    gap: 5px;
    background: white;
    padding: 5px;
    border-radius: 4px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.route-list {
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    display: flex;
    flex-direction: column;
}

.list-header {
    padding: 20px;
    border-bottom: 1px solid #e2e8f0;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.route-items {
    flex: 1;
    overflow-y: auto;
    padding: 10px;
}

.route-item {
    padding: 15px;
    border-bottom: 1px solid #e2e8f0;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.route-info h4 {
    margin: 0;
    font-size: 16px;
}

.route-info p {
    margin: 5px 0 0;
    font-size: 14px;
    color: #64748b;
}

.status-badge {
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 500;
}

.status-badge.pending { background: #fef3c7; color: #92400e; }
.status-badge.in-progress { background: #dbeafe; color: #1e40af; }
.status-badge.completed { background: #dcfce7; color: #166534; }

.route-actions {
    display: flex;
    gap: 5px;
}

.btn-icon {
    background: none;
    border: none;
    padding: 5px;
    cursor: pointer;
    color: #64748b;
    transition: color 0.2s ease;
}

.btn-icon:hover {
    color: #0284c7;
}

.route-details-panel {
    position: fixed;
    right: -400px;
    top: 0;
    width: 400px;
    height: 100vh;
    background: white;
    box-shadow: -2px 0 4px rgba(0,0,0,0.1);
    transition: right 0.3s ease;
    z-index: 1000;
}

.route-details-panel.active {
    right: 0;
}

.panel-header {
    padding: 20px;
    border-bottom: 1px solid #e2e8f0;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.panel-content {
    padding: 20px;
}

.details-section {
    margin-bottom: 30px;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 15px;
}

.info-item label {
    display: block;
    font-size: 12px;
    color: #64748b;
    margin-bottom: 4px;
}

.stops-list {
    display: flex;
    flex-direction: column;
    gap: 10px;
}
</style>

<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />

<script>
let map;
let markers = [];

document.addEventListener('DOMContentLoaded', function() {
    // Initialize map
    map = L.map('map').setView([0, 0], 2);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    // Load initial routes
    loadRoutes();
});

function loadRoutes() {
    // Implement route loading logic
}

function viewRoute(routeId) {
    document.getElementById('routeDetailsPanel').classList.add('active');
    // Load route details via AJAX
}

function closeRouteDetails() {
    document.getElementById('routeDetailsPanel').classList.remove('active');
}

function startRoute(routeId) {
    // Implement route start logic
}

function editRoute(routeId) {
    // Implement route edit logic
}

function centerMap() {
    // Implement map centering logic
}

// Route optimization handler
document.getElementById('optimizeRoutes').addEventListener('click', function() {
    // Implement route optimization logic
});

// Route export handler
document.getElementById('exportRoutes').addEventListener('click', function() {
    // Implement route export logic
});
</script>
@endsection 