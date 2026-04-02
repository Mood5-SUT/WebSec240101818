@extends('layouts.master')

@section('title', 'My Profile')

@section('content')
<div class="row g-4">
    <div class="col-md-{{ auth()->user()->hasRole('Member') ? '5' : '12' }}">
        <div class="card shadow-sm h-100">
            <div class="card-header">Account Information</div>
            <div class="card-body">
                <p><strong>Name:</strong> {{auth()->user()->name}}</p>
                <p><strong>Email:</strong> {{auth()->user()->email}}</p>
                <p><strong>Role:</strong> {{ auth()->user()->getRoleNames()->implode(', ') }}</p>
                @role('Member')
                    <p><strong>Borrowing Limit:</strong> {{ $borrowingLimit }}</p>
                    <p><strong>Remaining Slots:</strong> {{ $remainingLimit }}</p>
                    <p><strong>Status:</strong> {{ $borrowingStatus }}</p>
                @endrole
            </div>
        </div>
    </div>
    @role('Member')
        <div class="col-md-7">
            <div class="card shadow-sm">
                <div class="card-header">Currently Borrowed Books</div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Author</th>
                                    <th>Borrowed At</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($activeBorrows as $borrow)
                                    <tr>
                                        <td>{{ $borrow->book->title }}</td>
                                        <td>{{ $borrow->book->author }}</td>
                                        <td>{{ $borrow->borrowed_at?->format('Y-m-d H:i') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center">No borrowed books found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endrole

    <div class="col-md-{{ auth()->user()->hasRole('Member') ? '7' : '12' }}">
        <div class="card shadow-sm {{ auth()->user()->hasRole('Member') ? 'mt-4' : '' }}">
            <div class="card-header">Change Password</div>
            <div class="card-body">
                <form action="{{ route('users_password') }}" method="POST">
                    {{ csrf_field() }}
                    <div class="mb-3">
                        <label class="form-label">Old Password</label>
                        <input type="password" name="old_password" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">New Password</label>
                        <input type="password" name="password" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Confirm New Password</label>
                        <input type="password" name="password_confirmation" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-warning">Change Password</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
