<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Submission;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AssignmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:web');
    }

    // Return dedicated edit/add assignment view
    public function edit($course_id, $id = null)
    {
        if (!auth()->user()->hasPermissionTo('upload_content')) {
            abort(403, 'Unauthorized action. You need upload_content permission.');
        }

        $course = \App\Models\Course::findOrFail($course_id);
        $assignment = $id ? Assignment::findOrFail($id) : null;

        // Check ownership (instructors can only edit assignments of courses they teach, admin can edit all)
        if (!auth()->user()->hasRole('course_admin') && $course->instructor_id !== auth()->id()) {
            abort(403, 'Unauthorized action. You do not own this course.');
        }

        return view('assignments.edit', compact('course', 'assignment'));
    }

    // Save assignment (create/edit by instructor or admin)
    public function save(Request $request)
    {
        if (!auth()->user()->hasPermissionTo('upload_content')) {
            abort(403, 'Unauthorized action. You need upload_content permission.');
        }

        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'assignment_id' => 'nullable|exists:assignments,id',
            'assignment_file' => 'nullable|file|max:10240' // Max 10MB
        ]);

        $course = \App\Models\Course::findOrFail($request->course_id);

        // Check ownership (instructors can only save assignments of courses they teach, admin can save all)
        if (!auth()->user()->hasRole('course_admin') && $course->instructor_id !== auth()->id()) {
            abort(403, 'Unauthorized action. You do not own this course.');
        }

        $assignment = null;
        if ($request->assignment_id) {
            $assignment = Assignment::findOrFail($request->assignment_id);
            // Verify that this assignment belongs to this course as well to prevent tampering
            if ($assignment->course_id !== $course->id) {
                abort(400, 'Invalid assignment course binding.');
            }
        }

        $filePath = $assignment ? $assignment->file_path : null;
        if ($request->hasFile('assignment_file')) {
            $filePath = $request->file('assignment_file')->store('assignments', 'public');
        }

        if ($request->assignment_id) {
            $assignment->update([
                'title' => $request->title,
                'description' => $request->description,
                'file_path' => $filePath
            ]);
            $msg = 'Assignment updated successfully!';
        } else {
            $assignment = Assignment::create([
                'course_id' => $request->course_id,
                'title' => $request->title,
                'description' => $request->description,
                'file_path' => $filePath
            ]);
            $msg = 'Assignment created successfully!';
        }

        return redirect()->route('courses_show', ['id' => $request->course_id])->with('success', $msg);
    }

    // Submit assignment (students only)
    public function submit(Request $request)
    {
        if (!auth()->user()->hasPermissionTo('submit_assignment')) {
            abort(403, 'Unauthorized action. You need submit_assignment permission.');
        }

        $request->validate([
            'assignment_id' => 'required|exists:assignments,id',
            'submitted_text' => 'required|string',
            'submission_file' => 'nullable|file|max:10240' // Max 10MB
        ]);

        $assignment = Assignment::findOrFail($request->assignment_id);

        // Verify the student is actually enrolled in this course
        $enrolled = Enrollment::where('user_id', auth()->id())
            ->where('course_id', $assignment->course_id)
            ->exists();

        if (!$enrolled) {
            abort(403, 'You must be enrolled in this course to submit assignments.');
        }

        // Find existing submission to preserve old file if no new file uploaded
        $existing = Submission::where('assignment_id', $assignment->id)
            ->where('user_id', auth()->id())
            ->first();

        $filePath = $existing ? $existing->file_path : null;
        if ($request->hasFile('submission_file')) {
            $filePath = $request->file('submission_file')->store('submissions', 'public');
        }

        // Create or update submission
        $submission = Submission::updateOrCreate(
            [
                'assignment_id' => $assignment->id,
                'user_id' => auth()->id(),
            ],
            [
                'submitted_text' => $request->submitted_text,
                'file_path' => $filePath,
                'grade_review_status' => 'none' // Reset review status upon resubmission
            ]
        );

        return redirect()->route('courses_show', ['id' => $assignment->course_id])
            ->with('success', 'Assignment submitted successfully!');
    }

    // List submissions (Scoped: Students see their own; Instructors/Admins see all for their courses)
    public function list(Request $request)
    {
        $user = auth()->user();

        if ($user->hasRole('student')) {
            // Student sees only their own submissions
            $submissions = Submission::where('user_id', $user->id)
                ->with(['assignment.course', 'user'])
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            // Instructor or Course Admin can see student submissions
            // For instructors, let's filter by courses they teach.
            $query = Submission::with(['assignment.course', 'user']);
            
            if ($user->hasRole('instructor')) {
                $query->whereHas('assignment.course', function($q) use ($user) {
                    $q->where('instructor_id', $user->id);
                });
            }

            // Instructors/Admins can filter by specific course if provided
            if ($request->course_id) {
                $query->whereHas('assignment', function($q) use ($request) {
                    $q->where('course_id', $request->course_id);
                });
            }

            $submissions = $query->orderBy('created_at', 'desc')->get();
        }

        return view('submissions.list', compact('submissions'));
    }

    // Request Grade Review (students only)
    public function requestReview(Request $request)
    {
        $request->validate([
            'submission_id' => 'required|exists:submissions,id'
        ]);

        $submission = Submission::findOrFail($request->submission_id);

        // Student can only request review for their own submission
        if ($submission->user_id !== auth()->id()) {
            abort(403, 'You can only request reviews for your own submissions.');
        }

        // Set status to requested
        $submission->grade_review_status = 'requested';
        $submission->save();

        return redirect()->back()->with('success', 'Grade review request submitted!');
    }

    // Grade submission / Complete Grade Review (Instructors & Admins only)
    public function grade(Request $request)
    {
        $request->validate([
            'submission_id' => 'required|exists:submissions,id',
            'grade' => 'required|string|max:255',
        ]);

        $submission = Submission::findOrFail($request->submission_id);
        $course = $submission->assignment->course;

        // Check permission (Admins or Course instructor)
        if (!auth()->user()->hasRole('course_admin') && $course->instructor_id !== auth()->id()) {
            abort(403, 'Unauthorized. You can only grade submissions for your own courses.');
        }

        // Save grade
        $submission->grade = $request->grade;

        // If a review was requested, mark it completed.
        // Actually, the requirement says: "If an instructor reviews the request, the grade review request status changes to 'completed'."
        if ($submission->grade_review_status === 'requested') {
            $submission->grade_review_status = 'completed';
        } else {
            // Also transition to completed or keep as none if never requested
            $submission->grade_review_status = 'completed';
        }

        $submission->save();

        return redirect()->back()->with('success', 'Submission graded and marked as completed!');
    }
}
