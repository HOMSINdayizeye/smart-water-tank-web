@extends('layouts.app')

@section('content')

<style>
    .bg-white {
        width: 50%;
        margin: auto;
        padding: 20px;
        background-color: #f5f5f5;
        border-radius: 12px;
        box-shadow: 4px 4px 15px rgba(0, 0, 0, 0.15);
    }

    .p-6 {
        padding: 24px;
    }

    .grid-cols-1, .md\:grid-cols-2 {
        display: grid;
        gap: 20px;
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
        border: 2px solid #bbb;
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

<!-- <div class="btn-container">
    <button type="submit" class="bg-blue-500">Create User</button>
</div> -->


<div class="p-4">
    <div class="flex items-center mb-6">
     <a href="{{ route('users.index') }}" class="mr-2" 
   style="display: block; width: fit-content; 
   padding: 10px; border: 2px solid skyblue;
    background-color: lightblue; text-decoration: none;
     position: fixed; bottom: 10px; left: 50%; transform: translateX(-50%);">
    Dashboard
</a>

       
        <!-- <h1 class="text-2xl font-bold">Create New User</h1> -->
    </div> 

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <form action="{{ route('users.store') }}" method="POST" class="p-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" required>
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" required>
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input type="password" name="password" id="password" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" required>
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" required>
                </div>
                
                <div>
                    <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                    <select name="role" id="role" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" required>
                        <option value="">Select Role</option>
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="agent" {{ old('role') == 'agent' ? 'selected' : '' }}>Agent</option>
                        <option value="client" {{ old('role') == 'client' ? 'selected' : '' }}>Client</option>
                    </select>
                    @error('role')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone') }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                    @error('phone')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                    <input type="text" name="address" id="address" value="{{ old('address') }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                    @error('address')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="is_active" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="is_active" id="is_active" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" required>
                        <option value="1" {{ old('is_active', '1') == '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('is_active')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div id="client-fields" class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6" style="display: none;">
                <div>
                    <label for="location" class="block text-sm font-medium text-gray-700 mb-1">Tank Location</label>
                    <input type="text" name="location" id="location" value="{{ old('location') }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" required>
                    @error('location')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="capacity" class="block text-sm font-medium text-gray-700 mb-1">Tank Capacity (liters)</label>
                    <input type="number" name="capacity" id="capacity" value="{{ old('capacity', 1000) }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" required>
                    @error('capacity')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="current_level" class="block text-sm font-medium text-gray-700 mb-1">Current Water Level (liters)</label>
                    <input type="number" name="current_level" id="current_level" value="{{ old('current_level', 500) }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" required>
                    @error('current_level')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="ph_level" class="block text-sm font-medium text-gray-700 mb-1">pH Level</label>
                    <input type="number" step="0.1" name="ph_level" id="ph_level" value="{{ old('ph_level', 7.0) }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" required>
                    @error('ph_level')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="chloride_level" class="block text-sm font-medium text-gray-700 mb-1">Chloride Level (mg/L)</label>
                    <input type="number" name="chloride_level" id="chloride_level" value="{{ old('chloride_level', 200) }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" required>
                    @error('chloride_level')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="fluoride_level" class="block text-sm font-medium text-gray-700 mb-1">Fluoride Level (mg/L)</label>
                    <input type="number" step="0.1" name="fluoride_level" id="fluoride_level" value="{{ old('fluoride_level', 1.0) }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" required>
                    @error('fluoride_level')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="nitrate_level" class="block text-sm font-medium text-gray-700 mb-1">Nitrate Level (mg/L)</label>
                    <input type="number" name="nitrate_level" id="nitrate_level" value="{{ old('nitrate_level', 40) }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" required>
                    @error('nitrate_level')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div class="flex justify-end">
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md">Create User</button>
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
                // Make tank fields required when client is selected
                clientFields.querySelectorAll('input').forEach(input => {
                    input.required = true;
                });
            } else {
                clientFields.style.display = 'none';
                // Remove required attribute when client is not selected
                clientFields.querySelectorAll('input').forEach(input => {
                    input.required = false;
                });
            }
        }
        
        roleSelect.addEventListener('change', toggleClientFields);
        toggleClientFields(); // Run on page load
    });
</script>
@endsection
