<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}">
            <i class="fa-solid fa-shield-halved text-indigo"></i>
            <span>Edu<span>Security</span></span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('courses_list') ? 'active' : '' }}" href="{{ route('courses_list') }}">
                        <i class="fa-solid fa-book-open me-1"></i> Courses
                    </a>
                </li>
                @auth
                    <li class="nav-item">
                        <a class="nav-link {{ Route::is('submissions_list') ? 'active' : '' }}" href="{{ route('submissions_list') }}">
                            <i class="fa-solid fa-file-invoice me-1"></i> Submissions
                        </a>
                    </li>
                    
                    @can('create_course')
                        <li class="nav-item">
                            <a class="nav-link {{ Route::is('courses_edit') && !request()->route('id') ? 'active' : '' }}" href="{{ route('courses_edit') }}">
                                <i class="fa-solid fa-plus-circle me-1"></i> Create Course
                            </a>
                        </li>
                    @endcan

                    @role('course_admin')
                        <li class="nav-item">
                            <a class="nav-link {{ Route::is('users_list') ? 'active' : '' }}" href="{{ route('users_list') }}">
                                <i class="fa-solid fa-users-gear me-1"></i> Manage Roles
                            </a>
                        </li>
                    @endrole

                    @can('audit_security')
                        <li class="nav-item">
                            <a class="nav-link {{ Route::is('security_check') ? 'active' : '' }}" href="{{ route('security_check') }}">
                                <i class="fa-solid fa-shield-cat text-warning me-1"></i> Security Auditor
                            </a>
                        </li>
                    @endcan
                @endauth
            </ul>
            
            <ul class="navbar-nav ms-auto align-items-center">
                @auth
                    <li class="nav-item dropdown me-3">
                        <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-solid fa-user-circle fs-5"></i>
                            <span>{{ auth()->user()->name }}</span>
                            @if(auth()->user()->hasRole('course_admin'))
                                <span class="badge badge-admin">Admin</span>
                            @elseif(auth()->user()->hasRole('instructor'))
                                <span class="badge badge-instructor">Instructor</span>
                            @else
                                <span class="badge badge-student">Student</span>
                            @endif
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end bg-dark border-secondary" aria-labelledby="navbarDropdown">
                            <li>
                                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                                    <i class="fa-solid fa-key me-2 text-warning"></i> Change Password
                                </a>
                            </li>
                            <li><hr class="dropdown-divider border-secondary"></li>
                            <li>
                                <form action="{{ route('do_logout') }}" method="POST" class="d-inline">
                                    {{ csrf_field() }}
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="fa-solid fa-sign-out-alt me-2"></i> Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link {{ Route::is('login') ? 'active' : '' }}" href="{{ route('login') }}">
                            <i class="fa-solid fa-right-to-bracket me-1"></i> Login
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Route::is('register') ? 'active' : '' }}" href="{{ route('register') }}">
                            <i class="fa-solid fa-user-plus me-1"></i> Register
                        </a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

@auth
<!-- Change Password Modal -->
<div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content bg-dark border-secondary text-light">
            <div class="modal-header border-secondary">
                <h5 class="modal-title" id="changePasswordModalLabel"><i class="fa-solid fa-key me-2 text-warning"></i>Change Password</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('change_password') }}" method="POST">
                {{ csrf_field() }}
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="old_password" class="form-label">Current Password</label>
                        <input type="password" class="form-control text-light" id="old_password" name="old_password" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">New Password</label>
                        <input type="password" class="form-control text-light" id="password" name="password" required>
                        <div class="form-text text-muted">Must be at least 8 characters with letters, numbers, casing, and symbols.</div>
                    </div>
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirm New Password</label>
                        <input type="password" class="form-control text-light" id="password_confirmation" name="password_confirmation" required>
                    </div>
                </div>
                <div class="modal-footer border-secondary">
                    <button type="button" class="btn btn-secondary-custom" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary-custom px-4">Update Password</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endauth
