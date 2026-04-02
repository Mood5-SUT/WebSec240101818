@extends('layouts.master')

@section('title', 'Books Catalogue')

@section('content')
<div class="section-header mb-4 d-flex justify-content-between align-items-end flex-wrap gap-3">
    <div>
        <span class="section-kicker">Catalogue</span>
        <h1 class="section-title mb-0">Library Books</h1>
    </div>
    <div class="d-flex gap-2 flex-wrap">
        @can('manage_books')
            <a href="{{ route('books_edit') }}" class="btn btn-dark">Add New Book</a>
        @endcan
        <a href="{{ route('home') }}" class="btn btn-outline-dark">Back To Home</a>
    </div>
</div>

<div class="row g-4 mb-4">
    @forelse($books as $book)
        <div class="col-md-6 col-xl-4">
            <div class="catalog-card h-100">
                <div class="d-flex justify-content-between align-items-start gap-3">
                    <div>
                        <span class="catalog-pill {{ $book->copies > 0 ? 'catalog-pill-success' : 'catalog-pill-danger' }}">
                            {{ $book->copies > 0 ? 'Available' : 'Unavailable' }}
                        </span>
                        <h3 class="mt-3">{{ $book->title }}</h3>
                        <p class="catalog-meta">By {{ $book->author }}</p>
                    </div>
                    <div class="catalog-count">{{ $book->copies }}</div>
                </div>
                <p class="catalog-isbn mt-3">ISBN: {{ $book->isbn }}</p>
                <div class="catalog-actions mt-4">
                    @can('manage_books')
                        <a href="{{ route('books_edit', $book->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('books_delete', $book->id) }}" method="POST" class="d-inline">
                            {{ csrf_field() }}
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this book?')">Delete</button>
                        </form>
                    @endcan

                    @auth
                        @role('Member')
                            <form action="{{ route('borrow_save', $book->id) }}" method="POST" class="d-inline">
                                {{ csrf_field() }}
                                <button type="submit" class="btn btn-sm btn-success" {{ $book->copies <= 0 ? 'disabled' : '' }}>Borrow</button>
                            </form>
                        @endrole
                    @endauth

                    @guest
                        <a href="{{ route('users_login') }}" class="btn btn-sm btn-outline-secondary">Login to Borrow</a>
                    @endguest
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="empty-state">
                <h3>No books available in the catalogue</h3>
                <p>Admins and Librarians can add the first book record from the management panel.</p>
            </div>
        </div>
    @endforelse
</div>

<div class="card shadow-sm border-0 app-surface">
    <div class="card-header bg-transparent border-0 pt-4 px-4">
        <h2 class="h4 mb-0">Table View</h2>
    </div>
    <div class="card-body pt-3">
        <div class="table-responsive">
            <table class="table align-middle modern-table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Author</th>
                        <th>ISBN</th>
                        <th>Copies</th>
                        <th width="220">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($books as $book)
                        <tr>
                            <td>{{ $book->title }}</td>
                            <td>{{ $book->author }}</td>
                            <td>{{ $book->isbn }}</td>
                            <td>
                                @if($book->copies > 0)
                                    <span class="badge bg-success">{{ $book->copies }} Available</span>
                                @else
                                    <span class="badge bg-danger">Unavailable</span>
                                @endif
                            </td>
                            <td>
                                @can('manage_books')
                                    <a href="{{ route('books_edit', $book->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                    <form action="{{ route('books_delete', $book->id) }}" method="POST" class="d-inline">
                                        {{ csrf_field() }}
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this book?')">Delete</button>
                                    </form>
                                @endcan

                                @auth
                                    @role('Member')
                                        <form action="{{ route('borrow_save', $book->id) }}" method="POST" class="d-inline">
                                            {{ csrf_field() }}
                                            <button type="submit" class="btn btn-sm btn-success" {{ $book->copies <= 0 ? 'disabled' : '' }}>Borrow</button>
                                        </form>
                                    @endrole
                                @endauth

                                @guest
                                    <a href="{{ route('users_login') }}" class="btn btn-sm btn-outline-secondary">Login to Borrow</a>
                                @endguest
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">No books available in the catalogue.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
