<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
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
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|unique:products,slug',
            'price' => 'required|numeric',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'images' => 'array',
            'images.*' => 'url',
        ]);

        $product = Product::create($data);

        if (!empty($data['images'])) {
            foreach ($data['images'] as $url) {
                $product->images()->create(['url' => $url]);
            }
        }

        return response()->json($product->load(['category', 'images']), 201);
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $data = $request->validate([
            'title' => 'sometimes|string|max:255',
            'slug' => 'sometimes|string|unique:products,slug,' . $product->id,
            'price' => 'sometimes|numeric',
            'description' => 'nullable|string',
            'category_id' => 'sometimes|exists:categories,id',
            'images' => 'array',
            'images.*' => 'url',
        ]);

        $product->update($data);

        if (isset($data['images'])) {
            $product->images()->delete();
            foreach ($data['images'] as $url) {
                $product->images()->create(['url' => $url]);
            }
        }

        return response()->json($product->load(['category', 'images']));
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->images()->delete();
        $product->delete();

        return response()->json(['message' => 'Product deleted']);
    }
}
