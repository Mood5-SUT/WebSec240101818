<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Borrow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BorrowsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:web')->except(['list', 'show']);
    }

    public function list()
    {
        if (!auth()->check()) {
            return redirect()->route('users_login');
        }

        $borrows = Borrow::with('book')
            ->where('user_id', auth()->id())
            ->whereNull('returned_at')
            ->latest('borrowed_at')
            ->get();

        return view('users.profile', [
            'activeBorrows' => $borrows,
            'borrowingLimit' => 3,
            'remainingLimit' => 3 - $borrows->count(),
            'borrowingStatus' => $borrows->count() < 3 ? 'Eligible to borrow books' : 'Borrowing limit reached',
        ]);
    }

    public function save(Request $request, $bookId)
    {
        if (!auth()->user()->hasRole('Member')) {
            abort(403);
        }

        $borrowingLimit = 3;
        $activeBorrowCount = Borrow::where('user_id', auth()->id())->whereNull('returned_at')->count();

        if ($activeBorrowCount >= $borrowingLimit) {
            return redirect()->route('users_profile')->with('error', 'Your borrowing limit has been reached.');
        }

        $alreadyBorrowed = Borrow::where('user_id', auth()->id())
            ->where('book_id', $bookId)
            ->whereNull('returned_at')
            ->exists();

        if ($alreadyBorrowed) {
            return redirect()->route('books_list')->with('error', 'You already borrowed this book and have not returned it yet.');
        }

        $borrowed = DB::transaction(function () use ($bookId) {
            $book = Book::where('id', $bookId)->lockForUpdate()->firstOrFail();

            if ($book->copies <= 0) {
                return false;
            }

            Borrow::create([
                'user_id' => auth()->id(),
                'book_id' => $book->id,
                'borrowed_at' => now(),
            ]);

            $book->copies = $book->copies - 1;
            $book->save();

            return true;
        });

        if (!$borrowed) {
            return redirect()->route('books_list')->with('error', 'Book Currently Unavailable');
        }

        return redirect()->route('users_profile')->with('success', 'Book borrowed successfully.');
    }
}
