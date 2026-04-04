<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductShopResource;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

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

    public function index(Request $request)
    {
        $products = Product::with(['categories', 'brand', 'media', 'variants'])
            ->withMin('variants', 'price')
            ->withMax('variants', 'price')
            ->whereHas('variants', fn ($v) => $v->where('stock_quantity', '>', 0))
            ->when($request->filled('category'), function ($q) use ($request) {
                $q->whereHas('categories', fn ($c) => $c->where('slug', $request->category));
            })
            ->when($request->filled('sort'), function ($q) use ($request) {
                match ($request->sort) {
                    'price_asc'  => $q->orderBy('variants_min_price', 'asc'),
                    'price_desc' => $q->orderBy('variants_min_price', 'desc'),
                    'name_asc'   => $q->orderBy('name', 'asc'),
                    'name_desc'  => $q->orderBy('name', 'desc'),
                    default      => null,
                };
            })
            ->when($request->filled('search'), function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            })
            ->paginate($request->input('per_page', 12));

        return ProductShopResource::collection($products);
    }
}
