<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Borrow;
use App\Models\User;
use Spatie\Permission\Models\Role;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:web')->except(['list']);
    }

    public function list()
    {
        $booksCount = Book::count();
        $availableCopies = Book::sum('copies');
        $memberRoleExists = Role::where('name', 'Member')->where('guard_name', 'web')->exists();
        $membersCount = $memberRoleExists ? User::role('Member')->count() : 0;
        $activeBorrows = Borrow::whereNull('returned_at')->count();
        $featuredBooks = Book::orderBy('created_at', 'desc')->take(3)->get();

        return view('home.list', compact('booksCount', 'availableCopies', 'membersCount', 'activeBorrows', 'featuredBooks'));
    }
}
