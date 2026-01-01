<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\V1\CategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends ApiController
{
    public function index(Request $request)
    {
        $perPage = (int) $request->get('per_page', 15);
        $perPage = $perPage > 0 ? min($perPage, 100) : 15;

        $categories = Category::withCount('posts')
            ->latest('id')
            ->paginate($perPage);

        return $this->success(CategoryResource::collection($categories), 'Categories retrieved successfully');
    }

    public function store(CategoryRequest $request)
    {
        $category = Category::create($request->validated());
        $category->loadCount('posts');
        return $this->created(new CategoryResource($category), 'Category created successfully');
    }

    public function show(Category $category)
    {
        $category->loadCount('posts');
        return $this->success(new CategoryResource($category), 'Category fetched successfully');
    }

    public function update(CategoryRequest $request, Category $category)
    {
        $category->update($request->validated());
        $category->loadCount('posts');
        return $this->success(new CategoryResource($category), 'Category updated successfully');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return $this->success(message: 'Category deleted successfully');
    }
}
