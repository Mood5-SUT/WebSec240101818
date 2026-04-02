<?php

use App\Http\Controllers\Web\BooksController;
use App\Http\Controllers\Web\BorrowsController;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\RolesController;
use App\Http\Controllers\Web\UsersController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'list'])->name('home');

Route::get('login', [UsersController::class, 'login'])->name('users_login');
Route::post('login', [UsersController::class, 'authenticate'])->name('users_authenticate');
Route::get('register', [UsersController::class, 'register'])->name('users_register');
Route::post('register', [UsersController::class, 'saveRegistration'])->name('users_register_save');
Route::post('logout', [UsersController::class, 'logout'])->name('users_logout');

Route::get('members', [UsersController::class, 'list'])->name('users_list');
Route::get('members/password/{id}', [UsersController::class, 'editMember'])->name('users_member_password_edit');
Route::post('members/password/{id}', [UsersController::class, 'saveMemberPassword'])->name('users_member_password_save');
Route::get('librarians/{id?}', [UsersController::class, 'edit'])->name('users_edit');
Route::post('librarians/{id?}', [UsersController::class, 'save'])->name('users_save');
Route::post('librarians/delete/{id}', [UsersController::class, 'delete'])->name('users_delete');
Route::get('profile', [UsersController::class, 'profile'])->name('users_profile');
Route::post('profile/password', [UsersController::class, 'changePassword'])->name('users_password');

Route::get('roles', [RolesController::class, 'list'])->name('roles_list');

Route::get('books', [BooksController::class, 'list'])->name('books_list');
Route::get('book/edit/{id?}', [BooksController::class, 'edit'])->name('books_edit');
Route::post('book/save/{id?}', [BooksController::class, 'save'])->name('books_save');
Route::post('book/delete/{id}', [BooksController::class, 'delete'])->name('books_delete');

Route::get('borrowed-books', [BorrowsController::class, 'list'])->name('borrow_list');
Route::post('borrow/{bookId}', [BorrowsController::class, 'save'])->name('borrow_save');
