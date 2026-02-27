@extends('layouts.auth')

@section('title', 'Forgot Password')

@section('content')
    <div class="auth-header">
        <h1>Forgot Password?</h1>
        <p>No worries, we'll send you reset instructions.</p>
    </div>

    @if(session('status'))
        <div
            style="background: rgba(13, 148, 136, 0.1); color: var(--primary); padding: 10px; border-radius: 8px; margin-bottom: 20px; font-size: 0.85rem; text-align: center;">
            {{ session('status') }}
        </div>
    @endif

    <form action="{{ url('/forgot-password') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" class="form-control" placeholder="doctor@hospital.com" required
                autofocus>
        </div>

        <button type="submit" class="btn-primary">Reset Password</button>
    </form>

    <div class="auth-footer">
        <p>Remember your password? <a href="{{ route('login') }}">Back to login</a></p>
    </div>
@endsection