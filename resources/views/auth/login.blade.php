@extends('layouts.auth')

@section('title', 'Login')

@section('content')
    <div class="auth-header">
        <h1>Hospital System</h1>
        <p>Please sign in to your medical account</p>
    </div>

    @if(session('error'))
        <div
            style="background: rgba(239, 68, 68, 0.1); color: #ef4444; padding: 10px; border-radius: 8px; margin-bottom: 20px; font-size: 0.85rem; text-align: center;">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ url('/login') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" class="form-control" placeholder="doctor@hospital.com" required
                autofocus>
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" class="form-control" placeholder="••••••••" required>
        </div>

        <div
            style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; font-size: 0.8rem;">
            <label style="display: flex; align-items: center; cursor: pointer; color: var(--text-muted);">
                <input type="checkbox" name="remember" style="margin-right: 8px;"> Remember me
            </label>
            <a href="#" style="color: var(--primary); text-decoration: none; font-weight: 500;">Forgot password?</a>
        </div>

        <button type="submit" class="btn-primary">Sign In</button>
    </form>

    <div class="auth-footer">
        <p>Don't have an account? <a href="{{ route('register') }}">Create an account</a></p>
    </div>
@endsection