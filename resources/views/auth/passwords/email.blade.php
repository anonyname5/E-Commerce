@extends('layouts.app')

@section('title', 'Reset Password')

@section('content')
<div class="auth-wrapper">
    <div class="auth-card">
        <div class="card-header">
            <h4>Reset Password</h4>
            <p class="text-muted mb-0">Enter your email to receive reset instructions</p>
        </div>
        
        <div class="card-body">
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <div class="mb-4">
                    <label for="email" class="form-label">Email Address</label>
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                           name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">
                    Send Reset Link
                </button>
            </form>

            <div class="auth-footer">
                Remember your password? 
                <a href="{{ route('login') }}">Sign in</a>
            </div>
        </div>
    </div>
</div>
@endsection 