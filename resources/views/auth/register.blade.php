@extends('layouts.app')

@section('title', 'Register')

@section('content')
<div class="auth-wrapper">
    <div class="auth-card">
        <div class="card-header">
            <h4>Create Account</h4>
            <p class="text-muted mb-0">Join us today! It only takes a few steps</p>
        </div>
        
        <div class="card-body">
            <form method="POST" action="{{ route('register') }}">
                @csrf
                
                <div class="mb-3">
                    <label for="name" class="form-label">Full Name</label>
                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" 
                           name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                    @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                           name="email" value="{{ old('email') }}" required autocomplete="email">
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <small class="text-muted">We'll never share your email with anyone else.</small>
                </div>
                
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" 
                           name="password" required autocomplete="new-password">
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <small class="text-muted">Password must be at least 8 characters long.</small>
                </div>
                
                <div class="mb-4">
                    <label for="password-confirm" class="form-label">Confirm Password</label>
                    <input id="password-confirm" type="password" class="form-control" 
                           name="password_confirmation" required autocomplete="new-password">
                </div>
                
                <div class="mb-3 form-check">
                    <input class="form-check-input" type="checkbox" name="terms" id="terms" required>
                    <label class="form-check-label" for="terms">
                        I agree to the <a href="#" class="btn-link">Terms of Service</a> and 
                        <a href="#" class="btn-link">Privacy Policy</a>
                    </label>
                </div>
                
                <button type="submit" class="btn btn-primary">
                    Create Account
                </button>
            </form>

            <div class="auth-divider">
                <span>or sign up with</span>
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
                Already have an account? 
                <a href="{{ route('login') }}">Sign in</a>
            </div>
        </div>
    </div>
</div>
@endsection 