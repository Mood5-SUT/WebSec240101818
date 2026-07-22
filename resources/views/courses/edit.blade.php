@extends('layouts.master')

@section('title', $course ? 'Edit Course' : 'Create Course')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card card-custom">
            <div class="card-header card-header-custom">
                <h3 class="mb-0 fw-bold"><i class="fa-solid fa-graduation-cap text-indigo me-2"></i>{{ $course ? 'Edit Course' : 'Create Course' }}</h3>
                <p class="text-muted small mb-0 mt-1">Provide details for the educational course curriculum.</p>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('courses_save') }}" method="POST" enctype="multipart/form-data">
                    {{ csrf_field() }}

                    @if($course)
                        <input type="hidden" name="course_id" value="{{ $course->id }}">
                    @endif

                    <div class="mb-3">
                        <label for="title" class="form-label">Course Title</label>
                        <input type="text" class="form-control text-light bg-dark border-secondary" id="title" name="title" value="{{ old('title', $course->title ?? '') }}" placeholder="e.g. Advanced Cryptography" required>
                    </div>

                    @role('course_admin')
                        <div class="mb-3">
                            <label for="instructor_id" class="form-label">Assign Instructor</label>
                            <select name="instructor_id" id="instructor_id" class="form-select text-light bg-dark border-secondary" required>
                                <option value="" disabled {{ !$course ? 'selected' : '' }}>Select Instructor...</option>
                                @foreach($instructors as $inst)
                                    <option value="{{ $inst->id }}" {{ (old('instructor_id', $course->instructor_id ?? '') == $inst->id) ? 'selected' : '' }}>
                                        {{ $inst->name }} ({{ $inst->email }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endrole

                    <div class="mb-3">
                        <label for="description" class="form-label">Course Description</label>
                        <textarea class="form-control text-light bg-dark border-secondary" id="description" name="description" rows="6" placeholder="Provide syllabus, prerequisites, or general course descriptions..." required>{{ old('description', $course->description ?? '') }}</textarea>
                    </div>

                    <div class="mb-4">
                        <label for="course_material" class="form-label">Course Material / Syllabus Document (Optional, Max 10MB)</label>
                        <input type="file" class="form-control text-light bg-dark border-secondary" id="course_material" name="course_material">
                        @if($course && $course->material_path)
                            <div class="form-text text-muted mt-2">
                                <i class="fa-solid fa-file-lines text-indigo me-1"></i> Current document: <a href="{{ asset('storage/' . $course->material_path) }}" class="text-indigo text-decoration-none fw-semibold" download>Download current material</a>
                            </div>
                        @endif
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('courses_list') }}" class="btn btn-secondary-custom px-4">Cancel</a>
                        <button type="submit" class="btn btn-primary-custom px-4">
                            <i class="fa-solid fa-save me-1"></i> {{ $course ? 'Update Course' : 'Create Course' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
