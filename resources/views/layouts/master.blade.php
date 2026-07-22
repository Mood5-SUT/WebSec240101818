<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - EduSecurity</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome for Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #4f46e5;
            --primary-hover: #4338ca;
            --dark-bg: #0f172a;
            --card-bg: #1e293b;
            --text-main: #f8fafc;
            --text-muted: #a6b5c9;
            --border-color: #334155;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--dark-bg);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
        }

        .navbar {
            background-color: rgba(15, 23, 42, 0.8) !important;
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border-color);
            position: sticky;
            top: 0;
            z-index: 1030;
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            letter-spacing: -0.5px;
            color: var(--text-main) !important;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .navbar-brand span {
            background: linear-gradient(135deg, #818cf8, #4f46e5);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .nav-link {
            color: var(--text-muted) !important;
            font-weight: 500;
            transition: all 0.3s ease;
            padding: 0.5rem 1rem !important;
            border-radius: 8px;
        }

        .nav-link:hover, .nav-link.active {
            color: var(--text-main) !important;
            background-color: rgba(255, 255, 255, 0.05);
        }

        .btn-primary-custom {
            background: linear-gradient(135deg, #6366f1, #4f46e5);
            border: none;
            color: white;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .btn-primary-custom:hover {
            background: linear-gradient(135deg, #4f46e5, #4338ca);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
        }

        .btn-secondary-custom {
            background-color: transparent;
            border: 1px solid var(--border-color);
            color: var(--text-main);
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .btn-secondary-custom:hover {
            background-color: var(--border-color);
            color: white;
        }

        .card-custom {
            background-color: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.3);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card-custom:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.5);
            border-color: rgba(99, 102, 241, 0.4);
        }

        .card-header-custom {
            border-bottom: 1px solid var(--border-color);
            background-color: rgba(0, 0, 0, 0.1);
            padding: 1.5rem;
            font-weight: 600;
        }

        .footer {
            margin-top: auto;
            border-top: 1px solid var(--border-color);
            background-color: rgba(15, 23, 42, 0.9);
            padding: 2rem 0;
            color: var(--text-muted);
            font-size: 0.9rem;
        }

        /* Forms Styling */
        .form-control, .form-select {
            background-color: #0f172a !important;
            border: 1px solid var(--border-color) !important;
            color: #f8fafc !important;
            border-radius: 8px;
            padding: 0.6rem 1rem;
        }

        .form-control:focus, .form-select:focus {
            background-color: #0f172a !important;
            border-color: var(--primary-color) !important;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.2) !important;
            color: #f8fafc !important;
        }

        /* Input group prefix icon styling */
        .input-group-text {
            background-color: #1e293b !important;
            border-color: var(--border-color) !important;
            color: var(--text-muted) !important;
        }

        /* Placeholder text visibility */
        .form-control::placeholder {
            color: #64748b !important;
            opacity: 1 !important;
        }

        .form-label {
            font-weight: 500;
            color: var(--text-muted);
            margin-bottom: 0.5rem;
        }

        /* Badges */
        .badge-student {
            background-color: rgba(59, 130, 246, 0.15) !important;
            color: #60a5fa !important;
            border: 1px solid rgba(59, 130, 246, 0.3) !important;
        }

        .badge-instructor {
            background-color: rgba(16, 185, 129, 0.15) !important;
            color: #34d399 !important;
            border: 1px solid rgba(16, 185, 129, 0.3) !important;
        }

        .badge-admin {
            background-color: rgba(239, 68, 68, 0.15) !important;
            color: #f87171 !important;
            border: 1px solid rgba(239, 68, 68, 0.3) !important;
        }

        .bg-dark-panel {
            background-color: #0f172a !important;
            border: 1px solid var(--border-color) !important;
        }

        /* Custom Indigo Utilities */
        .text-indigo {
            color: #818cf8 !important;
        }
        .bg-indigo {
            background-color: #4f46e5 !important;
        }
        .border-indigo {
            border-color: #818cf8 !important;
        }

        /* Default Link Styling Overrides for Dark Mode */
        a {
            color: #818cf8;
            text-decoration: none;
            transition: color 0.2s ease;
        }
        a:hover {
            color: #a5b4fc;
            text-decoration: none;
        }

        /* Dropdown Menu styling for dark mode readability */
        .dropdown-menu {
            background-color: var(--card-bg) !important;
            border-color: var(--border-color) !important;
        }
        .dropdown-item {
            color: var(--text-main) !important;
        }
        .dropdown-item:hover {
            background-color: var(--border-color) !important;
            color: white !important;
        }
        .dropdown-item.text-danger {
            color: var(--danger-color) !important;
        }
        .dropdown-item.text-danger:hover {
            background-color: rgba(239, 68, 68, 0.15) !important;
            color: #fca5a5 !important;
        }

        /* Input Autofill override for dark theme */
        input:-webkit-autofill,
        input:-webkit-autofill:hover, 
        input:-webkit-autofill:focus, 
        input:-webkit-autofill:active {
            -webkit-box-shadow: 0 0 0 30px #1e293b inset !important;
            -webkit-text-fill-color: #f8fafc !important;
        }

        /* Code elements style for dark mode high contrast */
        code {
            color: #38bdf8 !important;
            background-color: rgba(56, 189, 248, 0.08) !important;
            padding: 0.15rem 0.35rem !important;
            border-radius: 6px !important;
            font-size: 0.9em !important;
            word-break: break-word !important;
        }

        /* Table custom overrides for dark mode */
        .table {
            color: var(--text-main) !important;
        }
        .table th {
            color: var(--text-muted) !important;
            border-bottom-color: var(--border-color) !important;
        }
        .table td {
            border-bottom-color: rgba(51, 65, 85, 0.5) !important;
        }

        /* Global typography color overrides for dark mode */
        h1, h2, h3, h4, h5, h6, .h1, .h2, .h3, .h4, .h5, .h6 {
            color: var(--text-main) !important;
        }
        .text-muted {
            color: var(--text-muted) !important;
        }

        /* Micro-animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animate-fade-in {
            animation: fadeIn 0.4s ease forwards;
        }
    </style>
</head>
<body>

    @include('layouts.menu')

    <div class="container my-5 animate-fade-in">
        <!-- Validation and Flash Messages -->
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-lg" role="alert" style="border-radius: 12px; background-color: rgba(239, 68, 68, 0.2); color: #fecaca;">
                <div class="d-flex align-items-center">
                    <i class="fa-solid fa-circle-exclamation fs-4 me-3"></i>
                    <div>
                        <strong class="d-block mb-1">Please correct the following errors:</strong>
                        <ul class="mb-0 ps-3">
                            @foreach($errors->all() as $error)
                                <li>{!! $error !!}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="filter: invert(1);"></button>
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-lg" role="alert" style="border-radius: 12px; background-color: rgba(16, 185, 129, 0.2); color: #d1fae5;">
                <div class="d-flex align-items-center">
                    <i class="fa-solid fa-circle-check fs-4 me-3"></i>
                    <div>
                        {!! session('success') !!}
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="filter: invert(1);"></button>
            </div>
        @endif

        @yield('content')
    </div>

    <footer class="footer mt-auto">
        <div class="container text-center">
            <p class="mb-1"><strong>Web & Security Technologies</strong> Course Project</p>
            <p class="mb-0 text-muted-50 small">&copy; {{ date('Y') }} EduSecurity Systems. Secure study domain: <span class="text-info">www.secure-study.com</span></p>
        </div>
    </footer>

    <!-- Bootstrap 5 Bundle JS CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>
