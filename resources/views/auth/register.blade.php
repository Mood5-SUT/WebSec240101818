@extends('layouts.master')

@section('title', 'Register')

@section('content')
<div class="row justify-content-center align-items-center" style="min-height: 70vh;">
    <div class="col-md-5">
        <div class="card card-custom">
            <div class="card-header card-header-custom text-center">
                <h3 class="mb-0 fw-bold">Create Account</h3>
                <p class="text-muted small mb-0 mt-2">Any newly registered user will take the <strong>student</strong> role by default</p>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('do_register') }}" method="POST">
                    {{ csrf_field() }}

                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name</label>
                        <div class="input-group">
                            <span class="input-group-text bg-dark border-secondary text-muted"><i class="fa-solid fa-user"></i></span>
                            <input type="text" class="form-control text-light" id="name" name="name" value="{{ old('name') }}" placeholder="John Doe" required autofocus>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <div class="input-group">
                            <span class="input-group-text bg-dark border-secondary text-muted"><i class="fa-solid fa-envelope"></i></span>
                            <input type="email" class="form-control text-light" id="email" name="email" value="{{ old('email') }}" placeholder="name@study.com" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group">
                            <span class="input-group-text bg-dark border-secondary text-muted"><i class="fa-solid fa-lock"></i></span>
                            <input type="password" class="form-control text-light" id="password" name="password" placeholder="••••••••" required>
                        </div>
                        <div class="form-text text-muted small mt-1">
                            Password rules: At least 8 characters, containing uppercase and lowercase letters, numbers, and symbols.
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                        <div class="input-group">
                            <span class="input-group-text bg-dark border-secondary text-muted"><i class="fa-solid fa-circle-check"></i></span>
                            <input type="password" class="form-control text-light" id="password_confirmation" name="password_confirmation" placeholder="••••••••" required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary-custom w-100 py-2.5 mb-3">
                        Register Account <i class="fa-solid fa-user-plus ms-1"></i>
                    </button>
                </form>

                <div class="text-center text-muted small mt-2">
                    Already have an account? <a href="{{ route('login') }}" class="text-indigo text-decoration-none fw-semibold">Login here</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
