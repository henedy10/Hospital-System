@extends('layouts.auth')

@section('title', 'Reset Password')

@section('content')
    <div class="auth-header">
        <h1>Reset Password</h1>
        <p>Create a new secure password for your account</p>
    </div>

    @if ($errors->any())
        <div
            style="background: rgba(239, 68, 68, 0.1); color: #ef4444; padding: 10px; border-radius: 8px; margin-bottom: 20px; font-size: 0.85rem;">
            <ul style="list-style: none; padding: 0; margin: 0; text-align: center;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('password.update') }}" method="POST">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" class="form-control" value="{{ $email ?? old('email') }}" required
                readonly>
        </div>

        <div class="form-group">
            <label for="password">New Password</label>
            <input type="password" id="password" name="password" class="form-control" placeholder="••••••••" required
                autofocus>
        </div>

        <div class="form-group">
            <label for="password_confirmation">Confirm New Password</label>
            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control"
                placeholder="••••••••" required>
        </div>

        <button type="submit" class="btn-primary">Update Password</button>
    </form>

    <div class="auth-footer">
        <p>Changed your mind? <a href="{{ route('login') }}">Back to login</a></p>
    </div>
@endsection