<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;
use App\Http\Resources\BookResource;
use App\Http\Requests\BookRequest;
use Iluminate\Database\Eloquent\Relations\BelongsTo;

class BookController extends Controller
{
    public function index()
    {
        $books = Book::with([
            'category',
            'author',
            'publisher',
        ])->get(); // get bisa diganti paginate(10) or simplePaginate(10) cocok untuk 10 perhalaman jadi tidak di ambil semua

        return BookResource::collection($books);
    }


    public function store(BookRequest $request)
    {
        $book = Book::create($request->validated());

        $book->load([
            'category',
            'author',
            'publisher',
        ]);

        return new BookResource($book);
    }


    public function show(Book $book)
    {
        $book->load([
            'category',
            'author',
            'publisher',
        ]);

        return new BookResource($book);
    }


    public function update(BookRequest $request, Book $book)
    {
        $book->update($request->validated());

        $book->load([
            'category',
            'author',
            'publisher',
        ]);

        return new BookResource($book);
    }


    public function destroy(string $id)
    {
        $book->deleted();

        return response()->json([
            'message' => 'Book deleted successfully.',
        ]);
    }
}
