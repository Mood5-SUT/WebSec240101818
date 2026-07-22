@extends('layouts.master')

@section('title', 'Upload Course Materials')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card card-custom">
            <div class="card-header card-header-custom d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-0 fw-bold"><i class="fa-solid fa-file-arrow-up text-indigo me-2"></i>Upload Course Materials</h3>
                    <p class="text-muted small mb-0 mt-1">Upload reference notes, slides, or syllabus for <strong>{{ $course->title }}</strong>.</p>
                </div>
                <a href="{{ route('courses_show', ['id' => $course->id]) }}" class="btn btn-secondary-custom btn-sm">
                    <i class="fa-solid fa-arrow-left me-1"></i> Back to Course
                </a>
            </div>
            <div class="card-body p-4">
                @if($course->material_path)
                    <!-- Current Material status -->
                    <div class="p-3 mb-4 bg-dark-panel rounded" style="border-radius: 12px;">
                        <h6 class="text-indigo fw-bold mb-2"><i class="fa-solid fa-file-lines me-1"></i> Currently Uploaded Document:</h6>
                        <div class="d-flex justify-content-between align-items-center bg-black p-3 rounded" style="border-radius: 8px; border: 1px solid var(--border-color);">
                            <span class="text-light small font-monospace"><i class="fa-solid fa-file-pdf text-danger me-2"></i>{{ basename($course->material_path) }}</span>
                            <div class="d-flex gap-2">
                                <a href="{{ asset('storage/' . $course->material_path) }}" class="btn btn-sm btn-outline-info" download>
                                    <i class="fa-solid fa-download me-1"></i> Download
                                </a>
                                <form action="{{ route('courses_delete_materials', ['id' => $course->id]) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this course material?')">
                                    {{ csrf_field() }}
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="fa-solid fa-trash-can me-1"></i> Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Upload form -->
                <form action="{{ route('courses_save_materials') }}" method="POST" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <input type="hidden" name="course_id" value="{{ $course->id }}">

                    <div class="mb-4">
                        <label for="course_material" class="form-label fw-semibold">Select Materials / Syllabus File (Max 10MB)</label>
                        <input type="file" class="form-control text-light bg-dark border-secondary" id="course_material" name="course_material" required>
                        <div class="form-text text-muted mt-2">
                            Supports files like PDFs, slides, documentation files, or ZIP archives containing reference codes.
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('courses_show', ['id' => $course->id]) }}" class="btn btn-secondary-custom px-4">Cancel</a>
                        <button type="submit" class="btn btn-primary-custom px-4">
                            <i class="fa-solid fa-cloud-arrow-up me-1"></i> Upload Document
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
