<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Login - Smart Water Tank</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Styles -->
    {{-- <link rel="stylesheet" href="{{ asset('css/agent.css') }}"> --}} {{-- Remove or comment out external CSS if not needed --}}

    <style>
        body {
            font-family: 'Figtree', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8fafc;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .login-page-container {
            width: 100%;
            max-width: 450px; /* Adjust max-width for the card */
            margin: 0 auto;
            background-color: #f8fafc;
            border-radius: 0.5rem;
            overflow: hidden; /* Ensure rounded corners on top */
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .login-header {
            background: linear-gradient(180deg, #0ea5e9 0%, #0284c7 100%); /* Blue gradient */
            color: white;
            padding: 1.5rem 1rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .header-logo-circle {
            width: 80px;
            height: 80px;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem auto;
            border: 2px solid rgba(255, 255, 255, 0.3);
        }

        .header-logo-circle img {
             width: 50px; /* Adjust size */
             height: 50px; /* Adjust size */
        }

        .header-welcome-text {
            font-size: 1.1rem;
            font-weight: 600;
            margin: 0;
        }

        .login-form-card {
            background-color: white;
            padding: 2rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: #475569; /* Slate gray */
            font-size: 0.9rem;
        }

        .form-input {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #cbd5e1; /* Light gray border */
            border-radius: 0.25rem;
            font-size: 1rem;
            box-sizing: border-box; /* Include padding in width */
            outline: none;
            transition: border-color 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        }
         .form-input:focus {
             border-color: #3b82f6; /* Blue focus */
             box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
         }

        .radio-group {
            display: flex;
            gap: 1rem;
            margin-top: 0.5rem;
             color: #334155; /* Darker gray */
        }

        .radio-label {
            display: flex;
            align-items: center;
            gap: 0.3rem;
            font-size: 0.9rem;
            cursor: pointer;
        }

        .checkbox-group {
            margin-top: 1rem;
            margin-bottom: 1.5rem;
             color: #334155;
             font-size: 0.9rem;
        }
         .checkbox-group label {
             display: flex;
             align-items: center;
             gap: 0.3rem;
             cursor: pointer;
         }

        .button {
            display: block;
            width: 100%;
            padding: 0.75rem;
            background-color: #0ea5e9; /* Blue button */
            color: white;
            border: none;
            border-radius: 0.25rem;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            text-align: center;
            transition: background-color 0.2s ease-in-out;
        }

        .button:hover {
            background-color: #0284c7; /* Darker blue on hover */
        }

        .error {
            color: #ef4444; /* Red for errors */
            margin-top: 0.5rem; /* Increased margin */
            font-size: 0.875rem;
        }
    </style>
</head>
<body>
    <div class="login-page-container">
        <div class="login-header">
             <div class="header-logo-circle">
                 {{-- Replace with your actual logo --}}
                 <img src="{{ asset('log.jpg') }}" alt="Smart Water Tank Logo">
             </div>
            <h2 class="header-welcome-text">WELCOME BACK TO SMART WATER TANK</h2>
        </div>

        <div class="login-form-card">
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="form-group">
                    <label for="email" class="form-label">Email Address</label>
                    <input id="email" type="email" class="form-input" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                    @error('email')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <input id="password" type="password" class="form-input" name="password" required autocomplete="current-password">
                    @error('password')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Login As</label>
                    <div class="radio-group">
                        <label class="radio-label">
                            <input type="radio" name="role" value="admin" {{ old('role') == 'admin' ? 'checked' : '' }} required>
                            ADMIN
                        </label>
                        <label class="radio-label">
                            <input type="radio" name="role" value="agent" {{ old('role') == 'agent' ? 'checked' : '' }} required>
                            AGENT
                        </label>
                        <label class="radio-label">
                            <input type="radio" name="role" value="client" {{ old('role') == 'client' ? 'checked' : '' }} required>
                            CLIENT
                        </label>
                    </div>
                    @error('role')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="checkbox-group">
                    <label>
                        <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        Remember Me
                    </label>
                </div>

                <div class="form-group">
                    <button type="submit" class="button">Login</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>