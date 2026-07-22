@extends('layouts.master')

@section('title', 'Login')

@section('content')
<div class="row justify-content-center align-items-center" style="min-height: 70vh;">
    <div class="col-md-5">
        
        @if(session('demo_verify_link'))
            <div class="alert alert-warning border-0 shadow mb-4" style="background-color: rgba(245, 158, 11, 0.15); color: #fef08a; border-radius: 12px;">
                <h5><i class="fa-solid fa-flask me-2 text-warning"></i>Course Project Helper:</h5>
                <p class="small mb-2">Since email sending is simulated in logs, click the link below to verify the account:</p>
                <a href="{{ session('demo_verify_link') }}" class="btn btn-warning btn-sm fw-semibold w-100">
                    <i class="fa-solid fa-user-check me-1"></i> Verify Newly Registered User Account
                </a>
            </div>
        @endif

        <div class="card card-custom">
            <div class="card-header card-header-custom text-center">
                <h3 class="mb-0 fw-bold">Sign In</h3>
                <p class="text-muted small mb-0 mt-2">Access your EduSecurity dashboard</p>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('do_login') }}" method="POST">
                    {{ csrf_field() }}

                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <div class="input-group">
                            <span class="input-group-text bg-dark border-secondary text-muted"><i class="fa-solid fa-envelope"></i></span>
                            <input type="email" class="form-control text-light" id="email" name="email" value="{{ old('email') }}" placeholder="name@study.com" required autofocus>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group">
                            <span class="input-group-text bg-dark border-secondary text-muted"><i class="fa-solid fa-lock"></i></span>
                            <input type="password" class="form-control text-light" id="password" name="password" placeholder="••••••••" required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary-custom w-100 py-2.5 mb-3">
                        Sign In <i class="fa-solid fa-arrow-right ms-1"></i>
                    </button>
                </form>

                <div class="text-center my-3">
                    <span class="text-muted small">Or Sign In with</span>
                </div>

                <div class="d-flex flex-column gap-2 mb-4">
                    <!-- Google -->
                    <a href="{{ route('social_login', ['provider' => 'google']) }}" class="btn btn-secondary-custom w-100 py-2.5 d-flex align-items-center justify-content-center gap-2 text-light text-decoration-none">
                        <i class="fa-brands fa-google text-danger"></i> Sign In with Google
                    </a>
                    
                    <div class="row g-2">
                        <!-- Facebook -->
                        <div class="col-4">
                            <a href="{{ route('social_login', ['provider' => 'facebook']) }}" class="btn btn-secondary-custom w-100 py-2.5 d-flex align-items-center justify-content-center gap-1 small text-light text-decoration-none" title="Sign In with Facebook">
                                <i class="fa-brands fa-facebook text-primary"></i> Facebook
                            </a>
                        </div>
                        <!-- LinkedIn -->
                        <div class="col-4">
                            <a href="{{ route('social_login', ['provider' => 'linkedin']) }}" class="btn btn-secondary-custom w-100 py-2.5 d-flex align-items-center justify-content-center gap-1 small text-light text-decoration-none" title="Sign In with LinkedIn">
                                <i class="fa-brands fa-linkedin text-info"></i> LinkedIn
                            </a>
                        </div>
                        <!-- Microsoft -->
                        <div class="col-4">
                            <a href="{{ route('social_login', ['provider' => 'microsoft']) }}" class="btn btn-secondary-custom w-100 py-2.5 d-flex align-items-center justify-content-center gap-1 small text-light text-decoration-none" title="Sign In with Microsoft">
                                <i class="fa-brands fa-windows text-light"></i> Microsoft
                            </a>
                        </div>
                    </div>
                </div>

                <div class="text-center text-muted small mt-2">
                    Don't have an account? <a href="{{ route('register') }}" class="text-indigo text-decoration-none fw-semibold">Register here</a>
                </div>
            </div>
        </div>

        <!-- Default Credentials helper -->
        <div class="card bg-dark border-secondary mt-4" style="border-radius: 12px; opacity: 0.85;">
            <div class="card-body p-3 small text-muted">
                <p class="fw-bold mb-2 text-light"><i class="fa-solid fa-circle-info me-1"></i> Default Course Credentials:</p>
                <div class="row text-start">
                    <div class="col-6">
                        <strong>Student:</strong><br>
                        <code>student@study.com</code>
                    </div>
                    <div class="col-6">
                        <strong>Instructor:</strong><br>
                        <code>instructor@study.com</code>
                    </div>
                    <div class="col-12 mt-2">
                        <strong>Admin:</strong> <code>admin@study.com</code> | <strong>Pass:</strong> <code>Secret123!</code>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
