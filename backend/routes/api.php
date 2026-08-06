<?php

use App\Http\Controllers\Api\BorrowingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\BookController;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

//harus punya token untuk mengakses api berikut
Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/borrowings', [BorrowingController::class, 'index']);

    Route::post('/borrowings', [BorrowingController::class, 'store']);

    Route::patch('/borrowings/{borrowing}/return', [BorrowingController::class, 'returnBook']);

    Route::apiResource('books', BookController::class);

    Route::apiResource('borrowings', BorrowingController::class);
});

Route::patch('borrowings/{borrowing}/return', [BorrowingController::class, 'returnBook']);
Route::delete('categories/{category}/force', [CategoryController::class, 'forceDelete'])->withTrashed(); // kenapa withTrashed karena data yang dihapus biasanya sudah berada di trash.
Route::put('categories/{category}/restore', [CategoryController::class, 'restore'])->withTrashed();
Route::get('categories/trash', [CategoryController::class, 'trash']);
Route::apiResource('categories', CategoryController::class);


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
