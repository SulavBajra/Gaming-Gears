<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductStoreRequest;
use App\Http\Requests\Admin\ProductUpdateRequest;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Services\ProductService;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class ProductController extends Controller
{
    public function __construct(private readonly ProductService $productService) {}

    /**
     * @return Response
     *                  Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::latest()->with('brand', 'categories')
            ->withMin('variants', 'price')
            ->withMax('variants', 'price')
            ->withSum('variants', 'stock_quantity')
            ->paginate(8);

        return Inertia::render('products/Index', [
            'products' => $products,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $brands = Brand::where('is_active', 1)->select('id', 'name')->get();
        $categories = Category::where(['is_active' => 1, 'parent_id' => null])->select('id', 'name')->get();

        return Inertia::render('products/Create', [
            'brands' => $brands,
            'categories' => $categories,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductStoreRequest $request)
    {
        $this->productService->store($request);

        return to_route('products.index')->with('success', 'Product created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return Inertia::render('products/Show', [
            'product' => $product,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        return Inertia::render('products/Edit', [
            'brands' => Brand::select('id', 'name')->get(),
            'categories' => Category::select('id', 'name')->get(),
            'product' => [
                ...$product->only('id', 'name', 'description', 'brand_id', 'is_active', 'is_featured', 'tags'),
                'category_ids' => $product->categories->pluck('id'),
                'variants' => $product->variants->map->only('id', 'name', 'price', 'stock_quantity', 'is_active'),
                'thumbnail' => $product->getFirstMedia('thumbnail')
                    ? [
                        'id' => $product->getFirstMedia('thumbnail')->id,
                        'url' => $product->getFirstMedia('thumbnail')->getUrl(),
                    ]
                    : null,
                'gallery' => $product->getMedia('gallery')->map(fn ($m) => [
                    'id' => $m->id,
                    'url' => $m->getUrl(),
                ]),
            ],
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductUpdateRequest $request, Product $product)
    {
        $this->productService->update($request, $product);

        return to_route('products.index')->with('success', 'Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        Product::destroy($id);

        return to_route('products.index')->with('success', 'Deleted Successfully');
    }


    public function analytics()
    {
        // 1. Stock status distribution (for a pie/donut chart)
        $stockStatus = ProductVariant::selectRaw("
                SUM(CASE WHEN stock_quantity = 0 THEN 1 ELSE 0 END) as out_of_stock,
                SUM(CASE WHEN stock_quantity > 0 AND stock_quantity <= low_stock_threshold THEN 1 ELSE 0 END) as low_stock,
                SUM(CASE WHEN stock_quantity > low_stock_threshold THEN 1 ELSE 0 END) as healthy_stock
            ")
            ->where('is_active', true)
            ->first();

        $stockStatusChart = [
            ['name' => 'Healthy', 'value' => (int) $stockStatus->healthy_stock],
            ['name' => 'Low Stock', 'value' => (int) $stockStatus->low_stock],
            ['name' => 'Out of Stock', 'value' => (int) $stockStatus->out_of_stock],
        ];

        // 2. Inventory value by brand (price * stock_quantity, for a bar chart)
        $inventoryByBrand = Brand::query()
            ->join('products', 'products.brand_id', '=', 'brands.id')
            ->join('product_variants', 'product_variants.product_id', '=', 'products.id')
            ->selectRaw('brands.name as brand, SUM(product_variants.price * product_variants.stock_quantity) as inventory_value, SUM(product_variants.stock_quantity) as total_units')
            ->where('products.is_active', true)
            ->where('product_variants.is_active', true)
            ->groupBy('brands.id', 'brands.name')
            ->orderByDesc('inventory_value')
            ->get()
            ->map(fn ($row) => [
                'brand' => $row->brand,
                'inventoryValue' => round((float) $row->inventory_value, 2),
                'totalUnits' => (int) $row->total_units,
            ]);

        $stockByCategory = Category::query()
            ->join('category_product', 'category_product.category_id', '=', 'categories.id')
            ->join('products', 'products.id', '=', 'category_product.product_id')
            ->join('product_variants', 'product_variants.product_id', '=', 'products.id')
            ->selectRaw('categories.name as category, SUM(product_variants.stock_quantity) as total_stock')
            ->where('products.is_active', true)
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('total_stock')
            ->get();

        $lowStockProducts = Product::query()
            ->with(['brand', 'variants' => fn ($q) => $q->whereColumn('stock_quantity', '<=', 'low_stock_threshold')])
            ->whereHas('variants', fn ($q) => $q->whereColumn('stock_quantity', '<=', 'low_stock_threshold'))
            ->where('is_active', true)
            ->get()
            ->flatMap(function ($product) {
                return $product->variants->map(fn ($variant) => [
                    'product' => $product->name,
                    'brand' => $product->brand?->name,
                    'variant' => $variant->name,
                    'stock' => $variant->stock_quantity,
                    'threshold' => $variant->low_stock_threshold,
                ]);
            })
            ->sortBy('stock')
            ->values();

        $summary = [
            'totalProducts' => Product::where('is_active', true)->count(),
            'totalVariants' => ProductVariant::where('is_active', true)->count(),
            'totalUnitsInStock' => ProductVariant::where('is_active', true)->sum('stock_quantity'),
            'totalInventoryValue' => round(
                ProductVariant::where('is_active', true)
                    ->selectRaw('SUM(price * stock_quantity) as value')
                    ->value('value') ?? 0,
                2
            ),
            'lowStockCount' => $lowStockProducts->count(),
        ];

        return Inertia::render('products/Analytics', [
            'summary' => $summary,
            'stockStatus' => $stockStatusChart,
            'inventoryByBrand' => $inventoryByBrand,
            'stockByCategory' => $stockByCategory,
            'lowStockProducts' => $lowStockProducts,
        ]);
    }
}
