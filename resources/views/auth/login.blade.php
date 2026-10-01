@extends('layouts.app')

@section('title', 'Login - libasbd')
@section('robots', 'noindex, follow')

@section('content')
<div class="login-page">
    <div class="login-container">
        <div class="login-card">
            <h2 class="login-title">Login to Your Account</h2>
            
            <form method="POST" action="{{ route('login.submit') }}" class="login-form">
                @csrf
                
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           class="form-input @error('email') error @enderror" 
                           value="{{ old('email') }}" 
                           placeholder="you@example.com"
                           required 
                           autofocus>
                    @error('email')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" 
                           id="password" 
                           name="password" 
                           class="form-input @error('password') error @enderror" 
                           placeholder="Your password"
                           required>
                    @error('password')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
                
                @if(session('success'))
                    <div class="success-message">{{ session('success') }}</div>
                @endif

                <div class="form-options">
                    <label class="checkbox-label">
                        <input type="checkbox" name="remember">
                        <span>Remember me</span>
                    </label>
                    <a href="{{ route('password.request') }}" class="forgot-link">Forgot password?</a>
                </div>
                
                <button type="submit" class="login-button">Login</button>
            </form>
            
            <div class="login-links">
                <a href="{{ route('register') }}">Don't have an account? Register here</a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.login-page {
    min-height: calc(100vh - 300px);
    display: flex;
    align-items: center;
    padding: 60px 0;
    background: #f8f9fa;
}

.login-container {
    width: 100%;
    max-width: 400px;
    margin: 0 auto;
    padding: 0 20px;
}

.login-card {
    background: white;
    padding: 40px 35px;
    border-radius: 12px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    border: 1px solid #eee;
}

.login-title {
    text-align: center;
    margin-bottom: 30px;
    color: #333;
    font-size: 1.8rem;
    font-weight: 600;
}

.login-form .form-group {
    margin-bottom: 25px;
}

.login-form label {
    display: block;
    margin-bottom: 8px;
    font-weight: 500;
    color: #555;
    font-size: 14px;
}

.form-input {
    width: 100%;
    padding: 14px 16px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 16px;
    transition: all 0.3s ease;
    background: #fafafa;
}

.form-input:focus {
    outline: none;
    border-color: #a07f2a;
    background: white;
    box-shadow: 0 0 0 3px rgba(0, 102, 204, 0.1);
}

.form-input.error {
    border-color: #dc3545;
}

.error-message {
    color: #dc3545;
    font-size: 14px;
    margin-top: 5px;
    display: block;
}

.form-options {
    margin-bottom: 25px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.forgot-link {
    font-size: 14px;
    color: #a07f2a;
    text-decoration: none;
}

.forgot-link:hover {
    text-decoration: underline;
}

.success-message {
    background: #e8f5e9;
    border: 1px solid #a5d6a7;
    color: #2e7d32;
    padding: 12px 16px;
    border-radius: 6px;
    font-size: 14px;
    margin-bottom: 20px;
    text-align: center;
}

.checkbox-label {
    display: flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    font-size: 14px;
    color: #555;
}

.checkbox-label input {
    width: 16px;
    height: 16px;
}

.login-button {
    width: 100%;
    padding: 14px;
    background: #a07f2a;
    color: white;
    border: none;
    border-radius: 6px;
    font-size: 16px;
    font-weight: 500;
    cursor: pointer;
    transition: background 0.3s ease;
}

.login-button:hover {
    background: #0056b3;
}

.login-links {
    margin-top: 25px;
    text-align: center;
    padding-top: 20px;
    border-top: 1px solid #eee;
}

.login-links a {
    color: #a07f2a;
    text-decoration: none;
    font-size: 14px;
}

.login-links a:hover {
    text-decoration: underline;
}

@media (max-width: 768px) {
    .login-page {
        padding: 40px 0;
    }
    
    .login-card {
        padding: 30px 25px;
    }
}
</style>
@endpush
