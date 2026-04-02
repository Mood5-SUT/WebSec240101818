@extends('layouts.master')

@section('title', 'Librarian Account')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header">{{ isset($user->id) ? 'Edit Librarian' : 'Create Librarian' }}</div>
            <div class="card-body">
                <form action="{{ route('users_save', $user->id ?? '') }}" method="POST">
                    {{ csrf_field() }}
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" value="{{$user->name ?? ''}}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="{{$user->email ?? ''}}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password {{ isset($user->id) ? '(Leave blank to keep current password)' : '' }}</label>
                        <input type="password" name="password" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-primary">Save Librarian</button>
                    <a href="{{ route('users_list') }}" class="btn btn-outline-secondary">Back</a>
                </form>

                @if(isset($user->id))
                    <hr>
                    <form action="{{ route('users_delete', $user->id) }}" method="POST">
                        {{ csrf_field() }}
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Delete this librarian account?')">Delete Librarian</button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
