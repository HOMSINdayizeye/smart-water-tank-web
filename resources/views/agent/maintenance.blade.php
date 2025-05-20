@extends('layouts.app')

@section('content')
<div class="maintenance-container">
    <div class="page-header">
        <h1>Maintenance Scheduling & Tracking</h1>
        <div class="header-actions">
            <button class="btn btn-secondary" id="refreshData">
                <i class="fas fa-sync-alt"></i> Refresh
            </button>
            <button class="btn btn-primary" id="scheduleMaintenance">
                <i class="fas fa-plus"></i> Schedule Maintenance
            </button>
        </div>
    </div>

    <!-- Status Overview -->
    <div class="status-overview">
        <div class="status-card">
            <div class="status-icon pending">
                <i class="fas fa-clock"></i>
            </div>
            <div class="status-info">
                <h3>Pending Tasks</h3>
                <p class="status-value">{{ $pendingTasksCount ?? 0 }}</p>
            </div>
        </div>
        <div class="status-card">
            <div class="status-icon in-progress">
                <i class="fas fa-tools"></i>
            </div>
            <div class="status-info">
                <h3>In Progress</h3>
                <p class="status-value">{{ $inProgressTasksCount ?? 0 }}</p>
            </div>
        </div>
        <div class="status-card">
            <div class="status-icon completed">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="status-info">
                <h3>Completed</h3>
                <p class="status-value">{{ $completedTasksCount ?? 0 }}</p>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="maintenance-grid">
        <!-- Calendar Section -->
        <div class="calendar-section">
            <div class="section-header">
                <h2>Maintenance Calendar</h2>
                <div class="calendar-controls">
                    <button class="btn-icon" id="prevMonth">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <span id="currentMonth">March 2024</span>
                    <button class="btn-icon" id="nextMonth">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </div>
            <div id="maintenanceCalendar"></div>
        </div>

        <!-- Task List Section -->
        <div class="task-list-section">
            <div class="section-header">
                <h2>Maintenance Tasks</h2>
                <div class="list-filters">
                    <div class="search-box">
                        <i class="fas fa-search"></i>
                        <input type="text" id="taskSearch" placeholder="Search tasks...">
                    </div>
                    <select id="statusFilter" class="filter-select">
                        <option value="all">All Status</option>
                        <option value="pending">Pending</option>
                        <option value="in_progress">In Progress</option>
                        <option value="completed">Completed</option>
                    </select>
                </div>
            </div>

            <div class="task-list" id="taskList">
                @foreach($maintenanceTasks as $task)
                <div class="task-item" data-task-id="{{ $task->id }}">
                    <div class="task-info">
                        <div class="task-icon">
                            <i class="fas fa-tools"></i>
                        </div>
                        <div class="task-details">
                            <h4>{{ $task->title }}</h4>
                            <p>{{ $task->tank->name }} - {{ $task->tank->location }}</p>
                        </div>
                    </div>
                    <div class="task-status">
                        <span class="status-badge {{ $task->status }}">
                            {{ ucfirst($task->status) }}
                        </span>
                        <span class="due-date">{{ $task->due_date->format('M d, Y') }}</span>
                    </div>
                    <div class="task-actions">
                        <button class="btn-icon" title="View Details" onclick="viewTaskDetails({{ $task->id }})">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="btn-icon" title="Edit Task" onclick="editTask({{ $task->id }})">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn-icon" title="Delete Task" onclick="deleteTask({{ $task->id }})">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Task Details Modal -->
    <div class="modal" id="taskModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="modalTitle">Task Details</h3>
                <button class="btn-icon" onclick="closeModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <form id="taskForm">
                    <input type="hidden" id="taskId">
                    <div class="form-group">
                        <label for="taskTitle">Title</label>
                        <input type="text" id="taskTitle" required>
                    </div>
                    <div class="form-group">
                        <label for="taskTank">Tank</label>
                        <select id="taskTank" required>
                            @foreach($tanks as $tank)
                            <option value="{{ $tank->id }}">{{ $tank->name }} - {{ $tank->location }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="taskDescription">Description</label>
                        <textarea id="taskDescription" rows="3" required></textarea>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="taskDueDate">Due Date</label>
                            <input type="date" id="taskDueDate" required>
                        </div>
                        <div class="form-group">
                            <label for="taskPriority">Priority</label>
                            <select id="taskPriority" required>
                                <option value="low">Low</option>
                                <option value="medium">Medium</option>
                                <option value="high">High</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="taskStatus">Status</label>
                        <select id="taskStatus" required>
                            <option value="pending">Pending</option>
                            <option value="in_progress">In Progress</option>
                            <option value="completed">Completed</option>
                        </select>
                    </div>
                    <div class="form-actions">
                        <button type="button" class="btn btn-secondary" onclick="closeModal()">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Task</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.maintenance-container {
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

.status-icon.pending { background: #fef3c7; color: #92400e; }
.status-icon.in-progress { background: #dbeafe; color: #1e40af; }
.status-icon.completed { background: #dcfce7; color: #166534; }

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

/* Maintenance Grid */
.maintenance-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

/* Calendar Section */
.calendar-section {
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

.calendar-controls {
    display: flex;
    align-items: center;
    gap: 10px;
}

#currentMonth {
    font-size: 16px;
    font-weight: 500;
    color: #1e293b;
}

/* Task List */
.task-list-section {
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
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

.task-list {
    padding: 20px;
}

.task-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 15px;
    border-bottom: 1px solid #e2e8f0;
    transition: background-color 0.2s ease;
}

.task-item:hover {
    background-color: #f8fafc;
}

.task-info {
    display: flex;
    align-items: center;
    gap: 10px;
}

.task-icon {
    width: 40px;
    height: 40px;
    background: #e2e8f0;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #64748b;
}

.task-details h4 {
    margin: 0;
    font-size: 14px;
}

.task-details p {
    margin: 5px 0 0;
    font-size: 12px;
    color: #64748b;
}

.task-status {
    display: flex;
    align-items: center;
    gap: 15px;
}

.due-date {
    font-size: 12px;
    color: #64748b;
}

.task-actions {
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

/* Status Badges */
.status-badge {
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 500;
}

.status-badge.pending { background: #fef3c7; color: #92400e; }
.status-badge.in_progress { background: #dbeafe; color: #1e40af; }
.status-badge.completed { background: #dcfce7; color: #166534; }

/* Modal */
.modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    z-index: 1000;
}

.modal-content {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: white;
    border-radius: 8px;
    width: 90%;
    max-width: 600px;
    max-height: 90vh;
    overflow-y: auto;
}

.modal-header {
    padding: 20px;
    border-bottom: 1px solid #e2e8f0;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-header h3 {
    margin: 0;
    font-size: 18px;
}

.modal-body {
    padding: 20px;
}

.form-group {
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
    font-size: 14px;
    color: #64748b;
}

.form-group input,
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 8px;
    border: 1px solid #e2e8f0;
    border-radius: 4px;
    font-size: 14px;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px;
}

.form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    margin-top: 20px;
}

@media (max-width: 1024px) {
    .maintenance-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .status-overview {
        grid-template-columns: 1fr;
    }
    
    .task-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }
    
    .task-status {
        width: 100%;
        justify-content: space-between;
    }
    
    .task-actions {
        width: 100%;
        justify-content: flex-end;
    }
    
    .form-row {
        grid-template-columns: 1fr;
    }
}
</style>

<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
<script>
let calendar = null;

// Initialize Calendar
document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('maintenanceCalendar');
    calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: '',
            center: '',
            right: ''
        },
        events: '/api/maintenance/calendar',
        eventClick: function(info) {
            viewTaskDetails(info.event.id);
        },
        eventDidMount: function(info) {
            info.el.title = info.event.title;
        }
    });
    calendar.render();

    // Calendar Navigation
    document.getElementById('prevMonth').addEventListener('click', () => {
        calendar.prev();
        updateMonthDisplay();
    });

    document.getElementById('nextMonth').addEventListener('click', () => {
        calendar.next();
        updateMonthDisplay();
    });

    updateMonthDisplay();
});

