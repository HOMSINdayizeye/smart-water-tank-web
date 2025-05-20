import React, { useState, useEffect } from 'react';
import { 
    UsersIcon,
    ChartBarIcon,
    CalendarIcon,
    DocumentTextIcon,
    MapIcon,
    ChatBubbleLeftRightIcon,
    BellIcon,
    UserCircleIcon,
    ArrowTrendingUpIcon,
    ExclamationTriangleIcon,
    UserPlusIcon
} from '@heroicons/react/24/outline';
import { Line, Doughnut } from 'react-chartjs-2';
import {
    Chart as ChartJS,
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    Title,
    Tooltip,
    Legend,
    ArcElement
} from 'chart.js';

ChartJS.register(
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    Title,
    Tooltip,
    Legend,
    ArcElement
);

const AgentDashboard = () => {
    const [stats, setStats] = useState({
        totalCustomers: 0,
        activeCustomers: 0,
        totalTanks: 0,
        monitoringTanks: 0,
        pendingTasks: 0,
        dueToday: 0,
        totalAlerts: 0,
        criticalAlerts: 0
    });

    const navigationItems = [
        { name: 'Customer Management', icon: UsersIcon, count: stats.totalCustomers },
        { name: 'Real-Time Monitoring', icon: ChartBarIcon, count: stats.monitoringTanks },
        { name: 'Maintenance', icon: CalendarIcon, count: stats.pendingTasks },
        { name: 'Reports & Analytics', icon: DocumentTextIcon },
        { name: 'GPS & Routes', icon: MapIcon },
        { name: 'Communication', icon: ChatBubbleLeftRightIcon, count: 0 },
        { name: 'Add New Client', icon: UserPlusIcon, count: null }
    ];

    const customerGrowthData = {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
        datasets: [
            {
                label: 'Total Customers',
                data: [65, 59, 80, 81, 56, 55],
                fill: true,
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.4
            }
        ]
    };

    const taskDistributionData = {
        labels: ['Maintenance', 'Installations', 'Inspections', 'Repairs'],
        datasets: [{
            data: [30, 25, 20, 25],
            backgroundColor: [
                '#0284c7',
                '#22c55e',
                '#facc15',
                '#ef4444'
            ]
        }]
    };

    const chartOptions = {
        responsive: true,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: {
                    display: true,
                    color: 'rgba(0, 0, 0, 0.1)'
                }
            },
            x: {
                grid: {
                    display: false
                }
            }
        }
    };

    const [clients, setClients] = useState([]);
    const [maintenanceRequests, setMaintenanceRequests] = useState([]);
    const [agentNotifications, setAgentNotifications] = useState(0);
    const [agentAlerts, setAgentAlerts] = useState(0);

    useEffect(() => {
        fetchAgentDashboardData();
    }, []);

    const fetchAgentDashboardData = async () => {
        try {
            const response = await fetch('/api/agent/dashboard');
            const data = await response.json();
            console.log('Fetched agent data:', data);
            setStats(data.stats);
            setClients(data.clients);
            setMaintenanceRequests(data.maintenanceRequests);
            setAgentNotifications(data.notificationCount);
            setAgentAlerts(data.criticalAlertCount);
        } catch (error) {
            console.error('Error fetching agent dashboard data:', error);
        }
    };

    const handleAddClient = () => {
        console.log('Add Client button clicked');
    };

    const handleViewClient = (clientId) => {
        console.log(`View Client: ${clientId}`);
    };

    const handleViewMaintenanceRequest = (requestId) => {
        console.log(`View Maintenance Request: ${requestId}`);
    };

    const handleAssignMaintenance = (requestId) => {
        console.log(`Assign Maintenance: ${requestId}`);
    };

    const handleSendNotification = (clientId) => {
        console.log(`Send Notification to: ${clientId}`);
    };

    return (
        <div className="min-h-screen bg-gray-50">
            {/* Header */}
            <header className="bg-white shadow-sm">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                    <div className="flex justify-between items-center">
                        <h1 className="text-2xl font-bold text-gray-900">Agent Dashboard</h1>
                        <div className="flex items-center space-x-4">
                            <button className="relative p-2 rounded-full hover:bg-gray-100 transition-colors">
                                <BellIcon className="h-6 w-6 text-gray-500" />
                                {agentNotifications > 0 && (
                                    <span className="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-500 rounded-full">
                                        {agentNotifications}
                                    </span>
                                )}
                            </button>
                            <div className="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                                <UserCircleIcon className="h-8 w-8 text-gray-500" />
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <main className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                {/* Overview Cards */}
                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                    <div className="bg-blue-600 text-white rounded-lg p-6 shadow-sm">
                        <div className="flex items-center justify-between">
                            <div>
                                <p className="text-sm font-medium">Total Customers</p>
                                <p className="text-2xl font-bold">{stats.totalCustomers}</p>
                                <p className="text-sm opacity-75">Active: {stats.activeCustomers}</p>
                            </div>
                            <UsersIcon className="h-8 w-8 opacity-75" />
                        </div>
                    </div>
                    <div className="bg-green-600 text-white rounded-lg p-6 shadow-sm">
                        <div className="flex items-center justify-between">
                            <div>
                                <p className="text-sm font-medium">Active Tanks</p>
                                <p className="text-2xl font-bold">{stats.totalTanks}</p>
                                <p className="text-sm opacity-75">Monitoring: {stats.monitoringTanks}</p>
                            </div>
                            <ChartBarIcon className="h-8 w-8 opacity-75" />
                        </div>
                    </div>
                    <div className="bg-yellow-600 text-white rounded-lg p-6 shadow-sm">
                        <div className="flex items-center justify-between">
                            <div>
                                <p className="text-sm font-medium">Pending Tasks</p>
                                <p className="text-2xl font-bold">{stats.pendingTasks}</p>
                                <p className="text-sm opacity-75">Due today: {stats.dueToday}</p>
                            </div>
                            <CalendarIcon className="h-8 w-8 opacity-75" />
                        </div>
                    </div>
                    <div className="bg-red-600 text-white rounded-lg p-6 shadow-sm">
                        <div className="flex items-center justify-between">
                            <div>
                                <p className="text-sm font-medium">Alerts</p>
                                <p className="text-2xl font-bold">{stats.totalAlerts}</p>
                                <p className="text-sm opacity-75">Critical: {stats.criticalAlerts}</p>
                            </div>
                            <ExclamationTriangleIcon className="h-8 w-8 opacity-75" />
                        </div>
                    </div>
                </div>

                {/* Quick Action: Add New Client */}
                <div className="mb-8">
                    <h2 className="text-lg font-semibold text-gray-900 mb-4">Actions</h2>
                    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <button
                            onClick={handleAddClient}
                            className="bg-blue-600 hover:bg-blue-700 text-white p-4 rounded-lg flex items-center space-x-3 transition-all duration-200 transform hover:scale-105"
                        >
                            <UserPlusIcon className="h-6 w-6" />
                            <span className="font-medium">Add New Client</span>
                        </button>
                    </div>
                </div>

                {/* Navigation Grid */}
                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-8">
                    {navigationItems.map((item) => (
                        <div
                            key={item.name}
                            className="bg-white p-4 rounded-lg shadow-sm hover:shadow-md transition-all duration-200 cursor-pointer transform hover:-translate-y-1"
                        >
                            <div className="flex items-center space-x-3">
                                <item.icon className="h-6 w-6 text-blue-500" />
                                <span className="font-medium text-gray-900">{item.name}</span>
                                {item.count !== undefined && item.count !== null && (
                                    <span className="ml-auto bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                        {item.count}
                                    </span>
                                )}
                            </div>
                        </div>
                    ))}
                </div>

                {/* Sections for Assigned Clients, Maintenance Requests, Notifications, Alerts */}
                <div className="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                    {/* Assigned Clients List */}
                    <div className="bg-white rounded-lg shadow-sm p-6">
                        <h3 className="text-lg font-semibold text-gray-900 mb-4">My Clients</h3>
                        <p className="text-gray-500">Client list goes here...</p>
                        {clients.map((client) => (
                            <div key={client.id} className="border-b py-2 flex justify-between items-center">
                                <div>{client.name}</div>
                                <button onClick={() => handleViewClient(client.id)} className="text-blue-600 text-sm">View</button>
                            </div>
                        ))}
                    </div>

                    {/* Recent Maintenance Requests from Clients */}
                    <div className="bg-white rounded-lg shadow-sm p-6">
                        <h3 className="text-lg font-semibold text-gray-900 mb-4">Client Maintenance Requests</h3>
                        <p className="text-gray-500">Maintenance requests go here...</p>
                        {maintenanceRequests.map((request) => (
                            <div key={request.id} className="border-b py-2 flex justify-between items-center">
                                <div>{request.title}</div>
                                <button onClick={() => handleViewMaintenanceRequest(request.id)} className="text-blue-600 text-sm mr-2">View</button>
                                <button onClick={() => handleAssignMaintenance(request.id)} className="text-green-600 text-sm">Assign</button>
                            </div>
                        ))}
                    </div>
                </div>

                {/* Sections for Notifications and Alerts */}
                <div className="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    {/* Notifications from Clients/System */}
                    <div className="bg-white rounded-lg shadow-sm p-6">
                        <h3 className="text-lg font-semibold text-gray-900 mb-4">Recent Notifications</h3>
                        <p className="text-gray-500">Notifications go here...</p>
                        {/* Example notification item */}
                        <div className="border-b py-2">Notification message...</div>
                    </div>

                    {/* Alerts from Clients/Tanks */}
                    <div className="bg-white rounded-lg shadow-sm p-6">
                        <h3 className="text-lg font-semibold text-gray-900 mb-4">Recent Alerts</h3>
                        <p className="text-gray-500">Alerts go here...</p>
                        {/* Example alert item */}
                        <div className="border-b py-2 text-red-600">Critical Alert message...</div>
                    </div>
                </div>
            </main>
        </div>
    );
};

export default AgentDashboard; 