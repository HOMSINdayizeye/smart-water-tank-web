import React, { useState, useEffect } from 'react';
import { 
    UsersIcon,
    UserPlusIcon,
    BellIcon,
    WrenchIcon,
    ShieldCheckIcon,
    ChartBarIcon,
    ExclamationTriangleIcon,
    UserCircleIcon,
    CogIcon,
    ClipboardDocumentListIcon,
    ArrowRightOnReactorIcon
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
import { useNavigate } from 'react-router-dom';
import styles from './AdminDashboard.module.css';

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

const AdminDashboard = () => {
    const navigate = useNavigate();

    const [stats, setStats] = useState({
        totalUsers: 0,
        totalAgents: 0,
        totalClients: 0,
        totalMaintenance: 0,
        pendingMaintenance: 0,
        totalAlerts: 0,
        criticalAlerts: 0,
        totalNotifications: 0
    });

    const [users, setUsers] = useState([]);
    const [notifications, setNotifications] = useState([]);
    const [alerts, setAlerts] = useState([]);
    const [maintenance, setMaintenance] = useState([]);
    const [permissions, setPermissions] = useState([
        { id: 1, name: 'delete_clients', description: 'Allows deleting client accounts', category: 'Client Management' },
        { id: 2, name: 'edit_client_info', description: 'Allows editing client information', category: 'Client Management' },
        { id: 3, name: 'view_client_info', description: 'Allows viewing client information', category: 'Client Management' },
        { id: 4, name: 'edit_tank_info', description: 'Allows editing tank information', category: 'Tank Management' },
        { id: 5, name: 'view_notifications', description: 'Allows viewing notifications', category: 'Notifications' },
        { id: 6, name: 'manage_maintenance', description: 'Allows managing maintenance requests', category: 'Maintenance' },
        { id: 7, name: 'view_reports', description: 'Allows viewing reports', category: 'Reports' },
        { id: 8, name: 'edit_reports', description: 'Allows editing reports', category: 'Reports' },
        { id: 9, name: 'manage_alerts', description: 'Allows managing alerts', category: 'Alerts' },
        { id: 10, name: 'view_alerts', description: 'Allows viewing alerts', category: 'Alerts' },
        { id: 11, name: 'can_manage_users', description: 'Allows managing users (create, edit, delete)', category: 'User Management' },
        { id: 12, name: 'can_create_clients', description: 'Allows creating new client accounts', category: 'Client Management' },
        { id: 13, name: 'view_users', description: 'Allows viewing users', category: 'User Management' },
        { id: 14, name: 'manage_settings', description: 'Allows managing application settings', category: 'Settings' },
        { id: 15, name: 'view_settings', description: 'Allows viewing application settings', category: 'Settings' }
    ]);
    const [selectedAgent, setSelectedAgent] = useState(null);
    const [agentPermissions, setAgentPermissions] = useState({});
    const [isLoadingPermissions, setIsLoadingPermissions] = useState(false);
    const [permissionError, setPermissionError] = useState(null);
    const [permissionSuccess, setPermissionSuccess] = useState(false);

    const navigationItems = [
        { name: 'User Management', icon: UsersIcon, count: stats.totalUsers },
        { name: 'Notifications', icon: BellIcon, count: stats.totalNotifications },
        { name: 'Alerts', icon: ExclamationTriangleIcon, count: stats.totalAlerts },
        { name: 'Maintenance', icon: WrenchIcon, count: stats.pendingMaintenance },
        { name: 'Permissions Management', icon: ShieldCheckIcon },
        { name: 'Analytics', icon: ChartBarIcon }
    ];

    useEffect(() => {
        fetchDashboardData();
        fetchPermissions();
    }, []);

    useEffect(() => {
        if (selectedAgent) {
            fetchAgentPermissions(selectedAgent.id);
        } else {
            setAgentPermissions({});
        }
    }, [selectedAgent]);

    const fetchDashboardData = async () => {
        try {
            const response = await fetch('/api/admin/dashboard');
            if (!response.ok) {
                 if (response.status === 401 || response.status === 403) {
                     console.error('Unauthorized access. Redirecting to login.');
                     navigate('/login');
                 }
                 throw new Error(`HTTP error! status: ${response.status}`);
             }
            const data = await response.json();
            setStats(data.stats);
            setUsers(data.users);
            setNotifications(data.notifications);
            setAlerts(data.alerts);
            setMaintenance(data.maintenance);
        } catch (error) {
            console.error('Error fetching dashboard data:', error);
        }
    };

    const fetchPermissions = async () => {
        try {
            const response = await fetch('/api/admin/permissions');
             if (!response.ok) {
                 if (response.status === 401 || response.status === 403) {
                     console.error('Unauthorized to fetch permissions. Redirecting to login.');
                     navigate('/login');
                 }
                 throw new Error(`HTTP error! status: ${response.status}`);
             }
            const data = await response.json();
            setPermissions(data);
        } catch (error) {
            console.error('Error fetching permissions:', error);
        }
    };

     const fetchAgentPermissions = async (agentId) => {
         setIsLoadingPermissions(true);
         setPermissionError(null);
         try {
             const response = await fetch(`/api/admin/users/${agentId}/permissions`);
              if (!response.ok) {
                 if (response.status === 401 || response.status === 403) {
                     console.error('Unauthorized to fetch agent permissions. Redirecting to login.');
                     navigate('/login');
                 }
                 throw new Error(`HTTP error! status: ${response.status}`);
             }
             const data = await response.json();
             const permsObject = data.reduce((obj, perm) => {
                 obj[perm.id] = true;
                 return obj;
             }, {});
             setAgentPermissions(permsObject);
         } catch (error) {
             console.error('Error fetching agent permissions:', error);
             setPermissionError('Failed to load permissions. Please try again.');
         } finally {
             setIsLoadingPermissions(false);
         }
     };

    const handleCreateUser = async (userData) => {
        try {
            const response = await fetch('/api/admin/users', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(userData),
            });
            if (response.ok) {
                fetchDashboardData();
            } else if (response.status === 401 || response.status === 403) {
                 console.error('Unauthorized to create user. Redirecting to login.');
                 navigate('/login');
            } else {
                console.error('Error creating user:', response.statusText);
            }
        } catch (error) {
            console.error('Error creating user:', error);
        }
    };

    const handleAssignMaintenance = async (maintenanceId, teamId) => {
        try {
            const response = await fetch(`/api/admin/maintenance/${maintenanceId}/assign`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ teamId }),
            });
            if (response.ok) {
                fetchDashboardData();
            } else if (response.status === 401 || response.status === 403) {
                 console.error('Unauthorized to assign maintenance. Redirecting to login.');
                 navigate('/login');
            } else {
                console.error('Error assigning maintenance:', response.statusText);
            }
        } catch (error) {
            console.error('Error assigning maintenance:', error);
        }
    };

    const handleUpdateUserRole = async (userId, newRole) => {
        try {
            const response = await fetch(`/api/admin/users/${userId}/role`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ role: newRole }),
            });
            if (response.ok) {
                fetchDashboardData();
            } else if (response.status === 401 || response.status === 403) {
                 console.error('Unauthorized to update user role. Redirecting to login.');
                 navigate('/login');
            } else {
                 console.error('Error updating user role:', response.statusText);
            }
        } catch (error) {
            console.error('Error updating user role:', error);
        }
    };

    const handleDeleteUser = async (userId) => {
        try {
            const response = await fetch(`/api/admin/users/${userId}`, {
                method: 'DELETE',
            });
            if (response.ok) {
                fetchDashboardData();
            } else if (response.status === 401 || response.status === 403) {
                 console.error('Unauthorized to delete user. Redirecting to login.');
                 navigate('/login');
            } else {
                 console.error('Error deleting user:', response.statusText);
            }
        } catch (error) {
            console.error('Error deleting user:', error);
        }
    };

     const handleLogout = async () => {
         try {
             const response = await fetch('/api/logout', {
                 method: 'POST',
                 headers: {
                     'Content-Type': 'application/json',
                 },
             });

             if (response.ok) {
                 localStorage.removeItem('authToken');
                 console.log('Logged out successfully. Redirecting...');
                 navigate('/login');
             } else {
                 console.error('Logout failed:', response.statusText);
             }
         } catch (error) {
             console.error('Error during logout:', error);
         }
     };

    const handlePermissionChange = (permissionId) => {
        setAgentPermissions(prevPermissions => ({
            ...prevPermissions,
            [permissionId]: !prevPermissions[permissionId]
        }));
    };

    const handleSavePermissions = async () => {
        if (!selectedAgent) return;
        
        setIsLoadingPermissions(true);
        setPermissionError(null);
        setPermissionSuccess(false);

        const grantedPermissionIds = Object.keys(agentPermissions)
            .filter(permissionId => agentPermissions[permissionId])
            .map(permissionId => parseInt(permissionId, 10));

        try {
            const response = await fetch(`/api/admin/users/${selectedAgent.id}/permissions`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ permissions: grantedPermissionIds }),
            });

            if (response.ok) {
                setPermissionSuccess(true);
                setTimeout(() => setPermissionSuccess(false), 3000);
            } else if (response.status === 401 || response.status === 403) {
                console.error('Unauthorized to update permissions. Redirecting to login.');
                navigate('/login');
            } else {
                throw new Error('Failed to update permissions');
            }
        } catch (error) {
            console.error('Error saving permissions:', error);
            setPermissionError('Failed to save permissions. Please try again.');
        } finally {
            setIsLoadingPermissions(false);
        }
    };

    const handleCancelPermissions = () => {
        if (selectedAgent) {
             fetchAgentPermissions(selectedAgent.id);
         } else {
             setAgentPermissions({});
         }
        console.log('Permission changes cancelled.');
    };

    const agents = users.filter(user => user.role === 'agent');

    return (
        <div className="min-h-screen bg-gray-50">
            <header className="bg-white shadow-sm">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                    <div className="flex justify-between items-center">
                        <h1 className="text-2xl font-bold text-gray-900">Admin Dashboard</h1>
                        <div className="flex items-center space-x-4">
                            <button className="relative p-2 rounded-full hover:bg-gray-100 transition-colors">
                                <BellIcon className="h-6 w-6 text-gray-500" />
                                {stats.totalNotifications > 0 && (
                                    <span className="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-500 rounded-full">
                                        {stats.totalNotifications}
                                    </span>
                                )}
                            </button>
                            <div className="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                                <UserCircleIcon className="h-8 w-8 text-gray-500" />
                            </div>
                            <button
                                onClick={handleLogout}
                                className="p-2 rounded-full hover:bg-gray-100 transition-colors flex items-center space-x-2 text-gray-600 hover:text-gray-900"
                            >
                                <ArrowRightOnReactorIcon className="h-6 w-6" />
                            </button>
                        </div>
                    </div>
                </div>
            </header>

            <main className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div className={styles['stats-cards-container']}>
                    <div className={`${styles['stat-card']} ${styles.blue}`}>
                        <div className="flex items-center justify-between">
                            <div>
                                <p className={styles['stat-title']}>Total Users</p>
                                <p className={styles['stat-value']}>{stats.totalUsers}</p>
                                <p className={styles['stat-sub-text']}>Agents: {stats.totalAgents} | Clients: {stats.totalClients}</p>
                            </div>
                            <UsersIcon className={`${styles['stat-icon']} text-white`} />
                        </div>
                    </div>
                    <div className={`${styles['stat-card']} ${styles.green}`}>
                        <div className="flex items-center justify-between">
                            <div>
                                <p className={styles['stat-title']}>Maintenance</p>
                                <p className={styles['stat-value']}>{stats.totalMaintenance}</p>
                                <p className={styles['stat-sub-text']}>Pending: {stats.pendingMaintenance}</p>
                            </div>
                            <WrenchIcon className={`${styles['stat-icon']} text-white`} />
                        </div>
                    </div>
                    <div className={`${styles['stat-card']} ${styles.yellow}`}>
                        <div className="flex items-center justify-between">
                            <div>
                                <p className={styles['stat-title']}>Alerts</p>
                                <p className={styles['stat-value']}>{stats.totalAlerts}</p>
                                <p className={styles['stat-sub-text']}>Critical: {stats.criticalAlerts}</p>
                            </div>
                            <ExclamationTriangleIcon className={`${styles['stat-icon']} text-white`} />
                        </div>
                    </div>
                    <div className={`${styles['stat-card']} ${styles.purple}`}>
                        <div className="flex items-center justify-between">
                            <div>
                                <p className={styles['stat-title']}>Notifications</p>
                                <p className={styles['stat-value']}>{stats.totalNotifications}</p>
                                <p className={styles['stat-sub-text']}>Unread</p>
                            </div>
                            <BellIcon className={`${styles['stat-icon']} text-white`} />
                        </div>
                    </div>
                </div>

                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-8">
                    {navigationItems.map((item) => (
                        <div
                            key={item.name}
                            className="bg-white p-4 rounded-lg shadow-sm hover:shadow-md transition-all duration-200 cursor-pointer transform hover:-translate-y-1"
                        >
                            <div className="flex items-center space-x-3">
                                <item.icon className="h-6 w-6 text-blue-500" />
                                <span className="font-medium text-gray-900">{item.name}</span>
                                {item.count !== undefined && (
                                    <span className="ml-auto bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                        {item.count}
                                    </span>
                                )}
                            </div>
                        </div>
                    ))}
                </div>

                <div className={styles['permissions-management-section']}>
                    <h3 className={styles['section-title']}>Agent Permissions Management</h3>

                    <div className={styles['agent-select-container']}>
                        <label htmlFor="agent-select" className={styles['agent-select-label']}>Select Agent to Manage Permissions</label>
                        <select
                            id="agent-select"
                            className={styles['agent-select']}
                            onChange={(e) => setSelectedAgent(agents.find(agent => agent.id === parseInt(e.target.value)))}
                            value={selectedAgent ? selectedAgent.id : ''}
                            disabled={isLoadingPermissions}
                        >
                            <option value="">-- Choose an Agent --</option>
                            {agents.map(agent => (
                                <option key={agent.id} value={agent.id}>{agent.name} ({agent.email})</option>
                            ))}
                        </select>
                    </div>

                    {selectedAgent && (
                        <div className={styles['permissions-list-container']}>
                            <h4 className={styles['permissions-list-title']}>Permissions for {selectedAgent.name}</h4>
                            
                            {permissionError && (
                                <div className={styles['error-message']} role="alert">
                                    <p className={styles['error-message-title']}>Error:</p>
                                    <p>{permissionError}</p>
                                </div>
                            )}
                            
                            {permissionSuccess && (
                                <div className={styles['success-message']} role="alert">
                                    Permissions updated successfully!
                                </div>
                            )}

                            {isLoadingPermissions ? (
                                <div className={styles['loading-container']}>
                                    <div className={styles['loading-spinner']}></div>
                                    <p className={styles['loading-text']}>Loading Permissions...</p>
                                </div>
                            ) : (
                                <div className={styles['space-y-6']}>
                                    {Array.from(new Set(permissions.map(p => p.category))).map(category => (
                                        <div key={category} className={styles['permissions-category-container']}>
                                            <div className={styles['category-header']}>
                                                <h5 className={styles['category-title']}>{category}</h5>
                                            </div>
                                            <div className={styles['permission-items-list']}>
                                                {permissions
                                                    .filter(permission => permission.category === category)
                                                    .map(permission => (
                                                        <div key={permission.id} className={styles['permission-item']}>
                                                            <div className={styles['permission-info']}>
                                                                <p className={styles['permission-name']}>{permission.name.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase())}</p>
                                                                <p className={styles['permission-description']}>{permission.description}</p>
                                                            </div>
                                                            <label className={styles['permission-toggle-label']}>
                                                                <input
                                                                    type="checkbox"
                                                                    value={permission.id}
                                                                    className={styles['permission-toggle-checkbox']}
                                                                    checked={!!agentPermissions[permission.id]}
                                                                    onChange={() => handlePermissionChange(permission.id)}
                                                                    disabled={isLoadingPermissions}
                                                                />
                                                                <div className={styles['permission-toggle-slider']}></div>
                                                            </label>
                                                        </div>
                                                    ))}
                                            </div>
                                        </div>
                                    ))}
                                </div>
                            )}

                            <div className={styles['buttons-container']}>
                                <button
                                    onClick={handleCancelPermissions}
                                    className={styles['cancel-button']}
                                    disabled={isLoadingPermissions}
                                >
                                    Cancel
                                </button>
                                <button
                                    onClick={handleSavePermissions}
                                    className={styles['save-button']}
                                    disabled={isLoadingPermissions}
                                >
                                    {isLoadingPermissions ? 'Saving...' : 'Save Permissions'}
                                </button>
                            </div>
                        </div>
                    )}
                </div>

                <div className="bg-white rounded-lg shadow-sm p-6 mb-8">
                    <div className="flex justify-between items-center mb-4">
                        <h3 className="text-lg font-semibold text-gray-900">User Management</h3>
                        <button className="bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center space-x-2 hover:bg-blue-700 transition-colors">
                            <UserPlusIcon className="h-5 w-5" />
                            <span>Add New User</span>
                        </button>
                    </div>
                    <div className="overflow-x-auto">
                        <table className="min-w-full divide-y divide-gray-200">
                            <thead className="bg-gray-50">
                                <tr>
                                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody className="bg-white divide-y divide-gray-200">
                                {users.map((user) => (
                                    <tr key={user.id}>
                                        <td className="px-6 py-4 whitespace-nowrap">
                                            <div className="flex items-center">
                                                <div className="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                                                    <UserCircleIcon className="h-8 w-8 text-gray-500" />
                                                </div>
                                                <div className="ml-4">
                                                    <div className="text-sm font-medium text-gray-900">{user.name}</div>
                                                    <div className="text-sm text-gray-500">{user.email}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td className="px-6 py-4 whitespace-nowrap">
                                            <select
                                                value={user.role}
                                                onChange={(e) => handleUpdateUserRole(user.id, e.target.value)}
                                                className="text-sm text-gray-900 border-gray-300 rounded-md"
                                            >
                                                <option value="client">Client</option>
                                                <option value="agent">Agent</option>
                                                <option value="admin">Admin</option>
                                            </select>
                                        </td>
                                        <td className="px-6 py-4 whitespace-nowrap">
                                            <span className={`px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${
                                                user.status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'
                                            }`}>
                                                {user.status}
                                            </span>
                                        </td>
                                        <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <button onClick={() => console.log('Edit user', user.id)} className="text-blue-600 hover:text-blue-900 mr-3">Edit</button>
                                            <button onClick={() => handleDeleteUser(user.id)} className="text-red-600 hover:text-red-900">Delete</button>
                                        </td>
                                    </tr>
                                ))}
                            </tbody>
                        </table>
                    </div>
                </div>

                <div className="bg-white rounded-lg shadow-sm p-6">
                    <div className="flex justify-between items-center mb-4">
                        <h3 className="text-lg font-semibold text-gray-900">Maintenance Requests</h3>
                        <button className="bg-green-600 text-white px-4 py-2 rounded-lg flex items-center space-x-2 hover:bg-green-700 transition-colors">
                            <ClipboardDocumentListIcon className="h-5 w-5" />
                            <span>Assign Maintenance</span>
                        </button>
                    </div>
                    <div className="space-y-4">
                        {maintenance.map((item) => (
                            <div key={item.id} className="border rounded-lg p-4">
                                <div className="flex justify-between items-start">
                                    <div>
                                        <h4 className="text-sm font-medium text-gray-900">{item.title}</h4>
                                        <p className="text-sm text-gray-500">{item.description}</p>
                                        <p className="text-xs text-gray-400 mt-1">From: {item.from}</p>
                                    </div>
                                    <div className="flex items-center space-x-2">
                                        <select
                                            value={item.assignedTeam || ''}
                                            onChange={(e) => handleAssignMaintenance(item.id, e.target.value)}
                                            className="text-sm text-gray-900 border-gray-300 rounded-md"
                                        >
                                            <option value="">Assign Team</option>
                                            <option value="team1">Maintenance Team 1</option>
                                            <option value="team2">Maintenance Team 2</option>
                                            <option value="team3">Maintenance Team 3</option>
                                        </select>
                                        <span className={`px-2 py-1 text-xs font-semibold rounded-full ${
                                            item.status === 'pending' ? 'bg-yellow-100 text-yellow-800' :
                                            item.status === 'in-progress' ? 'bg-blue-100 text-blue-800' :
                                            'bg-green-100 text-green-800'
                                        }`}>
                                            {item.status}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        ))}
                    </div>
                </div>
            </main>
        </div>
    );
};

export default AdminDashboard; 