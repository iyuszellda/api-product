<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'price' => $this->price,
            'description' => $this->description,
            'category' => [
                'id' => $this->category->id,
                'name' => $this->category->name,
                'slug' => $this->category->slug,
                'image' => $this->category->image,
                'creationAt' => $this->category->created_at,
                'updatedAt' => $this->category->updated_at,
            ],
            'images' => $this->images->pluck('url')->all(),
            'creationAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
        ];
    }
}
