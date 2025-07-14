<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
// use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'images']);

        // Filter by category ID
        if ($request->filled('categoryId') && $request->categoryId > 0) {
            $query->where('category_id', $request->categoryId);
        }

        // Filter by price range
        if ($request->filled('price_min')) {
            $query->where('price', '>=', $request->price_min);
        }
        if ($request->filled('price_max')) {
            $query->where('price', '<=', $request->price_max);
        }

        // Offset & Limit (paging)
        if ($request->has('offset') && $request->has('limit')) {
            $offset = (int) $request->input('offset', 0);
            $limit = (int) $request->input('limit', 10);
            $query->skip($offset)->take($limit);
        }
        $products = $query->get();

        // return value
        return $products->map(function ($product) {
            return [
                'id' => $product->id,
                'title' => $product->title,
                'slug' => $product->slug,
                'price' => $product->price,
                'description' => $product->description,
                'category' => [
                    'id' => $product->category->id,
                    'name' => $product->category->name,
                    'slug' => $product->category->slug,
                    'image' => $product->category->image,
                    'creationAt' => $product->category->created_at,
                    'updatedAt' => $product->category->updated_at,
                ],
                'images' => $product->images->pluck('url')->all(),
                'creationAt' => $product->created_at,
                'updatedAt' => $product->updated_at,
            ];
        })->values();
    }

    public function show($id)
    {
        $product = Product::with(['category', 'images'])->findOrFail($id);
        $transformed = [
            'id' => $product->id,
            'title' => $product->title,
            'slug' => $product->slug,
            'price' => $product->price,
            'description' => $product->description,
            'category' => [
                'id' => $product->category->id,
                'name' => $product->category->name,
                'slug' => $product->category->slug,
                'image' => $product->category->image,
                'creationAt' => $product->category->created_at,
                'updatedAt' => $product->category->updated_at,
            ],
            'images' => $product->images->pluck('url')->all(),
            'creationAt' => $product->created_at,
            'updatedAt' => $product->updated_at,
        ];

        return response()->json($transformed);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'categoryId' => 'required|exists:categories,id',
            'images' => 'nullable|array',
            'images.*' => 'url',
        ]);

        $slug = Str::slug($request->title);
        $slugExists = Product::where('slug', $slug)->exists();
        if ($slugExists) {
            $slug .= '-' . time();
        }
        $product = Product::create([
            'title' => $request->title,
            'slug' => $slug,
            'price' => $request->price,
            'description' => $request->description,
            'category_id' => $request->categoryId,
        ]);

        if ($request->has('images')) {
            foreach ($request->images as $url) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'url' => $url,
                ]);
            }
        }

        // Eager load category and images
        $product->load('category', 'images');

        return response()->json([
            'id' => $product->id,
            'title' => $product->title,
            'slug' => $product->slug,
            'price' => (float) $product->price,
            'description' => $product->description,
            'images' => $product->images->pluck('url'),
            'category' => [
                'id' => $product->category->id,
                'name' => $product->category->name,
                'image' => $product->category->image,
                'slug' => $product->category->slug,
            ],
            'creationAt' => $product->created_at->toISOString(),
            'updatedAt' => $product->updated_at->toISOString(),
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'price' => 'sometimes|required|numeric|min:0',
            'description' => 'nullable|string',
            'categoryId' => 'sometimes|required|exists:categories,id',
            'images' => 'nullable|array',
            'images.*' => 'url',
        ]);

        if ($request->has('title')) {
            $product->title = $request->title;
            $slug = Str::slug($request->title);
            $slugExists = Product::where('slug', $slug)->where('id', '!=', $product->id)->exists();
            $product->slug = $slugExists ? $slug . '-' . time() : $slug;
        }

        if ($request->has('price')) {
            $product->price = $request->price;
        }

        if ($request->has('description')) {
            $product->description = $request->description;
        }

        if ($request->has('categoryId')) {
            $product->category_id = $request->categoryId;
        }

        $product->save();

        // Replace images
        if ($request->has('images')) {
            $product->images()->delete(); // Delete old images

            foreach ($request->images as $url) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'url' => $url,
                ]);
            }
        }

        // Load relations
        $product->load('category', 'images');

        return response()->json([
            'id' => $product->id,
            'title' => $product->title,
            'slug' => $product->slug,
            'price' => (float) $product->price,
            'description' => $product->description,
            'images' => $product->images->pluck('url'),
            'category' => [
                'id' => $product->category->id,
                'name' => $product->category->name,
                'image' => $product->category->image,
                'slug' => $product->category->slug,
            ],
            'creationAt' => $product->created_at->toISOString(),
            'updatedAt' => $product->updated_at->toISOString(),
        ]);
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->images()->delete();
        $product->delete();

        return response()->json(true);
    }
}
