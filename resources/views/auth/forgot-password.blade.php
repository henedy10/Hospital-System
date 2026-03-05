@extends('layouts.auth')

@section('title', 'Forgot Password')

@section('content')
    <div class="auth-header">
        <h1>Restore Access</h1>
        <p>Enter your email to reset your password</p>
    </div>

    @if (session('status'))
        <div
            style="background: rgba(16, 185, 129, 0.1); color: #10b981; padding: 10px; border-radius: 8px; margin-bottom: 20px; font-size: 0.85rem; text-align: center;">
            {{ session('status') }}
        </div>
    @endif

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

    <form action="{{ route('password.email') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" class="form-control" placeholder="Email Address" required
                autofocus>
        </div>

        <button type="submit" class="btn-primary">Send Reset Link</button>
    </form>

    <div class="auth-footer">
        <p>Remember your password? <a href="{{ route('login') }}">Sign In</a></p>
    </div>
@endsection