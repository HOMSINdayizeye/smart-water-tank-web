@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h2 class="mb-0">Maintenance Scheduling</h2>
                </div>

                <div class="card-body">
                    @if($tank)
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <h3 class="h5 mb-0">Upcoming Maintenance</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="list-group">
                                            <div class="list-group-item">
                                                <div class="d-flex w-100 justify-content-between">
                                                    <h5 class="mb-1">Tank Cleaning</h5>
                                                    <small class="text-muted">Due in 15 days</small>
                                                </div>
                                                <p class="mb-1">Regular cleaning to prevent sediment buildup</p>
                                            </div>
                                            <div class="list-group-item">
                                                <div class="d-flex w-100 justify-content-between">
                                                    <h5 class="mb-1">Filter Replacement</h5>
                                                    <small class="text-muted">Due in 30 days</small>
                                                </div>
                                                <p class="mb-1">Replace water filters for optimal performance</p>
                                            </div>
                                            <div class="list-group-item">
                                                <div class="d-flex w-100 justify-content-between">
                                                    <h5 class="mb-1">System Inspection</h5>
                                                    <small class="text-muted">Due in 45 days</small>
                                                </div>
                                                <p class="mb-1">Comprehensive system check and maintenance</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <h3 class="h5 mb-0">Request Maintenance</h3>
                                    </div>
                                    <div class="card-body">
                                        <form action="{{ route('client.request.agent') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="type" value="maintenance">
                                            <div class="mb-3">
                                                <label for="title" class="form-label">Maintenance Type</label>
                                                <input type="text" class="form-control" id="title" name="title" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="message" class="form-label">Description</label>
                                                <textarea class="form-control" id="message" name="message" rows="3" required></textarea>
                                            </div>
                                            <button type="submit" class="btn btn-primary">Request Maintenance</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <h3 class="h5 mb-0">Maintenance History</h3>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Type</th>
                                                <th>Status</th>
                                                <th>Notes</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>2024-03-01</td>
                                                <td>Filter Replacement</td>
                                                <td><span class="badge bg-success">Completed</span></td>
                                                <td>Regular maintenance</td>
                                            </tr>
                                            <tr>
                                                <td>2024-02-15</td>
                                                <td>Tank Cleaning</td>
                                                <td><span class="badge bg-success">Completed</span></td>
                                                <td>Preventive maintenance</td>
                                            </tr>
                                            <tr>
                                                <td>2024-01-30</td>
                                                <td>System Inspection</td>
                                                <td><span class="badge bg-success">Completed</span></td>
                                                <td>Routine check</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-warning">
                            No tank information available. Please contact your administrator.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 