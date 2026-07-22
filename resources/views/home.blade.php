@extends('layouts.master')

@section('title', 'EduSecurity - Premium Cybersecurity LMS')

@section('content')
<style>
    .hero-section {
        padding: 5rem 0 6rem 0;
        text-align: center;
        position: relative;
    }
    .hero-badge {
        background: rgba(99, 102, 241, 0.1);
        border: 1px solid rgba(99, 102, 241, 0.2);
        color: #818cf8;
        padding: 0.5rem 1.25rem;
        border-radius: 9999px;
        font-weight: 500;
        font-size: 0.9rem;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 2rem;
    }
    .hero-title {
        font-size: 3.5rem;
        font-weight: 800;
        line-height: 1.15;
        letter-spacing: -1px;
        margin-bottom: 1.5rem;
    }
    .hero-title span {
        background: linear-gradient(135deg, #818cf8, #4f46e5);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    .hero-description {
        font-size: 1.25rem;
        color: var(--text-muted);
        max-width: 700px;
        margin: 0 auto 3rem auto;
        line-height: 1.6;
    }
    .hero-buttons {
        display: flex;
        justify-content: center;
        gap: 1rem;
        margin-bottom: 4rem;
    }
    .feature-card {
        background: rgba(30, 41, 59, 0.4);
        backdrop-filter: blur(16px);
        border: 1px solid rgba(51, 65, 85, 0.8);
        border-radius: 20px;
        padding: 2.5rem 2rem;
        height: 100%;
        transition: all 0.3s ease;
    }
    .feature-card:hover {
        transform: translateY(-8px);
        border-color: rgba(99, 102, 241, 0.5);
        background: rgba(30, 41, 59, 0.6);
        box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.4);
    }
    .feature-icon {
        width: 60px;
        height: 60px;
        background: rgba(99, 102, 241, 0.1);
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.75rem;
        color: #818cf8;
        margin-bottom: 1.5rem;
    }
    .feature-title {
        font-weight: 700;
        font-size: 1.25rem;
        margin-bottom: 0.75rem;
    }
    .feature-desc {
        color: var(--text-muted);
        font-size: 0.95rem;
        line-height: 1.5;
        margin-bottom: 0;
    }
    .preview-box {
        background: #0f172a;
        border: 1px solid var(--border-color);
        border-radius: 24px;
        padding: 0.5rem;
        box-shadow: 0 30px 60px -20px rgba(0, 0, 0, 0.8);
        max-width: 900px;
        margin: 0 auto;
    }
    .preview-header {
        display: flex;
        align-items: center;
        padding: 0.75rem 1.25rem;
        border-bottom: 1px solid var(--border-color);
        background: rgba(255, 255, 255, 0.02);
        border-top-left-radius: 18px;
        border-top-right-radius: 18px;
    }
    .preview-dots {
        display: flex;
        gap: 6px;
    }
    .preview-dot {
        width: 12px;
        height: 12px;
        border-radius: 50%;
    }
    .preview-dot.red { background: #ef4444; }
    .preview-dot.yellow { background: #f59e0b; }
    .preview-dot.green { background: #10b981; }
    .preview-address {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid var(--border-color);
        padding: 0.25rem 2rem;
        border-radius: 6px;
        font-size: 0.8rem;
        color: var(--text-muted);
        margin: 0 auto;
        width: 250px;
        text-align: center;
    }
    .preview-body {
        padding: 1.5rem;
    }
    .preview-image {
        border-radius: 12px;
        width: 100%;
        display: block;
        opacity: 0.9;
        transition: opacity 0.3s;
    }
    .preview-image:hover {
        opacity: 1;
    }
    .stats-section {
        background: rgba(15, 23, 42, 0.6);
        border-top: 1px solid var(--border-color);
        border-bottom: 1px solid var(--border-color);
        padding: 4rem 0;
        margin: 5rem 0;
    }
    .stat-box {
        text-align: center;
    }
    .stat-number {
        font-size: 3rem;
        font-weight: 800;
        color: #f8fafc;
        margin-bottom: 0.5rem;
        background: linear-gradient(135deg, #a5b4fc, #6366f1);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    .stat-label {
        font-weight: 500;
        color: var(--text-muted);
        font-size: 1rem;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
</style>

<div class="container">
    <!-- Hero Section -->
    <div class="hero-section">
        <div class="hero-badge">
            <i class="fa-solid fa-shield-halved"></i> Learn Secure Coding by Example
        </div>
        <h1 class="hero-title">
            Master Secure Development on the<br><span>EduSecurity</span> LMS Platform
        </h1>
        <p class="hero-description">
            A premium learning management system built for cybersecurity education. Experiment with live vulnerability simulators, audit certificates, and submit secure code.
        </p>
        <div class="hero-buttons">
            <a href="{{ route('register') }}" class="btn btn-primary-custom btn-lg px-5 py-3">
                <i class="fa-solid fa-user-plus me-2"></i> Join as a Student
            </a>
            <a href="{{ route('courses_list') }}" class="btn btn-secondary-custom btn-lg px-5 py-3">
                <i class="fa-solid fa-book-open me-2"></i> Explore Courses
            </a>
        </div>

        <!-- Simulated Preview Window -->
        <div class="preview-box mt-5">
            <div class="preview-header">
                <div class="preview-dots">
                    <span class="preview-dot red"></span>
                    <span class="preview-dot yellow"></span>
                    <span class="preview-dot green"></span>
                </div>
                <div class="preview-address">www.secure-study.com</div>
            </div>
            <div class="preview-body">
                <div class="p-4 bg-dark text-start" style="border-radius: 12px; border: 1px solid var(--border-color);">
                    <h5 class="text-light fw-bold"><i class="fa-solid fa-bug-slash text-indigo me-2"></i>Live Vulnerability Auditing</h5>
                    <p class="text-muted small">Compare raw query string concatenation against Laravel Eloquent parameterized binding in real-time.</p>
                    <div class="p-3 bg-black text-danger font-monospace mb-2 small" style="border-radius: 6px; border: 1px solid rgba(239,68,68,0.2);">
                        // Vulnerable Concatenation query: <br>
                        SELECT * FROM users WHERE email = 'student@study.com' OR '1'='1'
                    </div>
                    <div class="p-3 bg-black text-success font-monospace small" style="border-radius: 6px; border: 1px solid rgba(16,185,129,0.2);">
                        // Secure Parameterized binding query:<br>
                        SELECT * FROM users WHERE email = ? AND password = ?
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Stats Section -->
<div class="stats-section">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="stat-box">
                    <div class="stat-number">10+</div>
                    <div class="stat-label">Cybersecurity Courses</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-box">
                    <div class="stat-number">15+</div>
                    <div class="stat-label">Interactive Labs</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-box">
                    <div class="stat-number">100%</div>
                    <div class="stat-label">Hands-on Sandbox</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Features Section -->
<div class="container mb-5 pb-5">
    <h2 class="text-center fw-bold mb-5 text-light">Built-in Security Features</h2>
    <div class="row g-4">
        <!-- Feature 1 -->
        <div class="col-md-4">
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fa-solid fa-fingerprint"></i>
                </div>
                <h4 class="feature-title text-light">Socialite Authentication</h4>
                <p class="feature-desc">
                    Secure passwordless onboarding using OAuth 2.0. Seamlessly sign in via Google, LinkedIn, Microsoft, or Facebook using secure token exchanges.
                </p>
            </div>
        </div>
        <!-- Feature 2 -->
        <div class="col-md-4">
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fa-solid fa-code"></i>
                </div>
                <h4 class="feature-title text-light">Mitigation Simulators</h4>
                <p class="feature-desc">
                    Interactively check code blocks for vulnerabilities. Includes robustness testing, SQL injection parsers, and HTML entity escape checks.
                </p>
            </div>
        </div>
        <!-- Feature 3 -->
        <div class="col-md-4">
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fa-solid fa-shield-halved"></i>
                </div>
                <h4 class="feature-title text-light">Role-Based Access</h4>
                <p class="feature-desc">
                    Granular permissions for Students, Instructors, and Course Admins. Full database auditing keeps systems isolated and protected.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
