<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    // GET ALL PRODUCTS (public) with filters
    public function index(Request $request)
    {
        $query = Product::with(['category', 'images'])
            ->where('is_active', true);

        // Filter by category
        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by brand
        if ($request->brand) {
            $query->where('brand', $request->brand);
        }

        // Filter by price range
        if ($request->min_price) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->max_price) {
            $query->where('price', '<=', $request->max_price);
        }

        // Search by name
        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $products = $query->latest()->paginate(12);

        return response()->json($products);
    }

    // GET SINGLE PRODUCT (public)
    public function show($id)
    {
        $product = Product::with(['category', 'images'])
            ->findOrFail($id);

        return response()->json($product);
    }

    // CREATE PRODUCT (admin only)
    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name'        => 'required|string|max:255',
            'brand'       => 'required|string|max:100',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'images'      => 'nullable|array',
            'images.*'    => 'image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $product = Product::create([
            'category_id' => $request->category_id,
            'name'        => $request->name,
            'slug'        => Str::slug($request->name),
            'brand'       => $request->brand,
            'description' => $request->description,
            'price'       => $request->price,
            'stock'       => $request->stock,
            'is_active'   => true,
        ]);

        // Handle image uploads
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('products', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path,
                    'is_primary' => $index === 0, // first image is primary
                ]);
            }
        }

        return response()->json([
            'message' => 'Product created successfully',
            'product' => $product->load('images')
        ], 201);
    }

    // UPDATE PRODUCT (admin only)
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name'        => 'required|string|max:255',
            'brand'       => 'required|string|max:100',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
        ]);

        $product->update([
            'category_id' => $request->category_id,
            'name'        => $request->name,
            'slug'        => Str::slug($request->name),
            'brand'       => $request->brand,
            'description' => $request->description,
            'price'       => $request->price,
            'stock'       => $request->stock,
        ]);

        return response()->json([
            'message' => 'Product updated successfully',
            'product' => $product->load('images')
        ]);
    }

    // DELETE PRODUCT (admin only)
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return response()->json([
            'message' => 'Product deleted successfully'
        ]);
    }

    // GET ALL BRANDS (public)
    public function brands()
    {
        $brands = Product::where('is_active', true)
            ->distinct()
            ->pluck('brand');

        return response()->json($brands);
    }
}
