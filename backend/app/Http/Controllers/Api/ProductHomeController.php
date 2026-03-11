<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductHomeResource;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductHomeController extends Controller
{
    public function index()
    {
        $products = Product::with([
            'category:id,name,slug',
            'brand:id,name,slug',
            'gender:id,name,slug',
        ])->where('is_active', 1)
        ->withMin('productVariants', 'price')->paginate(10);
        // return response()->json($products);
        return ProductHomeResource::collection($products);
    }
}
