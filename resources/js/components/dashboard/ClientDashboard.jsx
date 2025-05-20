import React, { useState, useEffect } from 'react';
import { 
    WrenchIcon, 
    QuestionMarkCircleIcon, 
    ExclamationTriangleIcon,
    ChartBarIcon,
    BellIcon,
    LightBulbIcon,
    ClipboardDocumentListIcon,
    DocumentChartBarIcon,
    UserCircleIcon
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

const ClientDashboard = () => {
    const [notifications, setNotifications] = useState(0);
    const [requests, setRequests] = useState([
        {
            id: 1,
            title: 'Request Update',
            description: 'Your request has been completed',
            date: 'Apr 21, 2025 15:19',
            status: 'completed'
        }
    ]);

    const quickActions = [
        { name: 'Maintenance', icon: WrenchIcon, color: 'bg-yellow-500 hover:bg-yellow-600' },
        { name: 'Support', icon: QuestionMarkCircleIcon, color: 'bg-green-500 hover:bg-green-600' },
        { name: 'Emergency', icon: ExclamationTriangleIcon, color: 'bg-red-500 hover:bg-red-600' }
    ];

    const navigationItems = [
        { name: 'Real-Time Monitoring', icon: ChartBarIcon, count: null },
        { name: 'Smart Alerts', icon: BellIcon, count: notifications },
        { name: 'AI Predictions', icon: LightBulbIcon, count: null },
        { name: 'Recommendations', icon: ClipboardDocumentListIcon, count: null },
        { name: 'Maintenance', icon: WrenchIcon, count: null },
        { name: 'Reports', icon: DocumentChartBarIcon, count: null }
    ];

    const chartData = {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
        datasets: [
            {
                label: 'Tank Level',
                data: [65, 59, 80, 81, 56, 55],
                fill: true,
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.4
            }
        ]
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

    const handleQuickAction = (action) => {
        console.log(`Quick action clicked: ${action}`);
        // Implement action handling
    };

    return (
        <div className="min-h-screen bg-gray-50">
            {/* Header */}
            <header className="bg-white shadow-sm">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                    <div className="flex justify-between items-center">
                        <h1 className="text-2xl font-bold text-gray-900">Client Dashboard</h1>
                        <div className="flex items-center space-x-4">
                            <button className="relative p-2 rounded-full hover:bg-gray-100 transition-colors">
                                <BellIcon className="h-6 w-6 text-gray-500" />
                                {notifications > 0 && (
                                    <span className="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-500 rounded-full">
                                        {notifications}
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
                {/* Quick Actions */}
                <div className="mb-8">
                    <h2 className="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h2>
                    <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
                        {quickActions.map((action) => (
                            <button
                                key={action.name}
                                onClick={() => handleQuickAction(action.name)}
                                className={`${action.color} text-white p-4 rounded-lg flex items-center space-x-3 transition-all duration-200 transform hover:scale-105`}
                            >
                                <action.icon className="h-6 w-6" />
                                <span className="font-medium">{action.name}</span>
                            </button>
                        ))}
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
                                {item.count !== null && (
                                    <span className="ml-auto bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                        {item.count}
                                    </span>
                                )}
                            </div>
                        </div>
                    ))}
                </div>

                {/* Charts and Requests Section */}
                <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    {/* Tank Level Chart */}
                    <div className="lg:col-span-2 bg-white rounded-lg shadow-sm p-6">
                        <h3 className="text-lg font-semibold text-gray-900 mb-4">Tank Level History</h3>
                        <div className="h-64">
                            <Line data={chartData} options={chartOptions} />
                        </div>
                    </div>

                    {/* My Requests */}
                    <div className="bg-white rounded-lg shadow-sm">
                        <div className="px-6 py-4 border-b border-gray-200">
                            <h3 className="text-lg font-semibold text-gray-900">My Requests</h3>
                        </div>
                        <div className="divide-y divide-gray-200">
                            {requests.map((request) => (
                                <div key={request.id} className="px-6 py-4">
                                    <div className="flex items-center justify-between">
                                        <div>
                                            <p className="text-sm font-medium text-gray-900">{request.title}</p>
                                            <p className="text-sm text-gray-500">{request.description}</p>
                                        </div>
                                        <div className="text-sm text-gray-500">{request.date}</div>
                                    </div>
                                </div>
                            ))}
                        </div>
                    </div>
                </div>

                {/* Send Request Button */}
                <button className="fixed bottom-8 right-8 bg-blue-600 text-white px-6 py-3 rounded-full shadow-lg hover:bg-blue-700 transition-all duration-200 transform hover:scale-105 flex items-center space-x-2">
                    <WrenchIcon className="h-5 w-5" />
                    <span>Send Request</span>
                </button>
            </main>
        </div>
    );
};

export default ClientDashboard; 