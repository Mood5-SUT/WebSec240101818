@extends('layouts.master')

@section('title', 'Home')

@section('content')
<section class="hero-panel mb-5">
    <div class="row align-items-center g-4">
        <div class="col-lg-7">
            <span class="hero-kicker">Secure Role-Based Library Platform</span>
            <h1 class="hero-title mt-3">Manage books, members, and borrowing with a cleaner modern interface.</h1>
            <p class="hero-copy mt-3">
                This Library Management System combines secure authentication, role-based access, and real-time borrowing status
                for Admins, Librarians, and Members.
            </p>
            <div class="d-flex flex-wrap gap-3 mt-4">
                <a href="{{ route('books_list') }}" class="btn btn-light btn-lg px-4">Browse Catalogue</a>
                @guest
                    <a href="{{ route('users_register') }}" class="btn btn-outline-light btn-lg px-4">Create Member Account</a>
                @endguest
                @auth
                    <a href="{{ route('users_profile') }}" class="btn btn-outline-light btn-lg px-4">Open My Profile</a>
                @endauth
            </div>
        </div>
        <div class="col-lg-5">
            <div class="glass-card">
                <div class="row g-3">
                    <div class="col-6">
                        <div class="metric-card">
                            <span class="metric-label">Books</span>
                            <strong class="metric-value">{{ $booksCount }}</strong>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="metric-card">
                            <span class="metric-label">Available Copies</span>
                            <strong class="metric-value">{{ $availableCopies }}</strong>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="metric-card">
                            <span class="metric-label">Members</span>
                            <strong class="metric-value">{{ $membersCount }}</strong>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="metric-card">
                            <span class="metric-label">Active Borrows</span>
                            <strong class="metric-value">{{ $activeBorrows }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="mb-5">
    <div class="section-header mb-4">
        <div>
            <span class="section-kicker">Highlights</span>
            <h2 class="section-title">What this system supports</h2>
        </div>
    </div>
    <div class="row g-4">
        <div class="col-md-4">
            <div class="feature-card h-100">
                <h3>Secure Login</h3>
                <p>Users register and log in with protected authentication and a 3-attempt rate limit lock for 15 seconds.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="feature-card h-100">
                <h3>Role Control</h3>
                <p>Admins manage librarians, member passwords, roles, and permissions using Spatie access control.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="feature-card h-100">
                <h3>Borrow Workflow</h3>
                <p>Members borrow only when stock exists, while the system updates copies safely inside a transaction.</p>
            </div>
        </div>
    </div>
</section>

<section>
    <div class="section-header mb-4 d-flex justify-content-between align-items-end flex-wrap gap-3">
        <div>
            <span class="section-kicker">Newest Titles</span>
            <h2 class="section-title">Recently added books</h2>
        </div>
        <a href="{{ route('books_list') }}" class="btn btn-dark">View Full Catalogue</a>
    </div>
    <div class="row g-4">
        @forelse($featuredBooks as $book)
            <div class="col-md-4">
                <div class="catalog-card h-100">
                    <span class="catalog-pill">{{ $book->copies > 0 ? 'In Stock' : 'Unavailable' }}</span>
                    <h3>{{ $book->title }}</h3>
                    <p class="catalog-meta">By {{ $book->author }}</p>
                    <p class="catalog-isbn">ISBN: {{ $book->isbn }}</p>
                    <div class="catalog-footer">
                        <span>{{ $book->copies }} copies</span>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="empty-state">
                    <h3>No books added yet</h3>
                    <p>The catalogue is ready for Admins and Librarians to start building the library collection.</p>
                </div>
            </div>
        @endforelse
    </div>
</section>
@endsection
