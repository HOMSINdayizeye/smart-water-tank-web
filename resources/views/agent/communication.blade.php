@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Main Content Area -->
        <div class="col-md-8">
            <!-- Communication Channels -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Communication Channels</h5>
                    <div class="btn-group">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newMessageModal">
                            <i class="fas fa-plus"></i> New Message
                        </button>
                        <button type="button" class="btn btn-outline-primary" id="refreshMessages">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="nav nav-tabs" id="communicationTabs" role="tablist">
                        <button class="nav-link active" id="inbox-tab" data-bs-toggle="tab" data-bs-target="#inbox" type="button">
                            <i class="fas fa-inbox"></i> Inbox
                            <span class="badge bg-primary ms-2" id="inboxCount">0</span>
                        </button>
                        <button class="nav-link" id="sent-tab" data-bs-toggle="tab" data-bs-target="#sent" type="button">
                            <i class="fas fa-paper-plane"></i> Sent
                        </button>
                        <button class="nav-link" id="notifications-tab" data-bs-toggle="tab" data-bs-target="#notifications" type="button">
                            <i class="fas fa-bell"></i> Notifications
                            <span class="badge bg-danger ms-2" id="notificationCount">0</span>
                        </button>
                    </div>
                    <div class="tab-content mt-3" id="communicationTabsContent">
                        <!-- Inbox Tab -->
                        <div class="tab-pane fade show active" id="inbox" role="tabpanel">
                            <div class="list-group" id="inboxMessages">
                                <!-- Messages will be loaded here -->
                            </div>
                        </div>
                        <!-- Sent Tab -->
                        <div class="tab-pane fade" id="sent" role="tabpanel">
                            <div class="list-group" id="sentMessages">
                                <!-- Sent messages will be loaded here -->
                            </div>
                        </div>
                        <!-- Notifications Tab -->
                        <div class="tab-pane fade" id="notifications" role="tabpanel">
                            <div class="list-group" id="notificationsList">
                                <!-- Notifications will be loaded here -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Team Collaboration -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Team Collaboration</h5>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newTaskModal">
                        <i class="fas fa-tasks"></i> New Task
                    </button>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Active Tasks</h6>
                            <div class="list-group" id="activeTasks">
                                <!-- Active tasks will be loaded here -->
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6>Team Members</h6>
                            <div class="list-group" id="teamMembers">
                                <!-- Team members will be loaded here -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-md-4">
            <!-- Quick Actions -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#scheduleMeetingModal">
                            <i class="fas fa-calendar-plus"></i> Schedule Meeting
                        </button>
                        <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#createAnnouncementModal">
                            <i class="fas fa-bullhorn"></i> Create Announcement
                        </button>
                        <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#shareDocumentModal">
                            <i class="fas fa-file-upload"></i> Share Document
                        </button>
                    </div>
                </div>
            </div>

            <!-- Upcoming Events -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Upcoming Events</h5>
                </div>
                <div class="card-body">
                    <div id="upcomingEvents">
                        <!-- Events will be loaded here -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- New Message Modal -->
<div class="modal fade" id="newMessageModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">New Message</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="newMessageForm">
                    <div class="mb-3">
                        <label class="form-label">Recipient</label>
                        <select class="form-select" name="recipient_id" required>
                            <option value="">Select Recipient</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Subject</label>
                        <input type="text" class="form-control" name="subject" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Message</label>
                        <textarea class="form-control" name="message" rows="4" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Priority</label>
                        <select class="form-select" name="priority">
                            <option value="low">Low</option>
                            <option value="medium">Medium</option>
                            <option value="high">High</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="sendMessage">Send Message</button>
            </div>
        </div>
    </div>
</div>

<!-- New Task Modal -->
<div class="modal fade" id="newTaskModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">New Task</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="newTaskForm">
                    <div class="mb-3">
                        <label class="form-label">Title</label>
                        <input type="text" class="form-control" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" name="description" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Assign To</label>
                        <select class="form-select" name="assigned_to" required>
                            <option value="">Select Team Member</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Due Date</label>
                        <input type="datetime-local" class="form-control" name="due_date" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Priority</label>
                        <select class="form-select" name="priority">
                            <option value="low">Low</option>
                            <option value="medium">Medium</option>
                            <option value="high">High</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="createTask">Create Task</button>
            </div>
        </div>
    </div>
