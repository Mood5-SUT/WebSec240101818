<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\UsersController;
use App\Http\Controllers\Web\CourseController;
use App\Http\Controllers\Web\AssignmentController;
use App\Http\Controllers\Web\SecurityController;

// Home / Marketing page
Route::get('/', [CourseController::class, 'index'])->name('home');

// Authentication Routes
Route::get('login', [UsersController::class, 'login'])->name('login');
Route::post('do_login', [UsersController::class, 'do_login'])->name('do_login');
Route::get('register', [UsersController::class, 'register'])->name('register');
Route::post('do_register', [UsersController::class, 'do_register'])->name('do_register');
Route::post('do_logout', [UsersController::class, 'do_logout'])->name('do_logout');
Route::get('verify/{token?}', [UsersController::class, 'verify'])->name('verify');

// Social Login (Dynamic Provider: google, linkedin, facebook, microsoft)
Route::get('auth/{provider}', [UsersController::class, 'socialRedirect'])->name('social_login');
Route::get('auth/{provider}/callback', [UsersController::class, 'socialCallback'])->name('social_callback');

// Password Change
Route::post('change-password', [UsersController::class, 'changePassword'])->name('change_password');

// Access Control / User Roles (Admins only)
Route::get('admin/users', [UsersController::class, 'list'])->name('users_list');
Route::post('admin/users/save', [UsersController::class, 'save'])->name('users_save');

// Course Management Routes
Route::get('courses', [CourseController::class, 'list'])->name('courses_list');
Route::get('courses/edit/{id?}', [CourseController::class, 'edit'])->name('courses_edit');
Route::post('courses/save', [CourseController::class, 'save'])->name('courses_save');
Route::get('courses/materials/{course_id}', [CourseController::class, 'editMaterials'])->name('courses_edit_materials');
Route::post('courses/save-materials', [CourseController::class, 'saveMaterials'])->name('courses_save_materials');
Route::post('courses/delete-materials/{id}', [CourseController::class, 'deleteMaterials'])->name('courses_delete_materials');
Route::post('courses/delete/{id}', [CourseController::class, 'delete'])->name('courses_delete');
Route::post('courses/enroll', [CourseController::class, 'enroll'])->name('courses_enroll');
Route::get('courses/show/{id}', [CourseController::class, 'show'])->name('courses_show');

// Assignment & Submissions Routes
Route::get('assignments/edit/{course_id}/{id?}', [AssignmentController::class, 'edit'])->name('assignments_edit');
Route::post('assignments/save', [AssignmentController::class, 'save'])->name('assignments_save');
Route::get('submissions', [AssignmentController::class, 'list'])->name('submissions_list');
Route::post('submissions/submit', [AssignmentController::class, 'submit'])->name('submissions_submit');
Route::post('submissions/grade', [AssignmentController::class, 'grade'])->name('submissions_grade');
Route::post('submissions/request-review', [AssignmentController::class, 'requestReview'])->name('submissions_request_review');

// Security Scan & Vulnerability Simulation Routes
Route::get('security-check', [SecurityController::class, 'check'])->name('security_check');
Route::get('security-check/test-password', [SecurityController::class, 'testPassword'])->name('security_test_password');
Route::get('security-check/test-sqli', [SecurityController::class, 'testSqlInjection'])->name('security_test_sqli');
Route::get('security-check/test-xss', [SecurityController::class, 'testXss'])->name('security_test_xss');
