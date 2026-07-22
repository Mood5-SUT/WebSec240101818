@extends('layouts.master')

@section('title', $assignment ? 'Edit Assignment' : 'Add Assignment')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card card-custom">
            <div class="card-header card-header-custom">
                <h3 class="mb-0 fw-bold">
                    <i class="fa-solid fa-clipboard-question text-indigo me-2"></i>
                    {{ $assignment ? 'Edit Assignment' : 'Add Assignment' }}
                </h3>
                <p class="text-muted small mb-0 mt-1">Course: <strong class="text-light">{{ $course->title }}</strong></p>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('assignments_save') }}" method="POST" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <input type="hidden" name="course_id" value="{{ $course->id }}">
                    
                    @if($assignment)
                        <input type="hidden" name="assignment_id" value="{{ $assignment->id }}">
                    @endif

                    <div class="mb-3">
                        <label for="title" class="form-label">Assignment Title</label>
                        <input type="text" class="form-control text-light bg-dark border-secondary" id="title" name="title" value="{{ old('title', $assignment->title ?? '') }}" placeholder="e.g. Assignment 1: SQL Injection Lab" required>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Assignment Prompt / Description</label>
                        <textarea class="form-control text-light bg-dark border-secondary" id="description" name="description" rows="6" placeholder="Define submission questions or requirements..." required>{{ old('description', $assignment->description ?? '') }}</textarea>
                    </div>

                    <div class="mb-4">
                        <label for="assignment_file" class="form-label">Upload Reference File (Optional)</label>
                        <input type="file" class="form-control text-light bg-dark border-secondary" id="assignment_file" name="assignment_file">
                        @if($assignment && $assignment->file_path)
                            <div class="mt-2 small">
                                <span class="text-muted">Current file:</span> 
                                <a href="{{ asset('storage/' . $assignment->file_path) }}" class="text-info text-decoration-none" download>
                                    <i class="fa-solid fa-download me-1"></i> Download Reference File
                                </a>
                            </div>
                        @endif
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('courses_show', ['id' => $course->id]) }}" class="btn btn-secondary-custom px-4">Cancel</a>
                        <button type="submit" class="btn btn-primary-custom px-4">
                            <i class="fa-solid fa-save me-1"></i> {{ $assignment ? 'Save Changes' : 'Create Assignment' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
