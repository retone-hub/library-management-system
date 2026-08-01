<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;
use App\Http\Resources\BookResource;
use App\Http\Requests\BookRequest;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search'); //bisa diganti query tetapi hanya mengambil tipe data string, kalau input fleksibel
        $category = $request->input('category_id');
        $author = $request->input('author_id');
        $publisher = $request->input('publisher_id');

        $sort = $request->input('sort', 'published_year');
        $direction = $request->input('direction', 'asc');

        //Validation
        $allowedSorts = [
            'title',
            'published_year',
            'stock',
        ];

        if (! in_array($sort, $allowedSorts)) {
            $sort = 'published_year';
        }

        if (! in_array($direction, ['asc', 'desc'])) {
            $direction = 'asc';
        }

        //Query Builder
        $query = Book::query();
        // $query->with([
        //     'category',
        //     'author',
        //     'publisher',
        // ]);
        // $query->when($search, function ($query) use ($search) { // use itu membawa variabel dari luar function ke dalam closure (anonymous function)
        //     $query->where('title', 'LIKE', "%{$search}%");
        // });
        

        $query->with([
            'category',
            'author',
            'publisher',
        ])
        ->when($search, function ($query) use ($search) {
            $query->where('title', 'LIKE', "%$search%");
        })
        ->when($category, function ($query) use ($category) {
            $query->where('category_id', $category);
        })
        ->when($author, function ($query) use ($author) {
            $query->where('author_id', $author);
        })
        ->when($publisher, function ($query) use ($publisher) {
            $query->where('publisher_id', $publisher);
        })
        ->orderBy($sort, $direction);

        $books = $query->paginate(10);

        // $books = Book::with([ // tidak digunakan karena sudah ada functionnya termasuk search
        //     'category',
        //     'author',
        //     'publisher',
        // ])->simplePaginate(10); // get bisa diganti paginate(10) untuk halamar 1,2,3,4,5,.. 100 or simplePaginate(10) cocok untuk previous dan next

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


    public function destroy(Book $book)
    {
        $book->delete();

        return response()->json([
            'message' => 'Book deleted successfully.',
        ]);
    }
}
