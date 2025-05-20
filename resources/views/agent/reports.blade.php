@extends('layouts.app')

@section('content')
<div class="reports-container">
    <div class="page-header">
        <h1>Reports & Analytics</h1>
        <div class="header-actions">
            <button class="btn btn-secondary" id="refreshData">
                <i class="fas fa-sync-alt"></i> Refresh
            </button>
            <button class="btn btn-primary" id="exportReport">
                <i class="fas fa-download"></i> Export Report
            </button>
        </div>
    </div>

    <!-- Date Range Filter -->
    <div class="filter-section">
        <div class="date-range">
            <div class="form-group">
                <label for="startDate">Start Date</label>
                <input type="date" id="startDate" class="form-control">
            </div>
            <div class="form-group">
                <label for="endDate">End Date</label>
                <input type="date" id="endDate" class="form-control">
            </div>
            <button class="btn btn-primary" id="applyDateFilter">Apply Filter</button>
        </div>
        <div class="report-type">
            <select id="reportType" class="form-control">
                <option value="overview">Overview</option>
                <option value="tank_performance">Tank Performance</option>
                <option value="maintenance">Maintenance Analysis</option>
                <option value="alerts">Alert Statistics</option>
                <option value="customer">Customer Insights</option>
            </select>
        </div>
    </div>

    <!-- Overview Cards -->
    <div class="overview-cards">
        <div class="card">
            <div class="card-icon">
                <i class="fas fa-tint"></i>
            </div>
            <div class="card-content">
                <h3>Total Water Usage</h3>
                <p class="value">{{ number_format($totalWaterUsage ?? 0) }} L</p>
                <p class="trend {{ ($waterUsageTrend ?? 0) >= 0 ? 'positive' : 'negative' }}">
                    <i class="fas fa-arrow-{{ ($waterUsageTrend ?? 0) >= 0 ? 'up' : 'down' }}"></i>
                    {{ abs($waterUsageTrend ?? 0) }}%
                </p>
            </div>
        </div>
        <div class="card">
            <div class="card-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="card-content">
                <h3>Total Alerts</h3>
                <p class="value">{{ $totalAlerts ?? 0 }}</p>
                <p class="trend {{ ($alertsTrend ?? 0) >= 0 ? 'positive' : 'negative' }}">
                    <i class="fas fa-arrow-{{ ($alertsTrend ?? 0) >= 0 ? 'up' : 'down' }}"></i>
                    {{ abs($alertsTrend ?? 0) }}%
                </p>
            </div>
        </div>
        <div class="card">
            <div class="card-icon">
                <i class="fas fa-tools"></i>
            </div>
            <div class="card-content">
                <h3>Maintenance Tasks</h3>
                <p class="value">{{ $totalMaintenanceTasks ?? 0 }}</p>
                <p class="trend {{ ($maintenanceTrend ?? 0) >= 0 ? 'positive' : 'negative' }}">
                    <i class="fas fa-arrow-{{ ($maintenanceTrend ?? 0) >= 0 ? 'up' : 'down' }}"></i>
                    {{ abs($maintenanceTrend ?? 0) }}%
                </p>
            </div>
        </div>
        <div class="card">
            <div class="card-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="card-content">
                <h3>Active Customers</h3>
                <p class="value">{{ $activeCustomers ?? 0 }}</p>
                <p class="trend {{ ($customersTrend ?? 0) >= 0 ? 'positive' : 'negative' }}">
                    <i class="fas fa-arrow-{{ ($customersTrend ?? 0) >= 0 ? 'up' : 'down' }}"></i>
                    {{ abs($customersTrend ?? 0) }}%
                </p>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="charts-grid">
        <!-- Water Usage Chart -->
        <div class="chart-container">
            <h3>Water Usage Trends</h3>
            <canvas id="waterUsageChart"></canvas>
        </div>

        <!-- Tank Performance Chart -->
        <div class="chart-container">
            <h3>Tank Performance</h3>
            <canvas id="tankPerformanceChart"></canvas>
        </div>

        <!-- Alert Distribution Chart -->
        <div class="chart-container">
            <h3>Alert Distribution</h3>
            <canvas id="alertDistributionChart"></canvas>
        </div>

        <!-- Maintenance Status Chart -->
        <div class="chart-container">
            <h3>Maintenance Status</h3>
            <canvas id="maintenanceStatusChart"></canvas>
        </div>
    </div>

    <!-- Detailed Analysis Section -->
    <div class="analysis-section">
        <div class="section-header">
            <h2>Detailed Analysis</h2>
            <div class="section-actions">
                <button class="btn btn-secondary" id="toggleAnalysis">
                    <i class="fas fa-chevron-down"></i> Show Details
                </button>
            </div>
        </div>
        <div class="analysis-content" style="display: none;">
            <div class="analysis-grid">
                <div class="analysis-card">
                    <h4>Top Performing Tanks</h4>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Tank ID</th>
                                    <th>Location</th>
                                    <th>Efficiency</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="topTanksTable">
                                <!-- Will be populated dynamically -->
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="analysis-card">
                    <h4>Recent Alerts</h4>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th>Severity</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="recentAlertsTable">
                                <!-- Will be populated dynamically -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.reports-container {
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

/* Filter Section */
.filter-section {
    background: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    margin-bottom: 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.date-range {
    display: flex;
    gap: 15px;
    align-items: flex-end;
}

.form-group {
    margin-bottom: 0;
}

/* Overview Cards */
.overview-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-bottom: 20px;
}

.card {
    background: white;
    border-radius: 8px;
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 15px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.card-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: #e2e8f0;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    color: #0284c7;
}

.card-content h3 {
    margin: 0;
    font-size: 14px;
    color: #64748b;
}

.card-content .value {
    margin: 5px 0;
    font-size: 24px;
    font-weight: bold;
    color: #1e293b;
}

.trend {
    font-size: 12px;
    display: flex;
    align-items: center;
    gap: 5px;
}

.trend.positive { color: #059669; }
.trend.negative { color: #dc2626; }

/* Charts Grid */
.charts-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
    margin-bottom: 20px;
}

.chart-container {
    background: white;
    border-radius: 8px;
    padding: 20px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.chart-container h3 {
    margin: 0 0 15px;
    font-size: 16px;
    color: #1e293b;
}

/* Analysis Section */
.analysis-section {
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.section-header {
    padding: 20px;
    border-bottom: 1px solid #e2e8f0;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.section-header h2 {
    margin: 0;
    font-size: 18px;
}

.analysis-content {
    padding: 20px;
}

.analysis-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
}

.analysis-card {
    background: #f8fafc;
    border-radius: 8px;
    padding: 15px;
}

.analysis-card h4 {
    margin: 0 0 15px;
    font-size: 16px;
    color: #1e293b;
}

.table {
    width: 100%;
    border-collapse: collapse;
}

.table th,
.table td {
    padding: 8px;
    text-align: left;
    border-bottom: 1px solid #e2e8f0;
}

.table th {
    font-weight: 600;
    color: #64748b;
    font-size: 12px;
}

.table td {
    font-size: 14px;
    color: #1e293b;
}

@media (max-width: 1024px) {
    .charts-grid {
        grid-template-columns: 1fr;
    }
    
    .analysis-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .filter-section {
        flex-direction: column;
        gap: 15px;
    }
    
    .date-range {
        flex-direction: column;
        width: 100%;
    }
    
    .form-group {
        width: 100%;
    }
    
    .overview-cards {
        grid-template-columns: 1fr;
    }
}
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Initialize Charts
document.addEventListener('DOMContentLoaded', function() {
    initializeCharts();
    loadData();
    setupEventListeners();
});

function initializeCharts() {
    // Water Usage Chart
    new Chart(document.getElementById('waterUsageChart'), {
        type: 'line',
        data: {
            labels: [],
            datasets: [{
                label: 'Water Usage (L)',
                data: [],
                borderColor: '#0284c7',
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });

    // Tank Performance Chart
    new Chart(document.getElementById('tankPerformanceChart'), {
        type: 'bar',
        data: {
            labels: [],
            datasets: [{
                label: 'Efficiency (%)',
                data: [],
                backgroundColor: '#0284c7'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });

    // Alert Distribution Chart
    new Chart(document.getElementById('alertDistributionChart'), {
        type: 'doughnut',
        data: {
            labels: [],
            datasets: [{
                data: [],
                backgroundColor: ['#ef4444', '#f59e0b', '#10b981']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });

    // Maintenance Status Chart
    new Chart(document.getElementById('maintenanceStatusChart'), {
        type: 'pie',
        data: {
            labels: [],
            datasets: [{
                data: [],
                backgroundColor: ['#f59e0b', '#0284c7', '#10b981']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
}

function loadData() {
    const startDate = document.getElementById('startDate').value;
    const endDate = document.getElementById('endDate').value;
    const reportType = document.getElementById('reportType').value;

    fetch(`/api/reports/data?start_date=${startDate}&end_date=${endDate}&type=${reportType}`)
        .then(response => response.json())
        .then(data => {
            updateCharts(data);
            updateTables(data);
        })
        .catch(error => console.error('Error:', error));
}

function updateCharts(data) {
    // Update Water Usage Chart
    const waterUsageChart = Chart.getChart('waterUsageChart');
    waterUsageChart.data.labels = data.water_usage.labels;
    waterUsageChart.data.datasets[0].data = data.water_usage.data;
    waterUsageChart.update();

    // Update Tank Performance Chart
    const tankPerformanceChart = Chart.getChart('tankPerformanceChart');
    tankPerformanceChart.data.labels = data.tank_performance.labels;
    tankPerformanceChart.data.datasets[0].data = data.tank_performance.data;
    tankPerformanceChart.update();

    // Update Alert Distribution Chart
    const alertDistributionChart = Chart.getChart('alertDistributionChart');
    alertDistributionChart.data.labels = data.alert_distribution.labels;
    alertDistributionChart.data.datasets[0].data = data.alert_distribution.data;
    alertDistributionChart.update();

    // Update Maintenance Status Chart
    const maintenanceStatusChart = Chart.getChart('maintenanceStatusChart');
    maintenanceStatusChart.data.labels = data.maintenance_status.labels;
    maintenanceStatusChart.data.datasets[0].data = data.maintenance_status.data;
    maintenanceStatusChart.update();
}

function updateTables(data) {
    // Update Top Tanks Table
    const topTanksTable = document.getElementById('topTanksTable');
    topTanksTable.innerHTML = data.top_tanks.map(tank => `
        <tr>
            <td>${tank.id}</td>
            <td>${tank.location}</td>
            <td>${tank.efficiency}%</td>
            <td><span class="status-badge ${tank.status}">${tank.status}</span></td>
        </tr>
    `).join('');

    // Update Recent Alerts Table
    const recentAlertsTable = document.getElementById('recentAlertsTable');
    recentAlertsTable.innerHTML = data.recent_alerts.map(alert => `
        <tr>
            <td>${alert.date}</td>
            <td>${alert.type}</td>
            <td><span class="severity-badge ${alert.severity}">${alert.severity}</span></td>
            <td><span class="status-badge ${alert.status}">${alert.status}</span></td>
        </tr>
    `).join('');
}

function setupEventListeners() {
    // Date Filter
    document.getElementById('applyDateFilter').addEventListener('click', loadData);

    // Report Type Change
    document.getElementById('reportType').addEventListener('change', loadData);

    // Refresh Button
    document.getElementById('refreshData').addEventListener('click', loadData);

    // Export Button
    document.getElementById('exportReport').addEventListener('click', exportReport);

    // Toggle Analysis Section
    document.getElementById('toggleAnalysis').addEventListener('click', function() {
        const content = document.querySelector('.analysis-content');
        const icon = this.querySelector('i');
        if (content.style.display === 'none') {
            content.style.display = 'block';
            icon.classList.replace('fa-chevron-down', 'fa-chevron-up');
        } else {
            content.style.display = 'none';
            icon.classList.replace('fa-chevron-up', 'fa-chevron-down');
        }
    });
}

function exportReport() {
    const startDate = document.getElementById('startDate').value;
    const endDate = document.getElementById('endDate').value;
    const reportType = document.getElementById('reportType').value;

    window.location.href = `/api/reports/export?start_date=${startDate}&end_date=${endDate}&type=${reportType}`;
}
</script>
@endsection 