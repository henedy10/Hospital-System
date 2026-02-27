@extends('layouts.auth')

@section('title', 'Reset Password')

@section('content')
    <div class="auth-header">
        <h1>Reset Password</h1>
        <p>Your new password must be different from previous passwords.</p>
    </div>

    <form action="{{ url('/reset-password') }}" method="POST">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" class="form-control" value="{{ $email ?? old('email') }}" required
                autofocus>
        </div>

        <div class="form-group">
            <label for="password">New Password</label>
            <input type="password" id="password" name="password" class="form-control" placeholder="••••••••" required>
        </div>

        <div class="form-group">
            <label for="password_confirmation">Confirm New Password</label>
            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control"
                placeholder="••••••••" required>
        </div>

        <button type="submit" class="btn-primary">Reset Password</button>
    </form>

    <div class="auth-footer">
        <p>Changed your mind? <a href="{{ route('login') }}">Back to login</a></p>
    </div>
@endsection