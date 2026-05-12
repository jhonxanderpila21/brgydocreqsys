@extends('layouts.auth')

@section('content')
    <div class="card shadow-sm">
        <div class="card-body p-5">
            <h2 class="mb-4">Set New Password</h2>

            <form method="POST" action="{{ route('password.update') }}">
                @csrf

                <input type="hidden" name="token" value="{{ $token }}">

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" value="{{ old('email', $email) }}" class="form-control" required autofocus>
                </div>

                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Confirm Password</label>
                    <input type="password" name="password_confirmation" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary w-100">Reset Password</button>
            </form>

            <div class="mt-3 text-center">
                <a href="{{ route('login') }}">Back to Login</a>
            </div>
        </div>
    </div>
@endsection
