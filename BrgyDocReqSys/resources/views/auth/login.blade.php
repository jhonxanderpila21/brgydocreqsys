@extends('layouts.auth')

@section('content')
    <div class="card shadow-sm">
        <div class="card-body p-5">
            <h2 class="mb-4">Login</h2>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="form-control" required autofocus>
                </div>

                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" name="remember" id="remember" class="form-check-input">
                    <label class="form-check-label" for="remember">Remember me</label>
                </div>

                <button type="submit" class="btn btn-primary w-100">Login</button>
            </form>

            <div class="mt-3 text-center">
                <a href="{{ route('password.request') }}">Forgot your password?</a>
            </div>
            <div class="mt-3 text-center">
                <span>Don't have an account?</span>
                <a href="{{ route('register') }}">Register</a>
            </div>
        </div>
    </div>
@endsection
