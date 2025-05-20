@extends('layouts.app')

@section('content')

<style>
    body {
        margin: 0;
        font-family: sans-serif;
        background-color: #f7fafc; /* bg-gray-100 */
    }
    .header {
        background-color: #2b6cb0; /* bg-blue-600 */
        color: white;
        padding-top: 2rem; /* py-8 */
        padding-bottom: 2rem; /* py-8 */
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    .header .logo-container {
        width: 6rem; /* w-24 */
        height: 6rem; /* h-24 */
        background-color: #2c5282; /* bg-blue-800 */
        border-radius: 9999px; /* rounded-full */
        display: flex;
        align-items: center;
        justify-content: center;
    }
     .header .logo {
        width: 5rem; /* w-20 */
        height: 5rem; /* h-20 */
     }
    .header h1 {
        margin-top: 1rem; /* mt-4 */
        font-size: 1.5rem; /* text-2xl */
        font-weight: 600; /* font-semibold */
    }
    .header p {
        font-size: 0.875rem; /* text-sm */
        margin-top: 0.25rem; /* mt-1 */
    }
    .header h2 {
        margin-top: 1.5rem; /* mt-6 */
        font-size: 1.25rem; /* text-xl */
        font-weight: 500; /* font-medium */
    }

    .login-container {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 3rem 1rem; /* py-12 px-4 */
    }
    .login-form-container {
        max-width: 28rem; /* max-w-md */
        width: 100%;
        margin-top: -6rem; /* Adjust to overlap with header */
        /* space-y-8 */
        display: flex;
        flex-direction: column;
        gap: 2rem; /* approximating space-y-8 */
        background-color: white;
        padding: 2.5rem; /* p-10 */
        border-radius: 0.75rem; /* rounded-xl */
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05); /* shadow-lg */
    }
    .login-form-container h3 {
         text-align: center;
         font-size: 1.5rem; /* text-2xl */
         font-weight: 700; /* font-bold */
         color: #1a202c; /* text-gray-900 */
    }
    .login-form {
        /* mt-8 */
        /* space-y-6 */
        display: flex;
        flex-direction: column;
        gap: 1.5rem; /* approximating space-y-6 */
    }
    .form-group label {
        display: block;
        font-size: 0.875rem; /* text-sm */
        font-weight: 500; /* font-medium */
        color: #4a5568; /* text-gray-700 */
        margin-bottom: 0.25rem; /* mt-1 */
    }
    .form-group input[type="email"], 
    .form-group input[type="password"],
    .form-group select {
        appearance: none;
        border-radius: 0.375rem; /* rounded-md */
        position: relative;
        display: block;
        width: 100%;
        padding: 0.5rem 0.75rem; /* px-3 py-2 */
        border: 1px solid #cbd5e0; /* border-gray-300 */
        placeholder-color: #a0aec0; /* placeholder-gray-500 */
        color: #1a202c; /* text-gray-900 */
        font-size: 0.875rem; /* sm:text-sm */
    }
    .form-group input:focus, 
    .form-group select:focus {
        outline: none;
        ring-width: 2px; /* focus:ring-2 */
        ring-offset-width: 2px; /* focus:ring-offset-2 */
        ring-color: #667eea; /* focus:ring-indigo-500 */
        border-color: #667eea; /* focus:border-indigo-500 */
        z-index: 10; /* focus:z-10 */
    }
     .form-group input.border-red-500, 
     .form-group select.border-red-500 {
         border-color: #f56565; /* border-red-500 */
     }
    .error-message {
        color: #f56565; /* text-red-500 */
        font-size: 0.75rem; /* text-xs */
        margin-top: 0.25rem; /* mt-1 */
    }

    .role-selection label {
        display: inline-flex;
        align-items: center;
        margin-right: 1rem; /* space-x-4, adjust as needed */
    }
     .role-selection input[type="radio"] {
         /* form-radio default styling is usually sufficient or needs specific override */
     }
    .role-selection span {
        margin-left: 0.5rem; /* ml-2 */
        color: #4a5568; /* text-gray-700 */
    }

    .remember-me {
        display: flex;
        align-items: center;
    }
    .remember-me input[type="checkbox"] {
        height: 1rem; /* h-4 */
        width: 1rem; /* w-4 */
        color: #667eea; /* text-indigo-600 */
        border-color: #cbd5e0; /* border-gray-300 */
        border-radius: 0.25rem; /* rounded */
        /* focus:ring-indigo-500 */
    }
    .remember-me label {
        margin-left: 0.5rem; /* ml-2 */
        display: block;
        font-size: 0.875rem; /* text-sm */
        color: #1a202c; /* text-gray-900 */
    }

    .login-button {
        width: 100%;
        display: flex;
        justify-content: center;
        padding: 0.5rem 1rem; /* py-2 px-4 */
        border: 1px solid transparent; /* border border-transparent */
        font-size: 0.875rem; /* text-sm */
        font-weight: 500; /* font-medium */
        border-radius: 0.375rem; /* rounded-md */
        color: white;
        background-color: #4299e1; /* bg-blue-500 */
        cursor: pointer;
        transition: background-color 0.15s ease-in-out; /* hover:bg-blue-600 */
         position: relative;
    }
    .login-button:hover {
        background-color: #3182ce; /* hover:bg-blue-600 */
    }
     .login-button:focus {
        outline: none;
        ring-width: 2px; /* focus:ring-2 */
        ring-offset-width: 2px; /* focus:ring-offset-2 */
        ring-color: #4299e1; /* focus:ring-blue-500 */
    }
     .login-button .lock-icon {
        position: absolute;
        left: 0;
        inset-y: 0;
        display: flex;
        align-items: center;
        padding-left: 0.75rem; /* pl-3 */
     }
     .login-button .lock-icon svg {
         height: 1.25rem; /* h-5 */
         width: 1.25rem; /* w-5 */
         color: #63b3ed; /* text-blue-500 */
         transition: color 0.15s ease-in-out; /* group-hover:text-blue-400 */
     }
      .login-button:hover .lock-icon svg {
          color: #90cdf4; /* group-hover:text-blue-400 */
      }

