<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductImage;

class ImportProducts extends Command
{
    protected $signature = 'import:products';
    protected $description = 'Import products, categories, and images from a JSON file';

    public function handle()
    {
        $path = base_path('storage/app/data.json');
        if (!file_exists($path)) {
            $this->error("File not found at: $path");
            return;
        }
        $json = file_get_contents($path);
        $items = json_decode($json, true);
        if (!is_array($items)) {
            $this->error('Invalid JSON: ' . json_last_error_msg());
            return;
        }

        foreach ($items as $item) {
            // Insert or update category
            $category = Category::updateOrCreate(
                ['slug' => $item['category']['slug']], // ✅ match by slug
                [
                    'name' => $item['category']['name'],
                    'image' => $item['category']['image'],
                    'created_at' => $item['category']['creationAt'],
                    'updated_at' => $item['category']['updatedAt'],
                ]
            );

            // Insert or update product
            $product = Product::updateOrCreate(
                ['id' => $item['id']],
                [
                    'title' => $item['title'],
                    'slug' => $item['slug'],
                    'price' => $item['price'],
                    'description' => $item['description'],
                    'category_id' => $category->id,
                    'created_at' => $item['creationAt'],
                    'updated_at' => $item['updatedAt'],
                ]
            );

            // Insert images (clear and re-insert for update cases)
            $product->images()->delete();
            foreach ($item['images'] as $url) {
                $product->images()->create(['url' => $url]);
            }
        }

        $this->info('Products, categories, and images imported successfully.');
    }
}