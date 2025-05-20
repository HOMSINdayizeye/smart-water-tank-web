@extends('layouts.app')

@section('content')
<div class="customers-container">
    <div class="page-header">
        <h1>Customer Management</h1>
        <div class="header-actions">
            <button class="btn btn-primary" id="addCustomerBtn">
                <i class="fas fa-plus"></i> Add New Customer
            </button>
            <button class="btn btn-secondary" id="exportCustomers">
                <i class="fas fa-download"></i> Export
            </button>
        </div>
    </div>

    <div class="customers-grid">
        <!-- Search and Filter Section -->
        <div class="search-filter-section">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="customerSearch" placeholder="Search customers...">
            </div>
            <div class="filter-options">
                <select id="statusFilter" class="filter-select">
                    <option value="all">All Status</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                    <option value="pending">Pending</option>
                </select>
                <select id="sortBy" class="filter-select">
                    <option value="name">Sort by Name</option>
                    <option value="date">Sort by Date</option>
                    <option value="tanks">Sort by Tanks</option>
                </select>
            </div>
        </div>

        <!-- Customer List -->
        <div class="customer-list">
            <div class="list-header">
                <div class="header-item">Customer ID</div>
                <div class="header-item">Name</div>
                <div class="header-item">Contact</div>
                <div class="header-item">Tanks</div>
                <div class="header-item">Status</div>
                <div class="header-item">Actions</div>
            </div>
            
            <div class="list-body" id="customerListBody">
                @foreach($customers as $customer)
                <div class="customer-item" data-customer-id="{{ $customer->id }}">
                    <div class="item-cell">{{ $customer->id }}</div>
                    <div class="item-cell">
                        <div class="customer-info">
                            <div class="customer-avatar">
                                <i class="fas fa-user-circle"></i>
                            </div>
                            <div class="customer-details">
                                <h4>{{ $customer->name }}</h4>
                                <p>{{ $customer->email }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="item-cell">
                        <p>{{ $customer->phone }}</p>
                        <p class="text-muted">{{ $customer->address }}</p>
                    </div>
                    <div class="item-cell">
                        <span class="tank-count">{{ $customer->tanks_count }}</span>
                        <span class="text-muted">tanks</span>
                    </div>
                    <div class="item-cell">
                        <span class="status-badge {{ $customer->status }}">
                            {{ ucfirst($customer->status) }}
                        </span>
                    </div>
                    <div class="item-cell actions">
                        <button class="btn-icon" title="View Details" onclick="viewCustomer({{ $customer->id }})">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="btn-icon" title="Edit Customer" onclick="editCustomer({{ $customer->id }})">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn-icon" title="Delete Customer" onclick="deleteCustomer({{ $customer->id }})">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Customer Details Panel -->
        <div class="customer-details-panel" id="customerDetailsPanel">
            <div class="panel-header">
                <h3>Customer Details</h3>
                <button class="btn-icon" onclick="closeCustomerDetails()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div class="panel-content">
                <div class="details-section">
                    <h4>Customer Information</h4>
                    <div class="info-grid">
                        <div class="info-item">
                            <label>Customer ID</label>
                            <span id="customerId">-</span>
                        </div>
                        <div class="info-item">
                            <label>Name</label>
                            <span id="customerName">-</span>
                        </div>
                        <div class="info-item">
                            <label>Email</label>
                            <span id="customerEmail">-</span>
                        </div>
                        <div class="info-item">
                            <label>Phone</label>
                            <span id="customerPhone">-</span>
                        </div>
                        <div class="info-item">
                            <label>Address</label>
                            <span id="customerAddress">-</span>
                        </div>
                        <div class="info-item">
                            <label>Status</label>
                            <span id="customerStatus">-</span>
                        </div>
                    </div>
                </div>

                <div class="details-section">
                    <h4>Tank Information</h4>
                    <div class="tank-list" id="customerTanks">
                        <!-- Tank items will be loaded dynamically -->
                    </div>
                </div>

                <div class="details-section">
                    <h4>Recent Activity</h4>
                    <div class="activity-list" id="customerActivity">
                        <!-- Activity items will be loaded dynamically -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add/Edit Customer Modal -->
<div class="modal" id="customerModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="modalTitle">Add New Customer</h3>
            <button class="btn-icon" onclick="closeModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="customerForm" onsubmit="handleCustomerSubmit(event)">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="phone">Phone</label>
                <input type="tel" id="phone" name="phone" required>
            </div>
            <div class="form-group">
                <label for="address">Address</label>
                <textarea id="address" name="address" required></textarea>
            </div>
            <div class="form-group">
                <label for="status">Status</label>
                <select id="status" name="status" required>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                    <option value="pending">Pending</option>
                </select>
            </div>
            <div class="form-actions">
                <button type="button" class="btn btn-secondary" onclick="closeModal()">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Customer</button>
            </div>
        </form>
    </div>
</div>

<style>
.customers-container {
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

.search-filter-section {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    gap: 20px;
}

.search-box {
    position: relative;
    flex: 1;
}

.search-box input {
    width: 100%;
    padding: 10px 10px 10px 35px;
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

.filter-options {
    display: flex;
    gap: 10px;
}

.filter-select {
    padding: 8px;
    border: 1px solid #e2e8f0;
    border-radius: 4px;
}

.customer-list {
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    overflow: hidden;
}

.list-header {
    display: grid;
    grid-template-columns: 1fr 2fr 1.5fr 1fr 1fr 1fr;
    padding: 15px;
    background: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
}

.customer-item {
    display: grid;
    grid-template-columns: 1fr 2fr 1.5fr 1fr 1fr 1fr;
    padding: 15px;
    border-bottom: 1px solid #e2e8f0;
    align-items: center;
}

.customer-info {
    display: flex;
    align-items: center;
    gap: 10px;
}

.customer-avatar {
    width: 40px;
    height: 40px;
    background: #e2e8f0;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #64748b;
}

.customer-details h4 {
    margin: 0;
    font-size: 14px;
}

.customer-details p {
    margin: 5px 0 0;
    font-size: 12px;
    color: #64748b;
}

.tank-count {
    font-weight: bold;
    color: #0284c7;
}

.status-badge {
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 500;
}

.status-badge.active { background: #dcfce7; color: #166534; }
.status-badge.inactive { background: #fee2e2; color: #991b1b; }
.status-badge.pending { background: #fef3c7; color: #92400e; }

.actions {
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

.customer-details-panel {
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

.customer-details-panel.active {
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

/* Modal Styles */
.modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    z-index: 1100;
}

.modal.active {
    display: flex;
    align-items: center;
    justify-content: center;
}

.modal-content {
    background: white;
    border-radius: 8px;
    width: 100%;
    max-width: 500px;
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

.form-group {
    margin-bottom: 20px;
    padding: 0 20px;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
    color: #1e293b;
}

.form-group input,
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 8px;
    border: 1px solid #e2e8f0;
    border-radius: 4px;
}

.form-group textarea {
    height: 100px;
    resize: vertical;
}

.form-actions {
    padding: 20px;
    border-top: 1px solid #e2e8f0;
    display: flex;
    justify-content: flex-end;
    gap: 10px;
}
</style>

<script>
// Customer Management Functions
function viewCustomer(customerId) {
    document.getElementById('customerDetailsPanel').classList.add('active');
    // Load customer details via AJAX
    fetch(`/api/customers/${customerId}`)
        .then(response => response.json())
        .then(data => {
            updateCustomerDetails(data);
        })
        .catch(error => console.error('Error:', error));
}

function closeCustomerDetails() {
    document.getElementById('customerDetailsPanel').classList.remove('active');
}

function editCustomer(customerId) {
    // Load customer data and open modal
    fetch(`/api/customers/${customerId}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('modalTitle').textContent = 'Edit Customer';
            document.getElementById('customerForm').dataset.customerId = customerId;
            populateForm(data);
            openModal();
        })
        .catch(error => console.error('Error:', error));
}

function deleteCustomer(customerId) {
    if (confirm('Are you sure you want to delete this customer?')) {
        fetch(`/api/customers/${customerId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Remove customer from list
                document.querySelector(`[data-customer-id="${customerId}"]`).remove();
            }
        })
        .catch(error => console.error('Error:', error));
    }
}

// Modal Functions
function openModal() {
    document.getElementById('customerModal').classList.add('active');
}

function closeModal() {
    document.getElementById('customerModal').classList.remove('active');
    document.getElementById('customerForm').reset();
    document.getElementById('customerForm').dataset.customerId = '';
}

function populateForm(data) {
    document.getElementById('name').value = data.name;
    document.getElementById('email').value = data.email;
    document.getElementById('phone').value = data.phone;
    document.getElementById('address').value = data.address;
    document.getElementById('status').value = data.status;
}

function handleCustomerSubmit(event) {
    event.preventDefault();
    const form = event.target;
    const customerId = form.dataset.customerId;
    const method = customerId ? 'PUT' : 'POST';
    const url = customerId ? `/api/customers/${customerId}` : '/api/customers';

    const formData = new FormData(form);
    const data = Object.fromEntries(formData.entries());

    fetch(url, {
        method: method,
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeModal();
            // Refresh customer list
            location.reload();
        }
    })
    .catch(error => console.error('Error:', error));
}

// Search and Filter Functions
document.getElementById('customerSearch').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    filterCustomers(searchTerm);
});

document.getElementById('statusFilter').addEventListener('change', function(e) {
    const status = e.target.value;
    filterCustomers();
});

document.getElementById('sortBy').addEventListener('change', function(e) {
    const sortBy = e.target.value;
    sortCustomers(sortBy);
});

function filterCustomers(searchTerm = '') {
    const status = document.getElementById('statusFilter').value;
    const customerItems = document.querySelectorAll('.customer-item');

    customerItems.forEach(item => {
        const name = item.querySelector('h4').textContent.toLowerCase();
        const itemStatus = item.querySelector('.status-badge').classList[1];
        const matchesSearch = name.includes(searchTerm);
        const matchesStatus = status === 'all' || itemStatus === status;

        item.style.display = matchesSearch && matchesStatus ? 'grid' : 'none';
    });
}

function sortCustomers(sortBy) {
    const customerList = document.getElementById('customerListBody');
    const customerItems = Array.from(customerList.children);

    customerItems.sort((a, b) => {
        let valueA, valueB;

        switch(sortBy) {
            case 'name':
                valueA = a.querySelector('h4').textContent;
                valueB = b.querySelector('h4').textContent;
                break;
            case 'date':
                valueA = new Date(a.dataset.createdAt);
                valueB = new Date(b.dataset.createdAt);
                break;
            case 'tanks':
                valueA = parseInt(a.querySelector('.tank-count').textContent);
                valueB = parseInt(b.querySelector('.tank-count').textContent);
                break;
        }

        return valueA > valueB ? 1 : -1;
    });

    customerItems.forEach(item => customerList.appendChild(item));
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    // Add customer button click handler
    document.getElementById('addCustomerBtn').addEventListener('click', function() {
        document.getElementById('modalTitle').textContent = 'Add New Customer';
        document.getElementById('customerForm').dataset.customerId = '';
        openModal();
    });

    // Export customers click handler
    document.getElementById('exportCustomers').addEventListener('click', function() {
        window.location.href = '/api/customers/export';
    });
});
</script>
@endsection 