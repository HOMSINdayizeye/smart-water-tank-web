@extends('layouts.app')

@section('content')

<style>
    /* General Styles */
    body {
        background: #f9fafb;
        font-family: 'Arial', sans-serif;
        color: #333;
    }
    .back-btn {
    display: inline-block;
    background-color:rgba(108, 125, 119, 0.65); /* Grey color */
    color: white;
    padding: 10px 16px;
    border-radius: 6px;
    text-align: center;
    font-weight: bold;
    text-decoration: none;
    transition: background-color 0.3s ease;
}

.back-btn:hover {
    background-color: #5a6268;
}


    .container {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        padding: 20px;
    }

    .form-container {
        max-width: 700px;
        width: 100%;
        background: white;
        border-radius: 15px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        padding: 30px;
    }

    h1 {
        text-align: center;
        margin-bottom: 25px;
        color: #2563eb;
        font-weight: bold;
    }

    .form-label {
        font-weight: bold;
        margin-bottom: 5px;
        color: #1e293b;
    }

    .form-control {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 6px;
        font-size: 14px;
        transition: border-color 0.3s ease, box-shadow 0.3s ease;
    }

    .form-control:focus {
        border-color: #2563eb;
        outline: none;
        box-shadow: 0 0 5px rgba(37, 99, 235, 0.3);
    }

    .btn-primary {
        background-color: #2563eb;
        color: white;
        padding: 10px 16px;
        border: none;
        border-radius: 6px;
        font-size: 16px;
        font-weight: bold;
        cursor: pointer;
        width: 100%;
        transition: background-color 0.3s ease;
    }

    .btn-primary:hover {
        background-color: #1e40af;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .form-container {
            max-width: 100%;
            padding: 20px;
        }
    }
</style>

<div class="container">
    <div class="form-container">
        <h1>Create a New Tank</h1>

        <form action="{{ route('tanks.store') }}" method="POST">
            @csrf

            <!-- User ID -->
            <div class="mb-3">
                <label for="user_id" class="form-label">User ID</label>
                <input type="number" name="user_id" id="user_id" class="form-control" required>
            </div>

            <!-- Location -->
            <div class="mb-3">
                <label for="location" class="form-label">Location</label>
                <input type="text" name="location" id="location" class="form-control" required>
            </div>

            <!-- Capacity -->
            <div class="mb-3">
                <label for="capacity" class="form-label">Capacity (L)</label>
                <input type="number" name="capacity" id="capacity" class="form-control" required>
            </div>

            <!-- Current Level -->
            <div class="mb-3">
                <label for="current_level" class="form-label">Current Level (L)</label>
                <input type="number" name="current_level" id="current_level" class="form-control" required>
            </div>

            <!-- pH Level -->
            <div class="mb-3">
                <label for="ph_level" class="form-label">pH Level</label>
                <input type="number" step="0.01" name="ph_level" id="ph_level" class="form-control" required>
            </div>

            <!-- Chloride Level -->
            <div class="mb-3">
                <label for="chloride_level" class="form-label">Chloride Level</label>
                <input type="number" step="0.01" name="chloride_level" id="chloride_level" class="form-control" required>
            </div>

            <!-- Fluoride Level -->
            <div class="mb-3">
                <label for="fluoride_level" class="form-label">Fluoride Level</label>
                <input type="number" step="0.01" name="fluoride_level" id="fluoride_level" class="form-control" required>
            </div>

            <!-- Nitrate Level -->
            <div class="mb-3">
                <label for="nitrate_level" class="form-label">Nitrate Level</label>
                <input type="number" step="0.01" name="nitrate_level" id="nitrate_level" class="form-control" required>
            </div>

            <!-- Status -->
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select name="status" id="status" class="form-control" required>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>

            <!-- Last Maintenance -->
            <div class="mb-3">
                <label for="last_maintenance" class="form-label">Last Maintenance</label>
                <input type="datetime-local" name="last_maintenance" id="last_maintenance" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary">Create</button>
        </form>
        <a href="{{ route('dashboard') }}" class="back-btn">⬅ Back to Dashboard</a>

    </div>
</div>

@endsection
