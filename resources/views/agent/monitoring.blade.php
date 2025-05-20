@extends('layouts.app')

@section('content')
<div class="monitoring-container">
    <div class="page-header">
        <h1>Real-Time Tank Monitoring</h1>
        <div class="header-actions">
            <button class="btn btn-secondary" id="refreshData">
                <i class="fas fa-sync-alt"></i> Refresh
            </button>
            <button class="btn btn-primary" id="exportData">
                <i class="fas fa-download"></i> Export Data
            </button>
        </div>
    </div>

    <!-- Status Overview -->
    <div class="status-overview">
        <div class="status-card">
            <div class="status-icon active">
                <i class="fas fa-tint"></i>
            </div>
            <div class="status-info">
                <h3>Active Tanks</h3>
                <p class="status-value">{{ $activeTanksCount ?? 0 }}</p>
            </div>
        </div>
        <div class="status-card">
            <div class="status-icon maintenance">
                <i class="fas fa-tools"></i>
            </div>
            <div class="status-info">
                <h3>Maintenance Needed</h3>
                <p class="status-value">{{ $maintenanceNeededCount ?? 0 }}</p>
            </div>
        </div>
        <div class="status-card">
            <div class="status-icon alert">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="status-info">
                <h3>Critical Alerts</h3>
                <p class="status-value">{{ $criticalAlertsCount ?? 0 }}</p>
            </div>
        </div>
    </div>

    <!-- Monitoring Grid -->
    <div class="monitoring-grid">
        <!-- Tank List -->
        <div class="tank-list-section">
            <div class="section-header">
                <h2>Tank List</h2>
                <div class="list-filters">
                    <div class="search-box">
                        <i class="fas fa-search"></i>
                        <input type="text" id="tankSearch" placeholder="Search tanks...">
                    </div>
                    <select id="statusFilter" class="filter-select">
                        <option value="all">All Status</option>
                        <option value="active">Active</option>
                        <option value="maintenance">Maintenance</option>
                        <option value="critical">Critical</option>
                    </select>
                </div>
            </div>

            <div class="tank-list" id="tankList">
                @foreach($tanks as $tank)
                <div class="tank-item" data-tank-id="{{ $tank->id }}" onclick="viewTankDetails({{ $tank->id }})">
                    <div class="tank-info">
                        <div class="tank-icon">
                            <i class="fas fa-tint"></i>
                        </div>
                        <div class="tank-details">
                            <h4>{{ $tank->name }}</h4>
                            <p>{{ $tank->location }}</p>
                        </div>
                    </div>
                    <div class="tank-status">
                        <div class="water-level">
                            <div class="level-bar">
                                <div class="level-fill" style="width: {{ $tank->water_level }}%"></div>
                            </div>
                            <span>{{ $tank->water_level }}%</span>
                        </div>
                        <span class="status-badge {{ $tank->status }}">
                            {{ ucfirst($tank->status) }}
                        </span>
                    </div>
                    <div class="tank-actions">
                        <button class="btn-icon" title="Schedule Maintenance" onclick="scheduleMaintenance({{ $tank->id }})">
                            <i class="fas fa-calendar-plus"></i>
                        </button>
                        <button class="btn-icon" title="Send Alert" onclick="sendAlert({{ $tank->id }})">
                            <i class="fas fa-bell"></i>
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Tank Details Panel -->
        <div class="tank-details-panel" id="tankDetailsPanel">
            <div class="panel-header">
                <h3>Tank Details</h3>
                <button class="btn-icon" onclick="closeTankDetails()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div class="panel-content">
                <div class="details-section">
                    <h4>Tank Information</h4>
                    <div class="info-grid">
                        <div class="info-item">
                            <label>Tank ID</label>
                            <span id="tankId">-</span>
                        </div>
                        <div class="info-item">
                            <label>Name</label>
                            <span id="tankName">-</span>
                        </div>
                        <div class="info-item">
                            <label>Location</label>
                            <span id="tankLocation">-</span>
                        </div>
                        <div class="info-item">
                            <label>Capacity</label>
                            <span id="tankCapacity">-</span>
                        </div>
                        <div class="info-item">
                            <label>Current Level</label>
                            <span id="tankLevel">-</span>
                        </div>
                        <div class="info-item">
                            <label>Status</label>
                            <span id="tankStatus">-</span>
                        </div>
                    </div>
                </div>

                <div class="details-section">
                    <h4>Water Level History</h4>
                    <canvas id="waterLevelChart"></canvas>
                </div>

                <div class="details-section">
                    <h4>Recent Activity</h4>
                    <div class="activity-list" id="tankActivity">
                        <!-- Activity items will be loaded dynamically -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.monitoring-container {
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

/* Status Overview */
.status-overview {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.status-card {
    background: white;
    border-radius: 8px;
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 15px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.status-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
}

.status-icon.active { background: #dcfce7; color: #166534; }
.status-icon.maintenance { background: #fef3c7; color: #92400e; }
.status-icon.alert { background: #fee2e2; color: #991b1b; }

.status-info h3 {
    margin: 0;
    font-size: 14px;
    color: #64748b;
}

.status-value {
    margin: 5px 0 0;
    font-size: 24px;
    font-weight: bold;
    color: #1e293b;
}

/* Monitoring Grid */
.monitoring-grid {
    display: grid;
    grid-template-columns: 1fr 350px;
    gap: 20px;
}

/* Tank List */
.tank-list-section {
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.section-header {
    padding: 20px;
    border-bottom: 1px solid #e2e8f0;
}

.section-header h2 {
    margin: 0 0 15px;
    font-size: 18px;
}

.list-filters {
    display: flex;
    gap: 10px;
}

.search-box {
    position: relative;
    flex: 1;
}

.search-box input {
    width: 100%;
    padding: 8px 8px 8px 35px;
    border: 1px solid #e2e8f0;
    border-radius: 4px;
}

.search-box i {
    position: absolute;
    left: 10px;
    top: 50%;
    transform: translateY(-50%);
    color: #64748b;
}

.filter-select {
    padding: 8px;
    border: 1px solid #e2e8f0;
    border-radius: 4px;
    min-width: 120px;
}

.tank-list {
    padding: 20px;
}

.tank-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 15px;
    border-bottom: 1px solid #e2e8f0;
    cursor: pointer;
    transition: background-color 0.2s ease;
}

.tank-item:hover {
    background-color: #f8fafc;
}

.tank-info {
    display: flex;
    align-items: center;
    gap: 10px;
}

.tank-icon {
    width: 40px;
    height: 40px;
    background: #e2e8f0;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #64748b;
}

.tank-details h4 {
    margin: 0;
    font-size: 14px;
}

.tank-details p {
    margin: 5px 0 0;
    font-size: 12px;
    color: #64748b;
}

.tank-status {
    display: flex;
    align-items: center;
    gap: 15px;
}

.water-level {
    display: flex;
    align-items: center;
    gap: 10px;
}

.level-bar {
    width: 100px;
    height: 6px;
    background: #e2e8f0;
    border-radius: 3px;
    overflow: hidden;
}

.level-fill {
    height: 100%;
    background: #0284c7;
    transition: width 0.3s ease;
}

.tank-actions {
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

/* Tank Details Panel */
.tank-details-panel {
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    display: flex;
    flex-direction: column;
}

.panel-header {
    padding: 20px;
    border-bottom: 1px solid #e2e8f0;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.panel-header h3 {
    margin: 0;
    font-size: 18px;
}

.panel-content {
    padding: 20px;
    overflow-y: auto;
}

.details-section {
    margin-bottom: 30px;
}

.details-section h4 {
    margin: 0 0 15px;
    font-size: 16px;
    color: #1e293b;
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

.info-item span {
    font-size: 14px;
    color: #1e293b;
}

/* Status Badges */
.status-badge {
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 500;
}

.status-badge.active { background: #dcfce7; color: #166534; }
.status-badge.maintenance { background: #fef3c7; color: #92400e; }
.status-badge.critical { background: #fee2e2; color: #991b1b; }

/* Activity List */
.activity-list {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.activity-item {
    padding: 10px;
    background: #f8fafc;
    border-radius: 4px;
    font-size: 14px;
}

.activity-item .time {
    font-size: 12px;
    color: #64748b;
    margin-top: 5px;
}

@media (max-width: 1024px) {
    .monitoring-grid {
        grid-template-columns: 1fr;
    }
    
    .tank-details-panel {
        display: none;
    }
}

@media (max-width: 768px) {
    .status-overview {
        grid-template-columns: 1fr;
    }
    
    .tank-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }
    
    .tank-status {
        width: 100%;
        justify-content: space-between;
    }
    
    .tank-actions {
        width: 100%;
        justify-content: flex-end;
    }
}
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
let waterLevelChart = null;

// Tank Management Functions
function viewTankDetails(tankId) {
    fetch(`/api/tanks/${tankId}`)
        .then(response => response.json())
        .then(data => {
            updateTankDetails(data);
            document.getElementById('tankDetailsPanel').style.display = 'flex';
        })
        .catch(error => console.error('Error:', error));
}

function closeTankDetails() {
    document.getElementById('tankDetailsPanel').style.display = 'none';
}

function updateTankDetails(data) {
    // Update basic information
    document.getElementById('tankId').textContent = data.tank.id;
    document.getElementById('tankName').textContent = data.tank.name;
    document.getElementById('tankLocation').textContent = data.tank.location;
    document.getElementById('tankCapacity').textContent = `${data.tank.capacity}L`;
    document.getElementById('tankLevel').textContent = `${data.tank.water_level}%`;
    document.getElementById('tankStatus').textContent = data.tank.status;

    // Update water level chart
    updateWaterLevelChart(data.water_level_history);

    // Update activity list
    updateActivityList(data.recent_activity);
}

function updateWaterLevelChart(history) {
    const ctx = document.getElementById('waterLevelChart').getContext('2d');
    
    if (waterLevelChart) {
        waterLevelChart.destroy();
    }

    waterLevelChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: history.map(h => h.timestamp),
            datasets: [{
                label: 'Water Level',
                data: history.map(h => h.level),
                borderColor: '#0284c7',
                tension: 0.4,
                fill: false
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    title: {
                        display: true,
                        text: 'Water Level (%)'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Time'
                    }
                }
            }
        }
    });
}

function updateActivityList(activities) {
    const activityList = document.getElementById('tankActivity');
    activityList.innerHTML = activities.map(activity => `
        <div class="activity-item">
            <div class="message">${activity.message}</div>
            <div class="time">${activity.timestamp}</div>
        </div>
    `).join('');
}

function scheduleMaintenance(tankId) {
    // Implement maintenance scheduling
    console.log('Schedule maintenance for tank:', tankId);
}

function sendAlert(tankId) {
    // Implement alert sending
    console.log('Send alert for tank:', tankId);
}

// Search and Filter Functions
document.getElementById('tankSearch').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    filterTanks(searchTerm);
});

document.getElementById('statusFilter').addEventListener('change', function(e) {
    const status = e.target.value;
    filterTanks();
});

function filterTanks(searchTerm = '') {
    const status = document.getElementById('statusFilter').value;
    const tankItems = document.querySelectorAll('.tank-item');

    tankItems.forEach(item => {
        const name = item.querySelector('h4').textContent.toLowerCase();
        const itemStatus = item.querySelector('.status-badge').classList[1];
        const matchesSearch = name.includes(searchTerm);
        const matchesStatus = status === 'all' || itemStatus === status;

        item.style.display = matchesSearch && matchesStatus ? 'flex' : 'none';
    });
}

// Auto-refresh data every 30 seconds
setInterval(() => {
    fetch('/api/tanks/status')
        .then(response => response.json())
        .then(data => {
            updateTankList(data.tanks);
        })
        .catch(error => console.error('Error:', error));
}, 30000);

function updateTankList(tanks) {
    const tankList = document.getElementById('tankList');
    tankList.innerHTML = tanks.map(tank => `
        <div class="tank-item" data-tank-id="${tank.id}" onclick="viewTankDetails(${tank.id})">
            <div class="tank-info">
                <div class="tank-icon">
                    <i class="fas fa-tint"></i>
                </div>
                <div class="tank-details">
                    <h4>${tank.name}</h4>
                    <p>${tank.location}</p>
                </div>
            </div>
            <div class="tank-status">
                <div class="water-level">
                    <div class="level-bar">
                        <div class="level-fill" style="width: ${tank.water_level}%"></div>
                    </div>
                    <span>${tank.water_level}%</span>
                </div>
                <span class="status-badge ${tank.status}">
                    ${tank.status.charAt(0).toUpperCase() + tank.status.slice(1)}
                </span>
            </div>
            <div class="tank-actions">
                <button class="btn-icon" title="Schedule Maintenance" onclick="scheduleMaintenance(${tank.id})">
                    <i class="fas fa-calendar-plus"></i>
                </button>
                <button class="btn-icon" title="Send Alert" onclick="sendAlert(${tank.id})">
                    <i class="fas fa-bell"></i>
                </button>
            </div>
        </div>
    `).join('');
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    // Refresh button click handler
    document.getElementById('refreshData').addEventListener('click', function() {
        fetch('/api/tanks/status')
            .then(response => response.json())
            .then(data => {
                updateTankList(data.tanks);
            })
            .catch(error => console.error('Error:', error));
    });

    // Export button click handler
    document.getElementById('exportData').addEventListener('click', function() {
        window.location.href = '/api/tanks/export';
    });
});
</script>
@endsection 