</style>

<div class="header">
    <div class="logo-container">
        {{-- Insert your logo image tag here --}}
         <img src="{{ asset('images/swt-logo.png') }}" alt="SWT Monitoring" class="logo">
    </div>
    <h1>SWT Monitoring</h1>
    <p>Clean Drop of Water For a Better Tomorrow</p>
    <h2>WELCOME BACK TO SMART WATER TANK</h2>
</div>

<div class="login-container">
    <div class="login-form-container">
        <div>
            <h3>
                Sign in to your account
            </h3>
        </div>
        <form class="login-form" action="{{ route('login') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label for="email">Email Address</label>
                <div>
                    <input id="email" name="email" type="email" required 
                        class="@error('email') border-red-500 @enderror" 
                        placeholder=""
                        value="{{ old('email') }}">
                    @error('email')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <div>
                    <input id="password" name="password" type="password" required 
                        class="@error('password') border-red-500 @enderror" 
                        placeholder="">
                    @error('password')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="role-selection">
                <label>Login As</label>
                <div class="mt-2">
                    <label>
                        <input type="radio" name="role" value="admin" class="form-radio" {{ old('role') == 'admin' ? 'checked' : '' }} required>
                        <span>ADMIN</span>
                    </label>
                    <label>
                        <input type="radio" name="role" value="agent" class="form-radio" {{ old('role') == 'agent' ? 'checked' : '' }} required>
                        <span>AGENT</span>
                    </label>
                    <label>
                        <input type="radio" name="role" value="client" class="form-radio" {{ old('role') == 'client' ? 'checked' : '' }} required>
                        <span>CLIENT</span>
                    </label>
                </div>
                @error('role')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="remember-me">
                <input id="remember_me" name="remember" type="checkbox" 
                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                <label for="remember_me"> Remember me</label>
            </div>

            <div>
                <button type="submit" class="login-button">
                    Login
                </button>
            </div>
        </form>
    </div>
</div>
@endsection 