<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductShopResource;
use App\Models\Category;
use App\Models\Product;

class ShopController extends Controller
{
    public function show(Product $product)
    {
        $product->load([
            'brand',
            'categories',
            'variants',
            'media',
        ])->loadMin('variants', 'price');

        return new ProductShopResource($product);
        // return response()->json($product);
    }

    public function similar(Category $category)
    {
        // Get products that belong to this category, optionally exclude a specific product
        $products = Product::with(['categories', 'brand', 'media', 'variants'])
            ->withMin('variants', 'price')
            ->whereHas('categories', fn ($q) => $q->where('id', $category->id))
            ->active()
            ->limit(6)
            ->get();

        return ProductShopResource::collection($products);
    }

    public function index()
    {
        $products = Product::with(['categories', 'brand', 'media', 'variants'])
            ->withMin('variants', 'price')
            ->whereHas('variants', fn ($v) => $v->where('stock_quantity', '>', 0))
            ->paginate(10);

        return ProductShopResource::collection($products);
        // return response()->json($products);
    }
}
