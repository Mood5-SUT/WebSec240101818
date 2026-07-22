@extends('layouts.master')

@section('title', 'Available Courses')

@section('content')
<div class="row mb-5 align-items-center">
    <div class="col-md-7">
        <h1 class="fw-bold mb-1"><i class="fa-solid fa-graduation-cap text-indigo me-2"></i>Educational Courses</h1>
        <p class="text-muted mb-0">Browse through available cybersecurity and technology courses.</p>
    </div>
    
    <!-- Keyword Search form -->
    <div class="col-md-5">
        <form action="{{ route('courses_list') }}" method="GET" class="d-flex gap-2">
            <input type="text" name="keywords" class="form-control text-light bg-dark border-secondary" placeholder="Search by title or topic..." value="{{ request('keywords') }}">
            <button type="submit" class="btn btn-primary-custom px-4">
                <i class="fa-solid fa-magnifying-glass"></i>
            </button>
            @if(request('keywords'))
                <a href="{{ route('courses_list') }}" class="btn btn-secondary-custom">Clear</a>
            @endif
        </form>
    </div>
</div>

<div class="row g-4">
    @forelse($courses as $course)
        <div class="col-md-4">
            <div class="card card-custom h-100 d-flex flex-column">
                <div class="card-body p-4 d-flex flex-column">
                    <span class="badge badge-student mb-3 align-self-start">
                        <i class="fa-solid fa-user-tie me-1"></i> {{ $course->instructor->name ?? 'Guest Instructor' }}
                    </span>
                    <h4 class="card-title fw-bold text-light mb-2">{{ $course->title }}</h4>
                    <p class="card-text text-muted flex-grow-1 small">
                        {{ Str::limit($course->description, 140, '...') }}
                    </p>
                    <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top border-secondary">
                        <a href="{{ route('courses_show', ['id' => $course->id]) }}" class="btn btn-primary-custom btn-sm px-4">
                            View Course <i class="fa-solid fa-angle-right ms-1"></i>
                        </a>
                        
                        @can('upload_content')
                            @if(auth()->user()->hasRole('course_admin') || $course->instructor_id == auth()->id())
                                <div class="btn-group">
                                    <a href="{{ route('courses_edit', ['id' => $course->id]) }}" class="btn btn-outline-warning btn-sm border-0"><i class="fa-solid fa-pen-to-square"></i></a>
                                    
                                    <form action="{{ route('courses_delete', ['id' => $course->id]) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this course?')">
                                        {{ csrf_field() }}
                                        <button type="submit" class="btn btn-outline-danger btn-sm border-0"><i class="fa-solid fa-trash-can"></i></button>
                                    </form>
                                </div>
                            @endif
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="text-center py-5 bg-dark border border-secondary" style="border-radius: 16px;">
                <i class="fa-regular fa-folder-open fs-1 text-muted mb-3"></i>
                <h4 class="text-light fw-semibold">No Courses Found</h4>
                <p class="text-muted small">We couldn't find any courses matching your search criteria.</p>
                <a href="{{ route('courses_list') }}" class="btn btn-primary-custom mt-2">Clear Search</a>
            </div>
        </div>
    @endforelse
</div>
@endsection
