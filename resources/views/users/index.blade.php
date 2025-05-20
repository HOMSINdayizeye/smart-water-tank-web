@extends('admin')

<<<<<<< HEAD
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h1 class="h3 mb-0">User Management</h1>
                    <a href="{{ route('users.create') }}" class="btn btn-primary">Add New User</a>

@section('admin-content')

<style>
    /* Main Container */
    .user-management-container {
        padding: 20px;
    }

    /* Card Styling */
    .user-card {
        background-color: #ffffff;
        border-radius: 12px;
        box-shadow: 4px 4px 15px rgba(0, 0, 0, 0.15);
        padding: 20px;
    }

    .user-card-header {
        background-color: #007bff;
        color: white;
        padding: 15px;
        border-radius: 12px 12px 0 0;
        font-weight: bold;
    }

    /* Table Styling */
    .user-table {
        border-radius: 8px;
        overflow: hidden;
    }

    .user-table-striped tbody tr:nth-of-type(odd) {
        background-color: #f2f2f2;
    }

    /* Badges */
    .badge-success {
        background-color: #28a745;
    }

    .badge-danger {
        background-color: #dc3545;
    }

    .badge-primary {
        background-color: #007bff;
    }

    /* Button Customization */
    .user-btn-primary {
        background-color: #007bff;
        border: none;
        padding: 10px 16px;
        border-radius: 6px;
        font-weight: bold;
    }

    .user-btn-primary:hover {
        background-color: #0056b3;
    }

    .user-btn-secondary {
        background-color: #6c757d;
        border: none;
        padding: 10px 16px;
        border-radius: 6px;
    }

    .user-btn-secondary:hover {
        background-color: #5a6268;
    }

    .user-btn-info {
        background-color: #17a2b8;
        border: none;
        padding: 8px 12px;
    }

    .user-btn-info:hover {
        background-color: #138496;
    }

    .user-btn-warning {
        background-color: #ffc107;
        border: none;
        padding: 8px 12px;
    }

    .user-btn-warning:hover {
        background-color: #e0a800;
    }

    .user-btn-danger {
        background-color: #dc3545;
        border: none;
        padding: 8px 12px;
    }

    .user-btn-danger:hover {
        background-color: #bd2130;
    }

    /* Pagination */
    .user-pagination {
        margin-top: 20px;
    }
</style>

<div class="user-management-container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="user-card">
                <div class="user-card-header d-flex justify-content-between align-items-center">
                    <h1 class="h3 mb-0">User Management</h1>
                    <a href="{{ route('users.create') }}" class="user-btn-primary">Add New User</a>

                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="mb-3">
                        <form action="{{ route('users.index') }}" method="GET" class="row g-3 align-items-center">
                            <div class="col-auto">
                                <select name="role" class="form-select">
                                    <option value="">All Roles</option>
                                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="agent" {{ request('role') == 'agent' ? 'selected' : '' }}>Agent</option>
                                    <option value="client" {{ request('role') == 'client' ? 'selected' : '' }}>Client</option>
                                </select>
                            </div>
                            <div class="col-auto">

                                <button type="submit" class="btn btn-secondary">Filter</button>

                                <button type="submit" class="user-btn-secondary">Filter</button>

                            </div>
                        </form>
                    </div>

                    <div class="table-responsive">

                        <table class="table table-striped">
                        <table class="user-table user-table-striped">

                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Created</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">

                                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">

                                            <div class="rounded-circle badge-primary text-white d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">

                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </div>
                                            <div class="ms-3">
                                                {{ $user->name }}
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        <span class="badge bg-{{ $user->role === 'admin' ? 'danger' : ($user->role === 'agent' ? 'primary' : 'success') }}">
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    </td>
                                    <td>{{ $user->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <span class="badge bg-{{ $user->is_active ? 'success' : 'danger' }}">
                                            {{ $user->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group">

                                            <a href="{{ route('users.show', $user) }}" class="btn btn-sm btn-info">View</a>
                                            <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-warning">Edit</a>
                                            <a href="{{ route('users.permissions.edit', $user) }}" class="btn btn-sm btn-secondary">Permissions</a>
                                            <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this user?')">Delete</button>

                                            <a href="{{ route('users.show', $user) }}" class="user-btn-info">View</a>
                                            <a href="{{ route('users.edit', $user) }}" class="user-btn-warning">Edit</a>
                                            <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="user-btn-danger" onclick="return confirm('Are you sure you want to delete this user?')">Delete</button>

                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>


                    <div class="d-flex justify-content-center mt-4">

                    <div class="user-pagination d-flex justify-content-center">

                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
