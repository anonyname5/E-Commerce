@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="auth-wrapper">
    <div class="auth-card">
        <div class="card-header">
            <h4>Welcome Back</h4>
            <p class="text-muted mb-0">Please sign in to continue</p>
        </div>
        
        <div class="card-body">
            <form method="POST" action="{{ route('login') }}">
                @csrf
                
                <div class="mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                           name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" 
                           name="password" required autocomplete="current-password">
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                
                <div class="form-check-remember">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" 
                               id="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label" for="remember">
                            Remember Me
                        </label>
                    </div>
                    @if (Route::has('password.request'))
                        <a class="btn-link" href="{{ route('password.request') }}">
                            Forgot Password?
                        </a>
                    @endif
                </div>
                
                <button type="submit" class="btn btn-primary">
                    Sign In
                </button>
            </form>

            <div class="auth-divider">
                <span>or continue with</span>
            </div>

            <div class="social-login">
                <a href="#" class="btn">
                    <i class="fab fa-google"></i>
                    Google
                </a>
                <a href="#" class="btn">
                    <i class="fab fa-facebook-f"></i>
                    Facebook
                </a>
            </div>

            <div class="auth-footer">
                Don't have an account? 
                <a href="{{ route('register') }}">Sign up</a>
            </div>
        </div>
    </div>
</div>
@endsection 