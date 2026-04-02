<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;

class BooksController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:web')->except(['list', 'show']);
    }

    public function list()
    {
        $books = Book::orderBy('title')->get();

        return view('books.list', compact('books'));
    }

    public function show($id)
    {
        $book = Book::findOrFail($id);

        return view('books.show', compact('book'));
    }

    public function edit($id = null)
    {
        if (!auth()->user()->hasPermissionTo('manage_books')) {
            abort(403);
        }

        $book = $id ? Book::findOrFail($id) : new Book();

        return view('books.edit', compact('book'));
    }

    public function save(Request $request, $id = null)
    {
        if (!auth()->user()->hasPermissionTo('manage_books')) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => 'required|string|max:255|unique:books,isbn,'.($id ?? 'NULL').',id',
            'copies' => 'required|numeric',
        ]);

        $book = $id ? Book::findOrFail($id) : new Book();
        $book->title = $request->title;
        $book->author = $request->author;
        $book->isbn = $request->isbn;
        $book->copies = (int) $request->copies;
        $book->save();

        return redirect()->route('books_list')->with('success', 'Book saved successfully.');
    }

    public function delete($id)
    {
        if (!auth()->user()->hasPermissionTo('manage_books')) {
            abort(403);
        }

        $book = Book::findOrFail($id);
        $book->delete();

        return redirect()->route('books_list')->with('success', 'Book deleted successfully.');
    }
}