function updateMonthDisplay() {
    const date = calendar.getDate();
    const monthNames = ['January', 'February', 'March', 'April', 'May', 'June',
        'July', 'August', 'September', 'October', 'November', 'December'];
    document.getElementById('currentMonth').textContent = 
        `${monthNames[date.getMonth()]} ${date.getFullYear()}`;
}

// Task Management Functions
function viewTaskDetails(taskId) {
    fetch(`/api/maintenance/${taskId}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('modalTitle').textContent = 'Task Details';
            document.getElementById('taskId').value = data.id;
            document.getElementById('taskTitle').value = data.title;
            document.getElementById('taskTank').value = data.tank_id;
            document.getElementById('taskDescription').value = data.description;
            document.getElementById('taskDueDate').value = data.due_date;
            document.getElementById('taskPriority').value = data.priority;
            document.getElementById('taskStatus').value = data.status;
            
            document.getElementById('taskForm').onsubmit = function(e) {
                e.preventDefault();
                updateTask(taskId);
            };
            
            document.getElementById('taskModal').style.display = 'block';
        })
        .catch(error => console.error('Error:', error));
}

function editTask(taskId) {
    viewTaskDetails(taskId);
}

function deleteTask(taskId) {
    if (confirm('Are you sure you want to delete this task?')) {
        fetch(`/api/maintenance/${taskId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                refreshTaskList();
                calendar.refetchEvents();
            }
        })
        .catch(error => console.error('Error:', error));
    }
}

