@extends('layouts.master')

@section('title', 'Register')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card shadow-sm">
            <div class="card-header">Member Registration</div>
            <div class="card-body">
                <form action="{{ route('users_register_save') }}" method="POST">
                    {{ csrf_field() }}
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-success">Register</button>
                    <a href="{{ route('users_login') }}" class="btn btn-outline-secondary">Back to Login</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
