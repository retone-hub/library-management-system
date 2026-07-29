<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Http\Resources\CategoryResource;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use Iluminate\Database\Eloquent\Relations\HasMany;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() // mengambil semua kategori
    {
        $categories = Category::all();

        return CategoryResource::collection($categories);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        $category = Category::create($request->validated());

        return new CategoryResource($category);
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        return new CategoryResource($category);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $category->update($request->validated());

        return new CategoryResource($category);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $category->delete();

        return response()->json([
            'message' => 'Category deleted successfully.'
        ]);
    }

    public function trash()
    {
        $categories = Category::onlyTrashed()->get();

        return CategoryResource::collection($categories);
    }

    public function restore(Category $category)
    {
         //$category = Category::withTrashed()->findOrFail($id);

        $category->restore();

        return new CategoryResource($category);
    }

    public function forceDelete(Category $category)
    {
        $category->forceDelete();

        return response()->json([
            'message' => 'Category permanently deleted successfully.'
        ]);
    }

    public function books(): HasMany
    {
        return $this->hasMany(Book::class);
    }


}


