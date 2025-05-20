@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Customer Details</h5>
                    <div class="btn-group">
                        <a href="{{ route('agent.customers.edit', $customer->id) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Edit Customer
                        </a>
                        <button type="button" class="btn btn-danger" onclick="showDeleteModal()">
                            <i class="fas fa-trash"></i> Delete Customer
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Basic Information -->
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h6 class="mb-0">Basic Information</h6>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <tr>
                                            <th style="width: 150px;">Name:</th>
                                            <td>{{ $customer->name }}</td>
                                        </tr>
                                        <tr>
                                            <th>Email:</th>
                                            <td>{{ $customer->email }}</td>
                                        </tr>
                                        <tr>
                                            <th>Phone:</th>
                                            <td>{{ $customer->phone }}</td>
                                        </tr>
                                        <tr>
                                            <th>Status:</th>
                                            <td>
                                                <span class="status-badge status-{{ $customer->status }}">
                                                    {{ ucfirst($customer->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Additional Information -->
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h6 class="mb-0">Additional Information</h6>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <tr>
                                            <th style="width: 150px;">Address:</th>
                                            <td>{{ $customer->address ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Preferred Contact Time:</th>
                                            <td>{{ $customer->preferred_contact_time ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Emergency Contact:</th>
                                            <td>{{ $customer->emergency_contact ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Notes:</th>
                                            <td>{{ $customer->notes ?? 'N/A' }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tanks Information -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0">Tanks</h6>
                                    <a href="{{ route('agent.tanks.create', ['customer_id' => $customer->id]) }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-plus"></i> Add Tank
                                    </a>
                                </div>
                                <div class="card-body">
                                    @if($customer->tanks->count() > 0)
                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>Type</th>
                                                        <th>Capacity</th>
                                                        <th>Current Level</th>
                                                        <th>Last Maintenance</th>
                                                        <th>Status</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($customer->tanks as $tank)
                                                        <tr>
                                                            <td>{{ $tank->id }}</td>
                                                            <td>{{ $tank->type }}</td>
                                                            <td>{{ $tank->capacity }} L</td>
                                                            <td>
                                                                <div class="progress" style="height: 20px;">
                                                                    <div class="progress-bar {{ $tank->current_level < 20 ? 'bg-danger' : ($tank->current_level < 50 ? 'bg-warning' : 'bg-success') }}" 
                                                                         role="progressbar" 
                                                                         style="width: {{ $tank->current_level }}%"
                                                                         aria-valuenow="{{ $tank->current_level }}" 
                                                                         aria-valuemin="0" 
                                                                         aria-valuemax="100">
                                                                        {{ $tank->current_level }}%
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>{{ $tank->last_maintenance ? $tank->last_maintenance->format('M d, Y') : 'N/A' }}</td>
                                                            <td>
                                                                <span class="status-badge status-{{ $tank->status }}">
                                                                    {{ ucfirst($tank->status) }}
                                                                </span>
                                                            </td>
                                                            <td>
                                                                <div class="btn-group">
                                                                    <a href="{{ route('agent.tanks.show', $tank->id) }}" class="btn btn-info btn-sm">
                                                                        <i class="fas fa-eye"></i>
                                                                    </a>
                                                                    <a href="{{ route('agent.tanks.edit', $tank->id) }}" class="btn btn-warning btn-sm">
                                                                        <i class="fas fa-edit"></i>
                                                                    </a>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <p class="text-center mb-0">No tanks found for this customer.</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Maintenance History -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">Maintenance History</h6>
                                </div>
                                <div class="card-body">
                                    @if($customer->maintenanceRecords->count() > 0)
                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>Date</th>
                                                        <th>Tank</th>
                                                        <th>Type</th>
                                                        <th>Description</th>
                                                        <th>Technician</th>
                                                        <th>Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($customer->maintenanceRecords as $record)
                                                        <tr>
                                                            <td>{{ $record->scheduled_date->format('M d, Y') }}</td>
                                                            <td>{{ $record->tank->id }}</td>
                                                            <td>{{ ucfirst($record->type) }}</td>
                                                            <td>{{ $record->description }}</td>
                                                            <td>{{ $record->technician->name ?? 'N/A' }}</td>
                                                            <td>
                                                                <span class="status-badge status-{{ $record->status }}">
                                                                    {{ ucfirst($record->status) }}
                                                                </span>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <p class="text-center mb-0">No maintenance records found.</p>
                                    @endif
                                </div>
                            </div>
                        </div>
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
                <form action="{{ route('agent.customers.destroy', $customer->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
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
    .progress {
        background-color: #e5e7eb;
    }
</style>
@endpush

@push('scripts')
<script>
function showDeleteModal() {
    const modal = new bootstrap.Modal(document.getElementById('deleteCustomerModal'));
    modal.show();
}
</script>
@endpush 