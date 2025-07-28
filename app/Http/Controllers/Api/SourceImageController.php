<?php

namespace App\Http\Controllers\Api;

use App\Models\Images;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

class SourceImageController extends Controller
{
    public function index(): JsonResponse
    {
        $images = Images::orderBy('product_id')
            ->orderBy('sequence')
            ->get(['sequence', 'title', 'url'])
            ->map(function ($image, $i) {
                return [
                    'id' => $i +1,
                    'title'      => "{$image->title} {$image->sequence}",
                    'url'        => $image->url,
                ];
            });

        return response()->json($images, 200);
    }
}