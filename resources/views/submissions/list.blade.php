@extends('layouts.master')

@section('title', 'Submissions')

@section('content')
<div class="card card-custom">
    <div class="card-header card-header-custom d-flex justify-content-between align-items-center">
        <div>
            <h3 class="mb-0 fw-bold"><i class="fa-solid fa-file-invoice text-indigo me-2"></i>Submissions Registry</h3>
            <p class="text-muted small mb-0 mt-1">
                @role('student')
                    Track and review your submitted course materials.
                @else
                    View, grade, and resolve review requests for student submissions.
                @endrole
            </p>
        </div>
        <span class="badge badge-student p-2">
            @role('student') Student View @else Instructor View @endrole
        </span>
    </div>
    <div class="card-body p-4">
        
        <div class="table-responsive">
            <table class="table table-dark table-hover border-secondary align-middle">
                <thead>
                    <tr class="text-muted">
                        <th>Course / Assignment</th>
                        @role('student')
                            <!-- Students don't need to see who they are -->
                        @else
                            <th>Student</th>
                        @endrole
                        <th>Submission Content</th>
                        <th>Grade</th>
                        <th>Review Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($submissions as $sub)
                        <tr>
                            <td>
                                <a href="{{ route('courses_show', ['id' => $sub->assignment->course->id]) }}" class="text-indigo text-decoration-none fw-semibold">
                                    {{ $sub->assignment->course->title }}
                                </a>
                                <div class="text-muted small">{{ $sub->assignment->title }}</div>
                            </td>
                            
                            @role('student')
                                <!-- Empty -->
                            @else
                                <td>
                                    <strong>{{ $sub->user->name }}</strong>
                                    <div class="text-muted small"><code>{{ $sub->user->email }}</code></div>
                                </td>
                            @endrole

                            <td>
                                @if(strlen($sub->submitted_text) <= 120)
                                    <div class="p-2 bg-dark border border-secondary text-light font-monospace small mb-1" style="border-radius: 8px; max-width: 320px; white-space: pre-wrap;">{{ $sub->submitted_text }}</div>
                                @else
                                    <div class="p-2 bg-dark border border-secondary text-light font-monospace small mb-1" style="border-radius: 8px; max-width: 320px; white-space: pre-wrap;">{{ Str::limit($sub->submitted_text, 120, '...') }}</div>
                                    <details class="text-light small mt-1" style="max-width: 320px;">
                                        <summary class="text-info cursor-pointer text-decoration-none small" style="outline: none; cursor: pointer;">
                                            <i class="fa-solid fa-chevron-down me-1"></i>View Full Work
                                        </summary>
                                        <div class="mt-2 p-2 bg-dark border border-secondary font-monospace" style="border-radius: 8px; white-space: pre-wrap;">{{ $sub->submitted_text }}</div>
                                    </details>
                                @endif

                                @if($sub->file_path)
                                    <div class="mt-2">
                                        <a href="{{ asset('storage/' . $sub->file_path) }}" class="btn btn-sm btn-outline-info text-decoration-none py-1 px-2" style="font-size: 0.8rem;" download>
                                            <i class="fa-solid fa-file-arrow-down me-1"></i> Download File
                                        </a>
                                    </div>
                                @endif
                            </td>

                            <td>
                                @if($sub->grade)
                                    <span class="badge bg-success px-3 py-2 fs-6">Grade: {{ $sub->grade }}</span>
                                @else
                                    <span class="badge bg-secondary px-3 py-2">Ungraded</span>
                                @endif
                            </td>

                            <td>
                                @if($sub->grade_review_status == 'none')
                                    <span class="badge bg-secondary">None</span>
                                @elseif($sub->grade_review_status == 'requested')
                                    <span class="badge badge-admin p-2 animate-pulse"><i class="fa-solid fa-circle-exclamation me-1"></i> Review Requested</span>
                                @elseif($sub->grade_review_status == 'completed')
                                    <span class="badge badge-instructor p-2"><i class="fa-solid fa-circle-check me-1"></i> Review Completed</span>
                                @endif
                            </td>

                            <td>
                                @role('student')
                                    @if($sub->grade)
                                        @if($sub->grade_review_status == 'none')
                                            <form action="{{ route('submissions_request_review') }}" method="POST" class="d-inline">
                                                {{ csrf_field() }}
                                                <input type="hidden" name="submission_id" value="{{ $sub->id }}">
                                                <button type="submit" class="btn btn-warning btn-sm fw-semibold">
                                                    <i class="fa-solid fa-redo me-1"></i> Request Review
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-muted small">No actions</span>
                                        @endif
                                    @else
                                        <span class="text-muted small">Awaiting grading</span>
                                    @endif
                                @else
                                    <!-- Instructor grading form -->
                                    <form action="{{ route('submissions_grade') }}" method="POST" class="d-flex gap-1 align-items-center">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="submission_id" value="{{ $sub->id }}">
                                        <input type="text" name="grade" class="form-control form-control-sm text-light bg-dark border-secondary" placeholder="Grade (A, B...)" style="width: 100px;" value="{{ $sub->grade }}" required>
                                        <button type="submit" class="btn btn-primary-custom btn-sm">
                                            @if($sub->grade_review_status == 'requested')
                                                Resolve
                                            @else
                                                Grade
                                            @endif
                                        </button>
                                    </form>
                                @endrole
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">
                                <i class="fa-regular fa-folder-open fs-3 d-block mb-2"></i>
                                No submissions found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>
@endsection
