@extends('layouts.master')

@section('title', 'Book Form')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header">{{ isset($book->id) ? 'Edit Book' : 'Add Book' }}</div>
            <div class="card-body">
                <form action="{{ route('books_save', $book->id ?? '') }}" method="POST">
                    {{ csrf_field() }}
                    <div class="mb-3">
                        <label class="form-label">Title</label>
                        <input type="text" name="title" class="form-control" value="{{$book->title ?? ''}}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Author</label>
                        <input type="text" name="author" class="form-control" value="{{$book->author ?? ''}}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">ISBN</label>
                        <input type="text" name="isbn" class="form-control" value="{{$book->isbn ?? ''}}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Copies</label>
                        <input type="number" name="copies" class="form-control" min="0" value="{{$book->copies ?? 0}}">
                    </div>
                    <button type="submit" class="btn btn-primary">Save Book</button>
                    <a href="{{ route('books_list') }}" class="btn btn-outline-secondary">Back</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
