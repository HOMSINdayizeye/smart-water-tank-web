@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Customer Management</h5>
                    <div class="btn-group">
                        <a href="{{ route('agent.customers.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Add New Customer
                        </a>
                        <button type="button" class="btn btn-outline-primary" id="refreshCustomers">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Statistics Cards -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <h6 class="card-title">Total Customers</h6>
                                    <h2 class="mb-0" id="totalCustomers">0</h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <h6 class="card-title">Active Customers</h6>
                                    <h2 class="mb-0" id="activeCustomers">0</h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body">
                                    <h6 class="card-title">Pending Customers</h6>
                                    <h2 class="mb-0" id="pendingCustomers">0</h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-danger text-white">
                                <div class="card-body">
                                    <h6 class="card-title">Inactive Customers</h6>
                                    <h2 class="mb-0" id="inactiveCustomers">0</h2>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Search and Filter -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="input-group">
                                <input type="text" class="form-control" id="searchCustomer" placeholder="Search customers...">
                                <button class="btn btn-outline-secondary" type="button" id="searchButton">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex justify-content-end">
                                <select class="form-select w-auto" id="statusFilter">
                                    <option value="">All Status</option>
                                    <option value="active">Active</option>
                                    <option value="pending">Pending</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Customers Table -->
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Status</th>
                                    <th>Tanks</th>
                                    <th>Last Maintenance</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="customersTableBody">
                                <!-- Customers will be loaded here -->
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-4">
                        <nav aria-label="Customer pagination">
                            <ul class="pagination" id="pagination">
                                <!-- Pagination will be loaded here -->
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteCustomerModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this customer? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .status-badge {
        padding: 0.5em 1em;
        border-radius: 20px;
        font-size: 0.875rem;
        font-weight: 500;
    }
    .status-active {
        background-color: #d1fae5;
        color: #065f46;
    }
    .status-pending {
        background-color: #fef3c7;
        color: #92400e;
    }
    .status-inactive {
        background-color: #fee2e2;
        color: #991b1b;
    }
    .action-buttons .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentPage = 1;
    let searchQuery = '';
    let statusFilter = '';

    // Load initial data
    loadCustomers();
    loadStatistics();

    // Event Listeners
    document.getElementById('refreshCustomers').addEventListener('click', function() {
        loadCustomers();
        loadStatistics();
    });

    document.getElementById('searchButton').addEventListener('click', function() {
        searchQuery = document.getElementById('searchCustomer').value;
        currentPage = 1;
        loadCustomers();
    });

    document.getElementById('statusFilter').addEventListener('change', function() {
        statusFilter = this.value;
        currentPage = 1;
        loadCustomers();
    });

    document.getElementById('confirmDelete').addEventListener('click', function() {
        const customerId = this.dataset.customerId;
        deleteCustomer(customerId);
    });

    // Helper Functions
    function loadCustomers() {
        const url = `/agent/customers?page=${currentPage}&search=${searchQuery}&status=${statusFilter}`;
        
        fetch(url)
            .then(response => response.json())
            .then(data => {
                updateCustomersTable(data.customers);
                updatePagination(data);
            })
            .catch(error => {
                console.error('Error loading customers:', error);
                showToast('Error loading customers', 'error');
            });
    }

    function loadStatistics() {
        fetch('/agent/customers/statistics')
            .then(response => response.json())
            .then(data => {
                document.getElementById('totalCustomers').textContent = data.total;
                document.getElementById('activeCustomers').textContent = data.active;
                document.getElementById('pendingCustomers').textContent = data.pending;
                document.getElementById('inactiveCustomers').textContent = data.inactive;
            })
            .catch(error => {
                console.error('Error loading statistics:', error);
            });
    }

    function updateCustomersTable(customers) {
        const tbody = document.getElementById('customersTableBody');
        tbody.innerHTML = '';

        customers.forEach(customer => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${customer.name}</td>
                <td>${customer.email}</td>
                <td>${customer.phone}</td>
                <td><span class="status-badge status-${customer.status}">${customer.status}</span></td>
                <td>${customer.tanks_count}</td>
                <td>${customer.last_maintenance || 'N/A'}</td>
                <td class="action-buttons">
                    <a href="/agent/customers/${customer.id}" class="btn btn-info btn-sm">
                        <i class="fas fa-eye"></i>
                    </a>
                    <a href="/agent/customers/${customer.id}/edit" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit"></i>
                    </a>
                    <button class="btn btn-danger btn-sm" onclick="showDeleteModal(${customer.id})">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            `;
            tbody.appendChild(tr);
        });
    }

    function updatePagination(data) {
        const pagination = document.getElementById('pagination');
        pagination.innerHTML = '';

        // Previous button
        const prevLi = document.createElement('li');
        prevLi.className = `page-item ${data.current_page === 1 ? 'disabled' : ''}`;
        prevLi.innerHTML = `
            <a class="page-link" href="#" onclick="changePage(${data.current_page - 1})">
                <i class="fas fa-chevron-left"></i>
            </a>
        `;
        pagination.appendChild(prevLi);

        // Page numbers
        for (let i = 1; i <= data.last_page; i++) {
            const li = document.createElement('li');
            li.className = `page-item ${i === data.current_page ? 'active' : ''}`;
            li.innerHTML = `
                <a class="page-link" href="#" onclick="changePage(${i})">${i}</a>
            `;
            pagination.appendChild(li);
        }

        // Next button
        const nextLi = document.createElement('li');
        nextLi.className = `page-item ${data.current_page === data.last_page ? 'disabled' : ''}`;
        nextLi.innerHTML = `
            <a class="page-link" href="#" onclick="changePage(${data.current_page + 1})">
                <i class="fas fa-chevron-right"></i>
            </a>
        `;
        pagination.appendChild(nextLi);
    }

    function changePage(page) {
        currentPage = page;
        loadCustomers();
    }

    function showDeleteModal(customerId) {
        const modal = new bootstrap.Modal(document.getElementById('deleteCustomerModal'));
        document.getElementById('confirmDelete').dataset.customerId = customerId;
        modal.show();
    }

    function deleteCustomer(customerId) {
        fetch(`/agent/customers/${customerId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                bootstrap.Modal.getInstance(document.getElementById('deleteCustomerModal')).hide();
                loadCustomers();
                loadStatistics();
                showToast('Customer deleted successfully', 'success');
            } else {
                showToast('Failed to delete customer', 'error');
            }
        })
        .catch(error => {
            console.error('Error deleting customer:', error);
            showToast('An error occurred', 'error');
        });
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