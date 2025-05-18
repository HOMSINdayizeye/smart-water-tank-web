@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>My Clients</span>
                    <a href="{{ route('agent.create.client.form') }}" class="btn btn-primary">Add New Client</a>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($clients->isEmpty())
                        <div class="text-center py-4">
                            <p class="text-muted">No clients found. Start by adding a new client.</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Tank Location</th>
                                        <th>Tank Status</th>
                                        <th>Water Level</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($clients as $client)
                                        <tr>
                                            <td>{{ $client->name }}</td>
                                            <td>{{ $client->email }}</td>
                                            <td>{{ $client->tank->location ?? 'N/A' }}</td>
                                            <td>
                                                @if($client->tank)
                                                    <span class="badge bg-{{ $client->tank->status === 'active' ? 'success' : 'warning' }}">
                                                        {{ ucfirst($client->tank->status) }}
                                                    </span>
                                                @else
                                                    <span class="badge bg-secondary">No Tank</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($client->tank)
                                                    {{ $client->tank->current_level }} / {{ $client->tank->capacity }} L
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('tanks.show', $client->tank->id ?? '') }}" 
                                                   class="btn btn-sm btn-info {{ !$client->tank ? 'disabled' : '' }}">
                                                    View Details
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 