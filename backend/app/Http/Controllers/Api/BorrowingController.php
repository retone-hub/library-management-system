<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Borrowing;
use App\Http\Requests\BorrowRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\BorrowingResource;

class BorrowingController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $user = $request->input('user_id');
        $book = $request->input('book_id');
        $status = $request->input('status');
        $sort = $request->input('sort', 'borrowed_at');
        $direction = $request->input('direction', 'desc');

        //Validation
        $allowedSorts = [
            'borrowed_at',
            'due_date',
        ];

        if (!in_array($sort, $allowedSorts)) {
            $sort = 'borrowed_at';
        }

        if (!in_array($direction, ['asc', 'desc'])) {
            $direction = 'desc';
        }

        //Query Builder
        $query = Borrowing::query();
        
        $query->with([
            'user',
            'book',
        ])
        ->when($search, function ($query) use ($search) {
            $query->where(function ($query) use ($search) {
                $query->whereHas('book', function ($query) use ($search) { // search whereHas
                    $query->where('title', 'LIKE', "%{$search}%");
                })
                ->orWhereHas('user', function($query) use ($search){ // filter user
                    $query->where('name', 'LIKE', "%{$search}%");
                });
            });
        })
        ->when($book, function ($query) use ($book) {
            $query->where('book_id', $book);
        })
        ->when($status, function ($query) use ($status) { // filter status
            if ($status == 'borrowed') {
                $query->whereNull('returned_at');
            }
            if ($status == 'returned') {
                $query->whereNotNull('returned_at');
            }
        })
        ->orderBy($sort, $direction); //sorting

        $borrowings = $query->paginate(10);

        return BorrowingResource::collection($borrowings);
    }

    public function store(BorrowRequest $request)
    {
        // Cari buku berdasarkan ID
        $book = Book::findOrFail($request->book_id);

        // Cek apakah stok masih tersedia
        if ($book->stock <= 0) {
            return response()->json([
                'message' => 'Book is out of stock.',
            ], 400);
        }

        // Cek apakah user masih meminjam buku yang sama
        $alreadyBorrowed = Borrowing::where('user_id', $request->user_id)
        ->where('book_id', $request->book_id)
        ->whereNull('returned_at')
        ->exists();

        if ($alreadyBorrowed){
            return response()->json([
                'message' => 'You are still borrowing this book.'
            ], 400);
        }

        // Transaction
        $borrowing = DB::transaction(function () use ($book, $request) {

            //Kurangi stok buku
            $book->decrement('stock');

            //Simpan data peminjam
            return Borrowing::create([
                'user_id' => $request->user_id,
                'book_id' => $request->book_id,
                'borrowed_at' => now(),
                'due_date' => now()->addDays(7), //aturan 7 hari
            ]);
        });

        // Load relasi agar response lebih lengkap
        $borrowing->load([
            'user',
            'book',
        ]);

        return new BorrowingResource($borrowing);
    }

    public function show(Borrowing $borrowing)
    {
       

        return new BorrowingResource($borrowing);
    }

    public function returnBook(Borrowing $borrowing)
    {
        // Cek apakah buku sudah dikembalikan
        if ($borrowing->returned_at){
            return response()->json([
                'message' => 'Book has already been returned.'
            ], 400);
        }

        // Transaction
        DB::transaction(function () use ($borrowing) {
            
            //Tandai buku yang sudah dikembalikan
            $borrowing->update([
                'returned_at' => now,
            ]);

            // Tambah stok buku
            $borrowing->book->increment('stock');
        });

        // Load relasi untuk response
        $borrowing->load([
            'user',
            'book',
        ]);

        return new BorrowingResource($borrowing);
    }
}