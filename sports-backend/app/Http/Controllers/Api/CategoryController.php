<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    // GET ALL CATEGORIES (public)
    public function index()
    {
        $categories = Category::withCount('products')->get();
        return response()->json($categories);
    }

    // GET SINGLE CATEGORY (public)
    public function show($id)
    {
        $category = Category::withCount('products')->findOrFail($id);
        return response()->json($category);
    }

    // CREATE CATEGORY (admin only)
    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255|unique:categories',
            'description' => 'nullable|string',
            'image'       => 'nullable|string',
        ]);

        $category = Category::create([
            'name'        => $request->name,
            'slug'        => Str::slug($request->name),
            'description' => $request->description,
            'image'       => $request->image,
        ]);

        return response()->json([
            'message'  => 'Category created successfully',
            'category' => $category
        ], 201);
    }

    // UPDATE CATEGORY (admin only)
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $request->validate([
            'name'        => 'required|string|max:255|unique:categories,name,' . $id,
            'description' => 'nullable|string',
            'image'       => 'nullable|string',
        ]);

        $category->update([
            'name'        => $request->name,
            'slug'        => Str::slug($request->name),
            'description' => $request->description,
            'image'       => $request->image,
        ]);

        return response()->json([
            'message'  => 'Category updated successfully',
            'category' => $category
        ]);
    }

    // DELETE CATEGORY (admin only)
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return response()->json([
            'message' => 'Category deleted successfully'
        ]);
    }
}
