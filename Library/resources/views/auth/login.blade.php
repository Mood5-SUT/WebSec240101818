@extends('layouts.master')

@section('title', 'Login')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header">User Login</div>
            <div class="card-body">
                <form action="{{ route('users_authenticate') }}" method="POST">
                    {{ csrf_field() }}
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-primary">Login</button>
                    <a href="{{ route('users_register') }}" class="btn btn-outline-secondary">Create Account</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
