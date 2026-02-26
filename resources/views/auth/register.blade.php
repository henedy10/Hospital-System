@extends('layouts.auth')

@section('title', 'Register')

@section('content')
    <div class="auth-header">
        <h1>Create Account</h1>
        <p>Join the hospital management system</p>
    </div>

    @if(session('error'))
        <div
            style="background: rgba(239, 68, 68, 0.1); color: #ef4444; padding: 10px; border-radius: 8px; margin-bottom: 20px; font-size: 0.85rem; text-align: center;">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ url('/register') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="name">Full Name</label>
            <input type="text" id="name" name="name" class="form-control" placeholder="Dr. John Doe" required>
        </div>

        <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" class="form-control" placeholder="doctor@hospital.com" required>
        </div>

        <div class="form-group">
            <label for="role">Role</label>
            <select id="role" name="role" class="form-control" required
                style="appearance: none; background-image: url('data:image/svg+xml;charset=UTF-8,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%2224%22 height=%2224%22 viewBox=%220 0 24 24%22 fill=%22none%22 stroke=%22currentColor%22 stroke-width=%222%22 stroke-linecap=%22round%22 stroke-linejoin=%22round%22%3E%3Cpolyline points=%226 9 12 15 18 9%22%3E%3C/polyline%3E%3C/svg%3E'); background-repeat: no-repeat; background-position: right 1rem center; background-size: 1em;">
                <option value="" disabled selected>Select your role</option>
                <option value="doctor">Doctor</option>
                <option value="nurse">Nurse</option>
                <option value="admin">Administrator</option>
                <option value="patient">Patient</option>
            </select>
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" class="form-control" placeholder="••••••••" required>
        </div>

        <button type="submit" class="btn-primary" style="margin-top: 20px;">Register</button>
    </form>

    <div class="auth-footer">
        <p>Already have an account? <a href="{{ route('login') }}">Sign In</a></p>
    </div>
@endsection