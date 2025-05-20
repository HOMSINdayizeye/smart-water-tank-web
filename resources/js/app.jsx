import React from 'react';
import { createRoot } from 'react-dom/client';
import { BrowserRouter as Router, Routes, Route, Navigate } from 'react-router-dom';
import ClientDashboard from './components/dashboard/ClientDashboard';
import AgentDashboard from './components/dashboard/AgentDashboard';
import AdminDashboard from './components/dashboard/AdminDashboard';
import './bootstrap';

// Create root element
const container = document.getElementById('app');
const root = createRoot(container);

// Get user type from the page (you'll need to pass this from Laravel)
const userType = document.getElementById('app').dataset.userType || 'client';

// Render the app
root.render(
    <React.StrictMode>
        <Router>
            <Routes>
                <Route 
                    path="/admin/*" 
                    element={userType === 'admin' ? <AdminDashboard /> : <Navigate to="/" />} 
                />
                <Route 
                    path="/agent/*" 
                    element={userType === 'agent' ? <AgentDashboard /> : <Navigate to="/" />} 
                />
                <Route 
                    path="/client/*" 
                    element={userType === 'client' ? <ClientDashboard /> : <Navigate to="/" />} 
                />
                <Route 
                    path="/" 
                    element={
                        userType === 'admin' ? <Navigate to="/admin" /> :
                        userType === 'agent' ? <Navigate to="/agent" /> :
                        <Navigate to="/client" />
                    } 
                />
            </Routes>
        </Router>
    </React.StrictMode>
); 