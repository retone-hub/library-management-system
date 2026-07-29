<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CategoryController;

Route::delete('categories/{category}/force', [CategoryController::class, 'forceDelete'])->withTrashed();
Route::put('categories/{category}/restore', [CategoryController::class, 'restore'])->withTrashed();
Route::get('categories/trash', [CategoryController::class, 'trash']);
Route::apiResource('categories', CategoryController::class);


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
