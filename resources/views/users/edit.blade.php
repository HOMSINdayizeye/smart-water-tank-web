@extends('layouts.app')

@section('content')


<style>
    .bg-white {
        width: 50%;
        margin: auto;
        padding: 20px;
        background-color: #f5f5f5; /* Light grey background */
        border-radius: 12px;
        box-shadow: 4px 4px 15px rgba(0, 0, 0, 0.15);
    }

    .p-6 {
        padding: 24px;
    }

    .grid-cols-1, .md\:grid-cols-2 {
        display: grid;
        gap: 20px; /* Increased spacing between fields */
        row-gap: 30px;

    }

    @media (min-width: 768px) {
        .md\:grid-cols-2 {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    label {
        font-size: 14px;
        font-weight: bold;
        color: #333;
    }

    input, select, textarea {
        width: 100%;
        padding: 12px;
        border: 2px solid #bbb; /* Darker border */
        border-radius: 8px;
        font-size: 16px;
        transition: all 0.3s ease-in-out;
        background-color: #fff;
        
    }

    input:focus, select:focus, textarea:focus {
        border-color: #3498db;
        box-shadow: 0 0 6px rgba(52, 152, 219, 0.4);
    }

    .text-red-500 {
        color: red;
        font-size: 12px;
    }

    .btn-container {
        display: flex;
        justify-content: center; /* Centers the button */
        margin-top: 20px;
    }

    .bg-blue-500 {
        background-color: #3498db;
        color: white;
        font-weight: bold;
        padding: 12px 20px;
        border-radius: 8px;
        cursor: pointer;
        transition: background 0.3s;
        width: fit-content;
    }

    .bg-blue-500:hover {
        background-color: #2980b9;
    }
</style>


<div class="p-4">
    <div class="flex items-center mb-6">
           <a href="{{ route('users.index') }}" class="mr-2" 
   style="display: block; width: fit-content; 
   padding: 10px; border: 2px solid skyblue;
    background-color: lightblue; text-decoration: none;
     position: fixed; bottom: 10px; left: 50%; transform: translateX(-50%);">
    Dashboard
</a>
        <h1 class="text-2xl font-bold">Edit User: {{ $user->name }}</h1>
    </div>
    
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <form action="{{ route('users.update', $user) }}" method="POST" class="p-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" required>
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" required>
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password (leave blank to keep current)</label>
                    <input type="password" name="password" id="password" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                </div>
                
                <div>
                    <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                    <select name="role" id="role" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" required>
                        <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="agent" {{ old('role', $user->role) == 'agent' ? 'selected' : '' }}>Agent</option>
                        <option value="client" {{ old('role', $user->role) == 'client' ? 'selected' : '' }}>Client</option>
                    </select>
                    @error('role')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                    @error('phone')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                    <input type="text" name="address" id="address" value="{{ old('address', $user->address) }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                    @error('address')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="is_active" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="is_active" id="is_active" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" required>
                        <option value="1" {{ old('is_active', $user->is_active) == '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ old('is_active', $user->is_active) == '0' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('is_active')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div id="client-fields" class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6" style="display: none;">
                <div>
                    <label for="agent_id" class="block text-sm font-medium text-gray-700 mb-1">Assign Agent</label>
                    <select name="agent_id" id="agent_id" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                        <option value="">Select Agent</option>
                        @foreach(\App\Models\User::where('role', 'agent')->where('is_active', true)->get() as $agent)
                            <option value="{{ $agent->id }}" {{ old('agent_id', $user->agent_id) == $agent->id ? 'selected' : '' }}>{{ $agent->name }}</option>
                        @endforeach
                    </select>
                    @error('agent_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div class="flex justify-end">
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md">Update User</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const roleSelect = document.getElementById('role');
        const clientFields = document.getElementById('client-fields');
        
        function toggleClientFields() {
            if (roleSelect.value === 'client') {
                clientFields.style.display = 'grid';
            } else {
                clientFields.style.display = 'none';
            }
        }
        
        roleSelect.addEventListener('change', toggleClientFields);
        toggleClientFields();
    });
</script>
@endsection