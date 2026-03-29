<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductHomeResource;
use App\Models\Product;

class ProductHomeController extends Controller
{
    public function index()
    {
        $products = Product::query()
            ->active()
            ->with([
                'categories:id,name,slug',
                'brand:id,name,slug',
                'media' => fn ($q) => $q->where('collection_name', 'thumbnail'),
            ])
            ->withMin('variants', 'price')
            ->latest()
            ->limit(8)->get();

        return ProductHomeResource::collection($products);
    }

    public function slider()
    {
        $products = Product::with('media')
            ->where('is_active', 1)
            ->where('is_featured', 1)
            ->latest()
            ->limit(5)
            ->get();

        return ProductHomeResource::collection($products);
    }
}
