<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::paginate()
            ->through(fn($user) => [
                'id' => $user->id,
                'name' => $user->name
            ]);

        return inertia('Categories/Index', compact('categories'));
    }

    public function create()
    {
        return inertia('Categories/Edit');
    }

    public function store(CategoryRequest $request)
    {
        Category::create($request->validated());

        return to_route('categories.index');
    }

    public function edit(Category $category)
    {
        return inertia('Categories/Edit', compact('category'));
    }

    public function update(CategoryRequest $request, Category $category)
    {
        $category->update($request->validated());

        return to_route('categories.index');
    }
}
