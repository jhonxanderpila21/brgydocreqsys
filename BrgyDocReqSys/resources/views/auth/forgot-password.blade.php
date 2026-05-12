@extends('layouts.auth')

@section('content')
    <div class="card shadow-sm">
        <div class="card-body p-5">
            <h2 class="mb-4">Reset Password</h2>
            <p class="text-muted mb-4">Enter your email address and we will send you a password reset link.</p>

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="form-control" required autofocus>
                </div>

                <button type="submit" class="btn btn-primary w-100">Send Password Reset Link</button>
            </form>

            <div class="mt-3 text-center">
                <a href="{{ route('login') }}">Back to Login</a>
            </div>
        </div>
    </div>
@endsection
