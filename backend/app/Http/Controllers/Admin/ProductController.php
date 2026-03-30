<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductStoreRequest;
use App\Http\Requests\Admin\ProductUpdateRequest;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Services\ProductService;
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
        $products = Product::latest()->where('is_active', 1)->with('brand', 'categories')
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
            'products' => $product,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        return Inertia::render('products/Edit', [
            'brands'     => Brand::select('id', 'name')->get(),
            'categories' => Category::select('id', 'name')->get(),
            'product'    => [
                ...$product->only('id', 'name', 'description', 'brand_id', 'is_active', 'is_featured', 'tags'),
                'category_ids' => $product->categories->pluck('id'),
                'variants'     => $product->variants->map->only('id', 'name', 'price', 'stock_quantity', 'is_active'),
                'thumbnail' => $product->getFirstMedia('thumbnail')
                    ? [
                        'id'  => $product->getFirstMedia('thumbnail')->id,
                        'url' => $product->getFirstMediaUrl('thumbnail', 'preview'),
                      ]
                    : null,
                'gallery'      => $product->getMedia('gallery')->map(fn($m) => ['id' => $m->id, 'url' => $m->getUrl()]),
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
}
