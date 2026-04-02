@extends('layouts.master')

@section('title', 'Update Member Password')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card shadow-sm">
            <div class="card-header">Update Member Password</div>
            <div class="card-body">
                <p><strong>Member:</strong> {{ $user->name }}</p>
                <p><strong>Email:</strong> {{ $user->email }}</p>

                <form action="{{ route('users_member_password_save', $user->id) }}" method="POST">
                    {{ csrf_field() }}
                    <div class="mb-3">
                        <label class="form-label">New Password</label>
                        <input type="password" name="password" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-primary">Update Password</button>
                    <a href="{{ route('users_list') }}" class="btn btn-outline-secondary">Back</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
