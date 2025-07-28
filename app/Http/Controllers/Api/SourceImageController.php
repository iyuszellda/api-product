<?php

namespace App\Http\Controllers\Api;

use App\Models\Images;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

class SourceImageController extends Controller
{
    public function index(): JsonResponse
    {
        $images = Images::orderBy('product_id')->orderBy('sequence')->get([
            'product_id',
            'sequence',
            'title',
            'url',
        ]);

        return response()->json($images, 200);
    }
}