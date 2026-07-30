<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\BookController;

Route::delete('categories/{category}/force', [CategoryController::class, 'forceDelete'])->withTrashed(); // kenapa withTrashed karena data yang dihapus biasanya sudah berada di trash.
Route::put('categories/{category}/restore', [CategoryController::class, 'restore'])->withTrashed();
Route::get('categories/trash', [CategoryController::class, 'trash']);
Route::apiResource('categories', CategoryController::class);
Route::apiResource('books', BookController::class);


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
