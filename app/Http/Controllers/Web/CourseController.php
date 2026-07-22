<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:web')->except(['list', 'show', 'index']);
    }

    public function index()
    {
        if (Auth::check()) {
            return redirect()->route('courses_list');
        }
        return view('home');
    }

    public function list(Request $request)
    {
        // Use the dynamic filter with when() as per Chapter 1 conventions
        $query = Course::select("*")->with('instructor');
        
        $query->when($request->keywords, function($q) use ($request) {
            return $q->where("title", "like", "%" . $request->keywords . "%")
                     ->orWhere("description", "like", "%" . $request->keywords . "%");
        });

        $courses = $query->orderBy('created_at', 'desc')->get();

        return view('courses.list', compact('courses'));
    }

    public function show($id)
    {
        $course = Course::with(['instructor', 'assignments.submissions' => function($q) {
            // Students can only see their own submissions
            if (Auth::check() && Auth::user()->hasRole('student')) {
                $q->where('user_id', Auth::id());
            }
        }])->findOrFail($id);

        $isEnrolled = false;
        if (Auth::check()) {
            $isEnrolled = Enrollment::where('user_id', Auth::id())
                ->where('course_id', $course->id)
                ->exists();
        }

        return view('courses.show', compact('course', 'isEnrolled'));
    }

    public function edit($id = null)
    {
        if ($id === null) {
            if (!auth()->user()->hasPermissionTo('create_course')) {
                abort(403, 'Unauthorized action. You do not have permission to create courses.');
            }
        } else {
            if (!auth()->user()->hasPermissionTo('upload_content')) {
                abort(403, 'Unauthorized action. You do not have upload_content permission.');
            }
            $course = Course::findOrFail($id);
            if (!auth()->user()->hasRole('course_admin') && $course->instructor_id !== auth()->id()) {
                abort(403, 'You do not own this course.');
            }
        }

        $course = $id ? Course::findOrFail($id) : null;
        $instructors = \App\Models\User::role(['instructor', 'course_admin'])->get();
        return view('courses.edit', compact('course', 'instructors'));
    }

    public function save(Request $request)
    {
        if ($request->course_id) {
            if (!auth()->user()->hasPermissionTo('upload_content')) {
                abort(403, 'Unauthorized action. You do not have upload_content permission.');
            }
        } else {
            if (!auth()->user()->hasPermissionTo('create_course')) {
                abort(403, 'Unauthorized action. You do not have permission to create courses.');
            }
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'course_id' => 'nullable|exists:courses,id',
            'instructor_id' => 'nullable|exists:users,id',
            'course_material' => 'nullable|file|max:10240' // Max 10MB
        ]);

        $instructorId = auth()->id();
        if (auth()->user()->hasRole('course_admin') && $request->instructor_id) {
            $instructorId = $request->instructor_id;
        }

        $course = null;
        if ($request->course_id) {
            $course = Course::findOrFail($request->course_id);
            // Check ownership (instructors can only edit their own courses, admin can edit all)
            if (!auth()->user()->hasRole('course_admin') && $course->instructor_id !== auth()->id()) {
                abort(403, 'You do not own this course.');
            }
        }

        $materialPath = $course ? $course->material_path : null;
        if ($request->hasFile('course_material')) {
            $materialPath = $request->file('course_material')->store('materials', 'public');
        }

        if ($request->course_id) {
            $data = $request->only('title', 'description');
            if (auth()->user()->hasRole('course_admin') && $request->instructor_id) {
                $data['instructor_id'] = $request->instructor_id;
            }
            $data['material_path'] = $materialPath;
            $course->update($data);
            
            $msg = 'Course updated successfully!';
        } else {
            $course = Course::create([
                'title' => $request->title,
                'description' => $request->description,
                'instructor_id' => $instructorId,
                'material_path' => $materialPath
            ]);
            $msg = 'Course created successfully!';
        }

        return redirect()->route('courses_show', ['id' => $course->id])->with('success', $msg);
    }

    public function editMaterials($course_id)
    {
        if (!auth()->user()->hasPermissionTo('upload_content')) {
            abort(403, 'Unauthorized action. You do not have upload_content permission.');
        }

        $course = Course::findOrFail($course_id);

        // Check ownership (instructors can only edit their own courses, admin can edit all)
        if (!auth()->user()->hasRole('course_admin') && $course->instructor_id !== auth()->id()) {
            abort(403, 'You do not own this course.');
        }

        return view('courses.materials', compact('course'));
    }

    public function saveMaterials(Request $request)
    {
        if (!auth()->user()->hasPermissionTo('upload_content')) {
            abort(403, 'Unauthorized action. You do not have upload_content permission.');
        }

        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'course_material' => 'required|file|max:10240' // Max 10MB
        ]);

        $course = Course::findOrFail($request->course_id);

        // Check ownership (instructors can only edit their own courses, admin can edit all)
        if (!auth()->user()->hasRole('course_admin') && $course->instructor_id !== auth()->id()) {
            abort(403, 'You do not own this course.');
        }

        if ($request->hasFile('course_material')) {
            $materialPath = $request->file('course_material')->store('materials', 'public');
            $course->update([
                'material_path' => $materialPath
            ]);
            $msg = 'Course materials uploaded successfully!';
        } else {
            $msg = 'No file was selected.';
        }

        return redirect()->route('courses_show', ['id' => $course->id])->with('success', $msg);
    }

    public function deleteMaterials($id)
    {
        if (!auth()->user()->hasPermissionTo('upload_content')) {
            abort(403, 'Unauthorized action. You do not have upload_content permission.');
        }

        $course = Course::findOrFail($id);

        // Check ownership (instructors can only edit their own courses, admin can edit all)
        if (!auth()->user()->hasRole('course_admin') && $course->instructor_id !== auth()->id()) {
            abort(403, 'You do not own this course.');
        }

        if ($course->material_path) {
            if (\Illuminate\Support\Facades\Storage::disk('public')->exists($course->material_path)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($course->material_path);
            }
            $course->update(['material_path' => null]);
            $msg = 'Course materials deleted successfully!';
        } else {
            $msg = 'No materials found to delete.';
        }

        return redirect()->route('courses_show', ['id' => $course->id])->with('success', $msg);
    }

    public function delete($id)
    {
        if (!auth()->user()->hasPermissionTo('upload_content')) {
            abort(403, 'Unauthorized action.');
        }

        $course = Course::findOrFail($id);
        
        if (!auth()->user()->hasRole('course_admin') && $course->instructor_id !== auth()->id()) {
            abort(403, 'Unauthorized action. You do not own this course.');
        }

        $course->delete(); // Soft deletes
        return redirect()->route('courses_list')->with('success', 'Course deleted successfully!');
    }

    // Student Enrollment action
    public function enroll(Request $request)
    {
        if (!auth()->user()->hasPermissionTo('enroll_course')) {
            abort(403, 'Unauthorized action. You do not have enroll_course permission.');
        }

        $request->validate([
            'course_id' => 'required|exists:courses,id'
        ]);

        // Check if already enrolled
        $alreadyEnrolled = Enrollment::where('user_id', auth()->id())
            ->where('course_id', $request->course_id)
            ->exists();

        if ($alreadyEnrolled) {
            return redirect()->back()->withErrors('You are already enrolled in this course.');
        }

        Enrollment::create([
            'user_id' => auth()->id(),
            'course_id' => $request->course_id
        ]);

        return redirect()->back()->with('success', 'Enrolled in course successfully!');
    }
}
