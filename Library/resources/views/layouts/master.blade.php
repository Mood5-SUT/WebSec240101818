<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') | Library Management System</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/library.css') }}">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark app-navbar sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('home') }}">
                <span class="brand-mark">L</span>
                <span>Library Management System</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('books_list') }}">Books</a>
                    </li>
                    @auth
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('users_profile') }}">My Profile</a>
                        </li>
                    @endauth
                    @can('view_members')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('users_list') }}">Members</a>
                        </li>
                    @endcan
                    @can('view_roles')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('roles_list') }}">Roles</a>
                        </li>
                    @endcan
                    @can('manage_users')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('users_edit') }}">Create Librarian</a>
                        </li>
                    @endcan
                </ul>
                <ul class="navbar-nav ms-auto">
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('users_login') }}">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('users_register') }}">Register</a>
                        </li>
                    @endguest
                    @auth
                        <li class="nav-item">
                            <span class="nav-link">{{auth()->user()->name}}</span>
                        </li>
                        <li class="nav-item">
                            <form action="{{ route('users_logout') }}" method="POST" class="d-inline">
                                {{ csrf_field() }}
                                <button type="submit" class="btn btn-link nav-link">Logout</button>
                            </form>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <main class="py-4 py-lg-5">
        <div class="container">
            @if(session('success'))
                <div class="alert alert-success app-alert">{{ session('success') }}</div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger app-alert">{{ session('error') }}</div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger app-alert">
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
