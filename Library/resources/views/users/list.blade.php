@extends('layouts.master')

@section('title', 'Members')

@section('content')
<div class="card shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span>Registered Members</span>
        @can('manage_users')
            <a href="{{ route('users_edit') }}" class="btn btn-sm btn-primary">Create Librarian</a>
        @endcan
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Roles</th>
                        @can('manage_users')
                            <th>Actions</th>
                        @endcan
                    </tr>
                </thead>
                <tbody>
                    @forelse($members as $member)
                        <tr>
                            <td>{{ $member->name }}</td>
                            <td>{{ $member->email }}</td>
                            <td>{{ $member->getRoleNames()->implode(', ') }}</td>
                            @can('manage_users')
                                <td>
                                    <a href="{{ route('users_member_password_edit', $member->id) }}" class="btn btn-sm btn-warning">Update Password</a>
                                </td>
                            @endcan
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">No members found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
