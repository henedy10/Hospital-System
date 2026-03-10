@extends('layouts.auth')

@section('title', 'Register')

@section('content')
    <div class="auth-header">
        <h1>Join Us</h1>
        <p>Create your patient medical account</p>
    </div>

    @if ($errors->any())
        <div
            style="background: rgba(239, 68, 68, 0.1); color: #ef4444; padding: 10px; border-radius: 8px; margin-bottom: 20px; font-size: 0.85rem;">
            <ul style="list-style: none; padding: 0; margin: 0;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ url('/register') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="name">Full Name</label>
            <input type="text" id="name" name="name" class="form-control" placeholder="Full Name" value="{{ old('name') }}"
                required autofocus>
        </div>

        <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" class="form-control" placeholder="Email Address"
                value="{{ old('email') }}" required>
        </div>

        <div class="form-group">
            <label for="phone">Phone Number</label>
            <input type="text" id="phone" name="phone" class="form-control" placeholder="Phone Number"
                value="{{ old('phone') }}" required>
        </div>

        <div class="form-group">
            <label for="gender">Gender</label>
            <select id="gender" name="gender" class="form-control" required>
                <option value="">Select gender</option>
                <option value="male" {{ old('gender') === 'male' ? 'selected' : '' }}>Male</option>
                <option value="female" {{ old('gender') === 'female' ? 'selected' : '' }}>Female</option>
            </select>
        </div>

        <div class="form-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
            <div class="form-group">
                <label for="weight">Weight (kg)</label>
                <input type="number" id="weight" name="weight" class="form-control" placeholder="e.g. 70"
                    value="{{ old('weight') }}" min="0" max="500" step="0.1">
            </div>
            <div class="form-group">
                <label for="height">Height (cm)</label>
                <input type="number" id="height" name="height" class="form-control" placeholder="e.g. 170"
                    value="{{ old('height') }}" min="0" max="300" step="0.1">
            </div>
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" class="form-control" placeholder="••••••••" required>
        </div>

        <div class="form-group">
            <label for="password_confirmation">Confirm Password</label>
            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control"
                placeholder="••••••••" required>
        </div>

        <button type="submit" class="btn-primary" style="margin-top: 20px;">Create Account</button>

    </form>

    <div class="auth-footer">
        <p>Already have an account? <a href="{{ route('login') }}">Sign In</a></p>
    </div>
@endsection