</div>

<!-- Schedule Meeting Modal -->
<div class="modal fade" id="scheduleMeetingModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Schedule Meeting</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="meetingForm">
                    <div class="mb-3">
                        <label class="form-label">Title</label>
                        <input type="text" class="form-control" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Date & Time</label>
                        <input type="datetime-local" class="form-control" name="datetime" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Duration (minutes)</label>
                        <input type="number" class="form-control" name="duration" min="15" step="15" value="30" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Participants</label>
                        <select class="form-select" name="participants[]" multiple required>
                            <!-- Participants will be loaded here -->
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" name="description" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="scheduleMeeting">Schedule Meeting</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
    .message-item {
        transition: background-color 0.2s;
    }
    .message-item:hover {
        background-color: #f8f9fa;
    }
    .message-item.unread {
        background-color: #e3f2fd;
    }
    .task-item {
        border-left: 4px solid #dee2e6;
    }
    .task-item.priority-high {
        border-left-color: #dc3545;
    }
    .task-item.priority-medium {
        border-left-color: #ffc107;
    }
    .task-item.priority-low {
        border-left-color: #28a745;
    }
    .team-member {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .team-member .status-indicator {
        width: 10px;
        height: 10px;
        border-radius: 50%;
    }
    .status-indicator.online {
        background-color: #28a745;
    }
    .status-indicator.offline {
        background-color: #dc3545;
    }
    .status-indicator.away {
        background-color: #ffc107;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips and popovers
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Load initial data
    loadInboxMessages();
    loadSentMessages();
    loadNotifications();
    loadActiveTasks();
    loadTeamMembers();
    loadUpcomingEvents();

    // Event Listeners
    document.getElementById('refreshMessages').addEventListener('click', function() {
        loadInboxMessages();
        loadSentMessages();
        loadNotifications();
    });

    document.getElementById('sendMessage').addEventListener('click', function() {
        const form = document.getElementById('newMessageForm');
        const formData = new FormData(form);
        
        fetch('/api/messages', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(Object.fromEntries(formData))
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                bootstrap.Modal.getInstance(document.getElementById('newMessageModal')).hide();
                form.reset();
                loadSentMessages();
                showToast('Message sent successfully', 'success');
            } else {
                showToast('Failed to send message', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('An error occurred', 'error');
        });
    });

    document.getElementById('createTask').addEventListener('click', function() {
        const form = document.getElementById('newTaskForm');
        const formData = new FormData(form);
        
        fetch('/api/tasks', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(Object.fromEntries(formData))
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                bootstrap.Modal.getInstance(document.getElementById('newTaskModal')).hide();
                form.reset();
                loadActiveTasks();
                showToast('Task created successfully', 'success');
            } else {
                showToast('Failed to create task', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('An error occurred', 'error');
        });
    });

    document.getElementById('scheduleMeeting').addEventListener('click', function() {
        const form = document.getElementById('meetingForm');
        const formData = new FormData(form);
        
        fetch('/api/meetings', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(Object.fromEntries(formData))
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                bootstrap.Modal.getInstance(document.getElementById('scheduleMeetingModal')).hide();
                form.reset();
                loadUpcomingEvents();
                showToast('Meeting scheduled successfully', 'success');
            } else {
                showToast('Failed to schedule meeting', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('An error occurred', 'error');
        });
    });

    // Helper Functions
    function loadInboxMessages() {
        fetch('/api/messages/inbox')
            .then(response => response.json())
            .then(data => {
                const inboxContainer = document.getElementById('inboxMessages');
                inboxContainer.innerHTML = '';
                document.getElementById('inboxCount').textContent = data.unread_count;

                data.messages.forEach(message => {
                    inboxContainer.appendChild(createMessageElement(message));
                });
            })
            .catch(error => console.error('Error loading inbox:', error));
    }

    function loadSentMessages() {
        fetch('/api/messages/sent')
            .then(response => response.json())
            .then(data => {
                const sentContainer = document.getElementById('sentMessages');
                sentContainer.innerHTML = '';

                data.messages.forEach(message => {
                    sentContainer.appendChild(createMessageElement(message));
                });
            })
            .catch(error => console.error('Error loading sent messages:', error));
    }

    function loadNotifications() {
        fetch('/api/notifications')
            .then(response => response.json())
            .then(data => {
                const notificationsContainer = document.getElementById('notificationsList');
                notificationsContainer.innerHTML = '';
                document.getElementById('notificationCount').textContent = data.unread_count;

                data.notifications.forEach(notification => {
                    notificationsContainer.appendChild(createNotificationElement(notification));
                });
            })
            .catch(error => console.error('Error loading notifications:', error));
    }

    function loadActiveTasks() {
        fetch('/api/tasks/active')
            .then(response => response.json())
            .then(data => {
                const tasksContainer = document.getElementById('activeTasks');
                tasksContainer.innerHTML = '';

                data.tasks.forEach(task => {
                    tasksContainer.appendChild(createTaskElement(task));
                });
            })
            .catch(error => console.error('Error loading tasks:', error));
    }

    function loadTeamMembers() {
        fetch('/api/team/members')
            .then(response => response.json())
            .then(data => {
                const membersContainer = document.getElementById('teamMembers');
                membersContainer.innerHTML = '';

                data.members.forEach(member => {
                    membersContainer.appendChild(createTeamMemberElement(member));
                });
            })
            .catch(error => console.error('Error loading team members:', error));
    }

    function loadUpcomingEvents() {
        fetch('/api/events/upcoming')
            .then(response => response.json())
            .then(data => {
                const eventsContainer = document.getElementById('upcomingEvents');
                eventsContainer.innerHTML = '';

                data.events.forEach(event => {
                    eventsContainer.appendChild(createEventElement(event));
                });
            })
            .catch(error => console.error('Error loading events:', error));
    }

    function createMessageElement(message) {
        const div = document.createElement('div');
        div.className = `list-group-item message-item ${message.unread ? 'unread' : ''}`;
        div.innerHTML = `
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="mb-1">${message.subject}</h6>
                <small>${message.created_at}</small>
            </div>
            <p class="mb-1">${message.preview}</p>
            <small class="text-muted">From: ${message.sender}</small>
        `;
        return div;
    }

    function createNotificationElement(notification) {
        const div = document.createElement('div');
        div.className = `list-group-item ${notification.unread ? 'unread' : ''}`;
        div.innerHTML = `
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="mb-1">${notification.title}</h6>
                <small>${notification.created_at}</small>
            </div>
            <p class="mb-1">${notification.message}</p>
        `;
        return div;
    }

    function createTaskElement(task) {
        const div = document.createElement('div');
        div.className = `list-group-item task-item priority-${task.priority}`;
        div.innerHTML = `
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="mb-1">${task.title}</h6>
                <span class="badge bg-${getPriorityColor(task.priority)}">${task.priority}</span>
            </div>
            <p class="mb-1">${task.description}</p>
            <small class="text-muted">Due: ${task.due_date}</small>
        `;
        return div;
    }

    function createTeamMemberElement(member) {
        const div = document.createElement('div');
        div.className = 'list-group-item team-member';
        div.innerHTML = `
            <div class="status-indicator ${member.status}"></div>
            <div>
                <h6 class="mb-0">${member.name}</h6>
                <small class="text-muted">${member.role}</small>
            </div>
        `;
        return div;
    }

    function createEventElement(event) {
        const div = document.createElement('div');
        div.className = 'list-group-item';
        div.innerHTML = `
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="mb-1">${event.title}</h6>
                <small>${event.datetime}</small>
            </div>
            <p class="mb-1">${event.description}</p>
            <small class="text-muted">Duration: ${event.duration} minutes</small>
        `;
        return div;
    }

    function getPriorityColor(priority) {
        return {
            'high': 'danger',
            'medium': 'warning',
            'low': 'success'
        }[priority] || 'secondary';
    }

    function showToast(message, type = 'info') {
        const toast = document.createElement('div');
        toast.className = `toast align-items-center text-white bg-${type} border-0`;
        toast.setAttribute('role', 'alert');
        toast.setAttribute('aria-live', 'assertive');
        toast.setAttribute('aria-atomic', 'true');
        
        toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        `;
        
        document.body.appendChild(toast);
        const bsToast = new bootstrap.Toast(toast);
        bsToast.show();
        
        toast.addEventListener('hidden.bs.toast', function () {
            document.body.removeChild(toast);
        });
    }
});
</script>
@endpush 