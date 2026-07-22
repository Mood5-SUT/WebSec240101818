@extends('layouts.master')

@section('title', 'Manage User Roles')

@section('content')
<div class="card card-custom">
    <div class="card-header card-header-custom d-flex justify-content-between align-items-center">
        <div>
            <h3 class="mb-0 fw-bold"><i class="fa-solid fa-users-gear text-indigo me-2"></i>Manage User Roles</h3>
            <p class="text-muted small mb-0 mt-1">Course Admins can assign instructor, course_admin, or student roles to users.</p>
        </div>
        <span class="badge badge-admin p-2 fs-6">Admin Panel</span>
    </div>
    <div class="card-body p-4">
        <div class="table-responsive">
            <table class="table table-dark table-hover border-secondary align-middle">
                <thead>
                    <tr class="text-muted">
                        <th>User Name</th>
                        <th>Email</th>
                        <th>Current Roles</th>
                        <th>Action / Assign Role</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $usr)
                        <tr>
                            <td><strong>{{ $usr->name }}</strong></td>
                            <td><code>{{ $usr->email }}</code></td>
                            <td>
                                @foreach($usr->roles as $role)
                                    @if($role->name == 'course_admin')
                                        <span class="badge badge-admin">course_admin</span>
                                    @elseif($role->name == 'instructor')
                                        <span class="badge badge-instructor">instructor</span>
                                    @else
                                        <span class="badge badge-student">student</span>
                                    @endif
                                @endforeach
                                @if($usr->roles->isEmpty())
                                    <span class="badge bg-secondary">No Role</span>
                                @endif
                            </td>
                            <td>
                                <form action="{{ route('users_save') }}" method="POST" class="row g-2 align-items-center">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="user_id" value="{{ $usr->id }}">
                                    
                                    <div class="col-auto">
                                        <select name="roles[]" class="form-select form-select-sm text-light bg-dark border-secondary" required>
                                            <option value="" disabled selected>Select Role...</option>
                                            @foreach($roles as $role)
                                                <option value="{{ $role->name }}" {{ $usr->hasRole($role->name) ? 'selected' : '' }}>
                                                    {{ $role->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-auto">
                                        <button type="submit" class="btn btn-primary-custom btn-sm px-3">
                                            <i class="fa-solid fa-save me-1"></i> Update
                                        </button>
                                    </div>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