function updateTask(taskId) {
    const formData = {
        title: document.getElementById('taskTitle').value,
        tank_id: document.getElementById('taskTank').value,
        description: document.getElementById('taskDescription').value,
        due_date: document.getElementById('taskDueDate').value,
        priority: document.getElementById('taskPriority').value,
        status: document.getElementById('taskStatus').value
    };

    fetch(`/api/maintenance/${taskId}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify(formData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeModal();
            refreshTaskList();
            calendar.refetchEvents();
        }
    })
    .catch(error => console.error('Error:', error));
}

function closeModal() {
    document.getElementById('taskModal').style.display = 'none';
    document.getElementById('taskForm').reset();
}

// Search and Filter Functions
document.getElementById('taskSearch').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    filterTasks(searchTerm);
});

document.getElementById('statusFilter').addEventListener('change', function(e) {
    const status = e.target.value;
    filterTasks();
});

function filterTasks(searchTerm = '') {
    const status = document.getElementById('statusFilter').value;
    const taskItems = document.querySelectorAll('.task-item');

    taskItems.forEach(item => {
        const title = item.querySelector('h4').textContent.toLowerCase();
        const itemStatus = item.querySelector('.status-badge').classList[1];
        const matchesSearch = title.includes(searchTerm);
        const matchesStatus = status === 'all' || itemStatus === status;

        item.style.display = matchesSearch && matchesStatus ? 'flex' : 'none';
    });
}

// Refresh Functions
function refreshTaskList() {
    fetch('/api/maintenance/tasks')
        .then(response => response.json())
        .then(data => {
            updateTaskList(data.tasks);
        })
        .catch(error => console.error('Error:', error));
}

function updateTaskList(tasks) {
    const taskList = document.getElementById('taskList');
    taskList.innerHTML = tasks.map(task => `
        <div class="task-item" data-task-id="${task.id}">
            <div class="task-info">
                <div class="task-icon">
                    <i class="fas fa-tools"></i>
                </div>
                <div class="task-details">
                    <h4>${task.title}</h4>
                    <p>${task.tank.name} - ${task.tank.location}</p>
                </div>
            </div>
            <div class="task-status">
                <span class="status-badge ${task.status}">
                    ${task.status.charAt(0).toUpperCase() + task.status.slice(1)}
                </span>
                <span class="due-date">${new Date(task.due_date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}</span>
            </div>
            <div class="task-actions">
                <button class="btn-icon" title="View Details" onclick="viewTaskDetails(${task.id})">
                    <i class="fas fa-eye"></i>
                </button>
                <button class="btn-icon" title="Edit Task" onclick="editTask(${task.id})">
                    <i class="fas fa-edit"></i>
                </button>
                <button class="btn-icon" title="Delete Task" onclick="deleteTask(${task.id})">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    `).join('');
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    // Schedule Maintenance button click handler
    document.getElementById('scheduleMaintenance').addEventListener('click', function() {
        document.getElementById('modalTitle').textContent = 'Schedule Maintenance';
        document.getElementById('taskId').value = '';
        document.getElementById('taskForm').reset();
        document.getElementById('taskForm').onsubmit = function(e) {
            e.preventDefault();
            createTask();
        };
        document.getElementById('taskModal').style.display = 'block';
    });

    // Refresh button click handler
    document.getElementById('refreshData').addEventListener('click', function() {
        refreshTaskList();
        calendar.refetchEvents();
    });
});

function createTask() {
    const formData = {
        title: document.getElementById('taskTitle').value,
        tank_id: document.getElementById('taskTank').value,
        description: document.getElementById('taskDescription').value,
        due_date: document.getElementById('taskDueDate').value,
        priority: document.getElementById('taskPriority').value,
        status: document.getElementById('taskStatus').value
    };

    fetch('/api/maintenance', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify(formData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeModal();
            refreshTaskList();
            calendar.refetchEvents();
        }
    })
    .catch(error => console.error('Error:', error));
}
</script>
@endsection 