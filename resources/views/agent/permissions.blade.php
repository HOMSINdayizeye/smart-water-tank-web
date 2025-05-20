@extends('layouts.app')

@section('content')
<div class="container mx-auto py-6">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h1 class="text-2xl font-semibold mb-6">Permissions Management</h1>

        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                <p>{{ session('error') }}</p>
            </div>
        @endif

        {{-- Assign Permissions Form --}}
        <div class="mb-8">
            <h2 class="text-xl font-semibold mb-4">Assign Permissions to Agents</h2>
            <form action="{{ route('agent.permissions.assign') }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Agent Selection --}}
                    <div>
                        <label for="agent_id" class="block text-sm font-medium text-gray-700 mb-2">Select Agent</label>
                        <select name="agent_id" id="agent_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Select an agent...</option>
                            @foreach($agents as $agent)
                                <option value="{{ $agent->id }}" data-email="{{ $agent->email }}">
                                    {{ $agent->name }} ({{ $agent->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('agent_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Selected Agent Info --}}
                    <div id="selected_agent_info" class="hidden">
                        <div class="bg-gray-50 p-4 rounded-md">
                            <h3 class="text-sm font-medium text-gray-700 mb-2">Selected Agent Information</h3>
                            <div class="space-y-2">
                                <p class="text-sm text-gray-600">
                                    <span class="font-medium">Name:</span> 
                                    <span id="agent_name"></span>
                                </p>
                                <p class="text-sm text-gray-600">
                                    <span class="font-medium">Email:</span> 
                                    <span id="agent_email"></span>
                                </p>
                                <p class="text-sm text-gray-600">
                                    <span class="font-medium">Current Permissions:</span> 
                                    <span id="agent_permissions_count">0</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Permissions Selection --}}
                <div class="mt-6">
                    <div class="flex items-center justify-between mb-4">
                        <label class="block text-sm font-medium text-gray-700">Select Permissions</label>
                        <div class="flex items-center space-x-4">
                            <button type="button" id="select_all" class="text-sm text-blue-600 hover:text-blue-800">
                                Select All
                            </button>
                            <button type="button" id="deselect_all" class="text-sm text-gray-600 hover:text-gray-800">
                                Deselect All
                            </button>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($permissions as $permission)
                            <div class="flex items-start p-3 border rounded-md hover:bg-gray-50 transition-colors">
                                <input type="checkbox" 
                                       name="permissions[]" 
                                       value="{{ $permission->id }}" 
                                       id="permission_{{ $permission->id }}"
                                       class="h-4 w-4 mt-1 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="permission_{{ $permission->id }}" class="ml-2 block">
                                    <span class="text-sm font-medium text-gray-900">{{ $permission->name }}</span>
                                    <span class="text-xs text-gray-500 block mt-1">{{ $permission->description }}</span>
                                </label>
                            </div>
                        @endforeach
                    </div>
                    @error('permissions')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end mt-6">
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        Assign Permissions
                    </button>
                </div>
            </form>
        </div>

        {{-- Available Permissions List --}}
        <div>
            <h2 class="text-xl font-semibold mb-4">Available Permissions</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($permissions as $permission)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $permission->id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $permission->name }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $permission->description }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const agentSelect = document.getElementById('agent_id');
    const permissionCheckboxes = document.querySelectorAll('input[name="permissions[]"]');
    const selectedAgentInfo = document.getElementById('selected_agent_info');
    const agentName = document.getElementById('agent_name');
    const agentEmail = document.getElementById('agent_email');
    const agentPermissionsCount = document.getElementById('agent_permissions_count');
    const selectAllBtn = document.getElementById('select_all');
    const deselectAllBtn = document.getElementById('deselect_all');

    // Function to load agent permissions
    function loadAgentPermissions(agentId) {
        fetch(`/agent/permissions/${agentId}`)
            .then(response => response.json())
            .then(permissions => {
                // Reset all checkboxes
                permissionCheckboxes.forEach(checkbox => {
                    checkbox.checked = false;
                });

                // Check the permissions that the agent has
                permissions.forEach(permission => {
                    const checkbox = document.getElementById(`permission_${permission.id}`);
                    if (checkbox) {
                        checkbox.checked = true;
                    }
                });

                // Update permissions count
                agentPermissionsCount.textContent = permissions.length;
            })
            .catch(error => {
                console.error('Error loading permissions:', error);
            });
    }

    // Event listener for agent selection
    agentSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        if (this.value) {
            // Show agent info
            selectedAgentInfo.classList.remove('hidden');
            agentName.textContent = selectedOption.text.split(' (')[0];
            agentEmail.textContent = selectedOption.dataset.email;
            
            // Load permissions
            loadAgentPermissions(this.value);
        } else {
            // Hide agent info
            selectedAgentInfo.classList.add('hidden');
            // Reset all checkboxes
            permissionCheckboxes.forEach(checkbox => {
                checkbox.checked = false;
            });
            agentPermissionsCount.textContent = '0';
        }
    });

    // Select All button
    selectAllBtn.addEventListener('click', function() {
        permissionCheckboxes.forEach(checkbox => {
            checkbox.checked = true;
        });
    });

    // Deselect All button
    deselectAllBtn.addEventListener('click', function() {
        permissionCheckboxes.forEach(checkbox => {
            checkbox.checked = false;
        });
    });
});
</script>
@endpush
@endsection 