@extends('layouts.master')

@section('title', $course->title)

@section('content')
<div class="row g-4">
    <!-- Course details sidebar -->
    <div class="col-lg-4">
        <div class="card card-custom mb-4">
            <div class="card-body p-4">
                <span class="badge badge-student mb-3"><i class="fa-solid fa-graduation-cap me-1"></i> Course Syllabus</span>
                <h2 class="fw-bold text-light mb-3">{{ $course->title }}</h2>
                <hr class="border-secondary">
                <div class="mb-3 small">
                    <span class="text-muted d-block">Instructor:</span>
                    <strong class="text-light fs-5"><i class="fa-solid fa-user-tie text-indigo me-1"></i> {{ $course->instructor->name ?? 'Guest Instructor' }}</strong>
                </div>
                <div class="mb-4 small">
                    <span class="text-muted d-block">Contact Email:</span>
                    <code>{{ $course->instructor->email ?? '' }}</code>
                </div>

                @auth
                    @if(auth()->user()->hasRole('student'))
                        @if($isEnrolled)
                            <div class="alert alert-success border-0 text-center py-2" style="background-color: rgba(16, 185, 129, 0.15); color: #a7f3d0; border-radius: 8px;">
                                <i class="fa-solid fa-circle-check me-1"></i> You are enrolled in this course!
                            </div>
                        @else
                            <form action="{{ route('courses_enroll') }}" method="POST">
                                {{ csrf_field() }}
                                <input type="hidden" name="course_id" value="{{ $course->id }}">
                                <button type="submit" class="btn btn-primary-custom w-100 py-2.5">
                                    <i class="fa-solid fa-user-plus me-1"></i> Enroll in Course
                                </button>
                            </form>
                        @endif
                    @endif

                    @can('upload_content')
                        @if(auth()->user()->hasRole('course_admin') || $course->instructor_id == auth()->id())
                            <div class="mt-2 d-flex flex-column gap-2">
                                <a href="{{ route('assignments_edit', ['course_id' => $course->id]) }}" class="btn btn-primary-custom w-100 py-2.5">
                                    <i class="fa-solid fa-plus-circle me-1"></i> Add Assignment
                                </a>
                                <a href="{{ route('courses_edit_materials', ['course_id' => $course->id]) }}" class="btn btn-primary-custom w-100 py-2.5">
                                    <i class="fa-solid fa-file-arrow-up me-1"></i> Add Materials
                                </a>
                                <a href="{{ route('courses_edit', ['id' => $course->id]) }}" class="btn btn-secondary-custom w-100 py-2.5">
                                    <i class="fa-solid fa-pen-to-square me-1"></i> Edit Syllabus
                                </a>
                            </div>
                        @endif
                    @endcan
                @else
                    <div class="alert alert-info border-0 text-center py-2 small" style="background-color: rgba(59, 130, 246, 0.15); color: #bfdbfe; border-radius: 8px;">
                        <a href="{{ route('login') }}" class="text-indigo text-decoration-none fw-semibold">Login</a> to enroll and submit assignments.
                    </div>
                @endauth
            </div>
        </div>
    </div>

    <!-- Course Syllabus / Description -->
    <div class="col-lg-8">
        <div class="card card-custom mb-4">
            <div class="card-header card-header-custom">
                <h4 class="mb-0 fw-bold"><i class="fa-solid fa-book text-indigo me-2"></i>Course Details</h4>
            </div>
            <div class="card-body p-4">
                <p class="fs-5 text-light" style="white-space: pre-line; line-height: 1.6; margin-bottom: 0;">
                    {{ $course->description }}
                </p>
                @if($course->material_path)
                    <div class="mt-4 pt-4 border-top border-secondary animate-fade-in">
                        <h5 class="text-light fw-semibold mb-2"><i class="fa-solid fa-folder-open text-indigo me-2"></i>Course Syllabus & Materials</h5>
                        <div class="d-flex align-items-center gap-2">
                            <a href="{{ asset('storage/' . $course->material_path) }}" class="btn btn-outline-info text-decoration-none" download>
                                <i class="fa-solid fa-file-arrow-down me-1"></i> Download Reference Materials
                            </a>
                            @can('upload_content')
                                @if(auth()->user()->hasRole('course_admin') || $course->instructor_id == auth()->id())
                                    <form action="{{ route('courses_delete_materials', ['id' => $course->id]) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this course material?')">
                                        {{ csrf_field() }}
                                        <button type="submit" class="btn btn-outline-danger">
                                            <i class="fa-solid fa-trash-can me-1"></i> Delete Material
                                        </button>
                                    </form>
                                @endif
                            @endcan
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Assignments list Section -->
        <h3 class="fw-bold text-light mt-5 mb-4"><i class="fa-solid fa-clipboard-list text-indigo me-2"></i>Assignments</h3>

        @forelse($course->assignments as $assignment)
            <div class="card card-custom mb-4 border-start border-primary border-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold text-light mb-2">{{ $assignment->title }}</h5>
                    <p class="text-muted small mb-2">{{ $assignment->description }}</p>
                    @if($assignment->file_path)
                        <div class="mb-3">
                            <a href="{{ asset('storage/' . $assignment->file_path) }}" class="btn btn-sm btn-outline-info text-decoration-none" download>
                                <i class="fa-solid fa-file-arrow-down me-1"></i> Download Reference Material
                            </a>
                        </div>
                    @endif

                    @auth
                        @if(auth()->user()->hasRole('student'))
                            @if($isEnrolled)
                                @php
                                    $submission = $assignment->submissions->first();
                                @endphp

                                @if($submission)
                                    <!-- Student has already submitted -->
                                    <div class="p-3 bg-dark border border-secondary mb-3" style="border-radius: 12px;">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="text-muted small">Your Submission:</span>
                                            <div>
                                                @if($submission->grade)
                                                    <span class="badge bg-success py-1.5 px-3 fs-6">Grade: {{ $submission->grade }}</span>
                                                @else
                                                    <span class="badge bg-secondary py-1.5 px-3">Pending Grading</span>
                                                @endif
                                            </div>
                                        </div>
                                        <p class="text-light small mb-0 font-monospace" style="white-space: pre-wrap;">{{ $submission->submitted_text }}</p>
                                        @if($submission->file_path)
                                            <div class="mt-3 pt-3 border-top border-secondary">
                                                <a href="{{ asset('storage/' . $submission->file_path) }}" class="btn btn-sm btn-outline-info text-decoration-none" download>
                                                    <i class="fa-solid fa-file-arrow-down me-1"></i> Download Submitted File
                                                </a>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="text-muted small">
                                            Submitted on {{ $submission->created_at->format('M d, Y H:i') }}
                                        </span>
                                        
                                        <!-- Grade Review Panel -->
                                        @if($submission->grade)
                                            @if($submission->grade_review_status == 'none')
                                                <form action="{{ route('submissions_request_review') }}" method="POST" class="d-inline">
                                                    {{ csrf_field() }}
                                                    <input type="hidden" name="submission_id" value="{{ $submission->id }}">
                                                    <button type="submit" class="btn btn-warning btn-sm fw-semibold">
                                                        <i class="fa-solid fa-redo me-1"></i> Request Grade Review
                                                    </button>
                                                </form>
                                            @elseif($submission->grade_review_status == 'requested')
                                                <span class="badge badge-admin py-1.5 px-3"><i class="fa-solid fa-hourglass-half me-1"></i> Review Requested</span>
                                            @elseif($submission->grade_review_status == 'completed')
                                                <span class="badge badge-instructor py-1.5 px-3"><i class="fa-solid fa-circle-check me-1"></i> Review Completed</span>
                                            @endif
                                        @endif
                                    </div>
                                @else
                                    <!-- Student has NOT submitted yet -->
                                    <form action="{{ route('submissions_submit') }}" method="POST" enctype="multipart/form-data" class="mt-3">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="assignment_id" value="{{ $assignment->id }}">
                                        <div class="mb-3">
                                            <label for="submitted_text_{{ $assignment->id }}" class="form-label small">Type your submission answer below:</label>
                                            <textarea class="form-control text-light bg-dark border-secondary" id="submitted_text_{{ $assignment->id }}" name="submitted_text" rows="4" placeholder="Write or paste your submission answer here..." required></textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label for="submission_file_{{ $assignment->id }}" class="form-label small">Upload File Submission (Optional, Max 10MB)</label>
                                            <input type="file" class="form-control text-light bg-dark border-secondary" id="submission_file_{{ $assignment->id }}" name="submission_file">
                                        </div>
                                        <button type="submit" class="btn btn-primary-custom btn-sm px-4">
                                            <i class="fa-solid fa-paper-plane me-1"></i> Submit Work
                                        </button>
                                    </form>
                                @endif
                            @else
                                <div class="text-muted small italic">
                                    <i class="fa-solid fa-lock me-1"></i> Enroll in this course to view and submit this assignment.
                                </div>
                            @endif
                        @else
                            <!-- Instructor/Admin views -->
                            <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top border-secondary">
                                <span class="text-muted small">
                                    <i class="fa-solid fa-users me-1"></i> Submissions: <strong>{{ $assignment->submissions->count() }}</strong> total
                                </span>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('assignments_edit', ['course_id' => $course->id, 'id' => $assignment->id]) }}" class="btn btn-outline-warning btn-sm px-3">
                                        <i class="fa-solid fa-pen-to-square me-1"></i> Edit Assignment
                                    </a>
                                    <a href="{{ route('submissions_list', ['course_id' => $course->id]) }}" class="btn btn-secondary-custom btn-sm px-3">
                                        <i class="fa-solid fa-grading me-1"></i> View & Grade
                                    </a>
                                </div>
                            </div>
                        @endif
                    @endauth
                </div>
            </div>
        @empty
            <div class="text-center py-4 bg-dark border border-secondary" style="border-radius: 12px;">
                <i class="fa-solid fa-list-check fs-2 text-muted mb-2"></i>
                <p class="text-muted small mb-0">No assignments posted for this course yet.</p>
            </div>
        @endforelse
    </div>
</div>


@endsection
