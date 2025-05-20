@extends('layouts.dashboard')

@section('title', 'User Management')

@section('content')
<div class="main-content-padding">
    <div class="flex items-center mb-6">
        <h1 class="text-2xl font-bold page-title">
            <span style="vertical-align:middle; margin-right:8px; color:#6366f1;">
                <i class="fas fa-users"></i>
            </span>
            User Management
        </h1>
        <a href="{{ route('users.create') }}" class="add-user-btn">+ Add New User</a>
    </div>
    <div class="user-table-container">
        <table class="user-table enhanced-table">
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
                        <span class="user-avatar">{{ strtoupper(substr($user->name,0,1)) }}</span>
                        <span class="user-name">{{ $user->name }}</span>
                    </td>
                    <td>{{ $user->email }}</td>
                    <td>
                        <span class="role-badge role-{{ strtolower($user->role) }}">{{ $user->role }}</span>
                    </td>
                    <td>{{ $user->created_at->format('M d, Y') }}</td>
                    <td>
                        <span class="status-badge {{ $user->status == 'Active' ? 'active' : 'inactive' }}">
                            {{ $user->status }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('users.show', $user->id) }}" class="action-link view-link" title="View"><i class="fas fa-eye"></i></a>
                        <a href="{{ route('users.edit', $user->id) }}" class="action-link edit-link" title="Edit"><i class="fas fa-edit"></i></a>
                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="action-link delete-link" title="Delete" onclick="return confirm('Delete this user?')"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('styles')
<style>
    .main-content-padding {
        padding: 1.5rem;
    }

    .page-title {
        margin-left: 1rem;
    }

    .add-user-btn {
        margin-left: auto;
        background: linear-gradient(90deg, #6366f1 60%, #818cf8 100%);
        color: #fff;
        padding: 0.55rem 1.2rem;
        border-radius: 0.5rem;
        text-decoration: none;
        font-weight: 600;
        font-size: 1rem;
        box-shadow: 0 2px 8px rgba(99,102,241,0.10);
        transition: background 0.2s, box-shadow 0.2s;
    }
    .add-user-btn:hover {
        background: linear-gradient(90deg, #4f46e5 60%, #6366f1 100%);
        box-shadow: 0 4px 16px rgba(99,102,241,0.18);
    }

    .user-table-container {
        overflow-x: auto;
    }

    .user-table {
        width: 100%;
        border-collapse: collapse;
        background: #fff;
        border-radius: 0.5rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.06);
    }

    .user-table.enhanced-table {
        border-radius: 0.75rem;
        overflow: hidden;
        box-shadow: 0 4px 24px rgba(99,102,241,0.08);
        font-size: 1rem;
    }

    .user-table th,
    .user-table td {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid #e5e7eb;
        text-align: left;
    }

    .user-table th {
        background: #f1f5f9;
        color: #374151;
        font-weight: 700;
        letter-spacing: 0.03em;
        border-bottom: 2px solid #e5e7eb;
    }

    .user-table tr {
        transition: background 0.2s;
    }

    .user-table tr:hover {
        background: #f3f4f6;
    }

    .user-avatar {
        background: linear-gradient(135deg, #6366f1 60%, #818cf8 100%);
        color: #fff;
        border-radius: 50%;
        width: 2.2rem;
        height: 2.2rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 1.1rem;
        margin-right: 0.7rem;
        box-shadow: 0 2px 8px rgba(99,102,241,0.15);
    }

    .user-name {
        font-weight: 600;
        color: #1e293b;
    }

    .role-badge {
        padding: 0.3em 0.9em;
        border-radius: 1em;
        font-size: 0.95em;
        font-weight: 500;
        background: #e0e7ff;
        color: #3730a3;
        text-transform: capitalize;
    }

    .role-admin { background: #fee2e2; color: #b91c1c; }
    .role-agent { background: #fef9c3; color: #92400e; }
    .role-client { background: #d1fae5; color: #065f46; }

    .status-badge {
        padding: 0.3em 1em;
        border-radius: 1em;
        font-size: 0.95em;
        font-weight: 600;
        letter-spacing: 0.02em;
    }

    .status-badge.active { {
        background: #d1fae5;
        color: #065f46;
    }

    .status-badge.inactive {e {
        background: #fee2e2;
        color: #991b1b;
    }

    .action-link {
        margin-right: 0.5rem;rem;
        color: #6366f1;   color: #6366f1;
        font-size: 1.1em;        font-size: 1.1em;
        padding: 0.3em 0.5em;0.3em 0.5em;
        border-radius: 0.3em;s: 0.3em;
        transition: background 0.15s, color 0.15s;5s, color 0.15s;
        text-decoration: none;none;
        display: inline-block;
    }

    .action-link.view-link:hover { background: #e0e7ff; color: #3730a3; }    .action-link.view-link:hover { background: #e0e7ff; color: #3730a3; }
    .action-link.edit-link:hover { background: #fef9c3; color: #92400e; } { background: #fef9c3; color: #92400e; }
    .action-link.delete-link { color: #ef4444; }color: #ef4444; }
    .action-link.delete-link:hover { background: #fee2e2; color: #991b1b; }ink:hover { background: #fee2e2; color: #991b1b; }
</style>
@endpush

@push('head')@push('head')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">et" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endpush