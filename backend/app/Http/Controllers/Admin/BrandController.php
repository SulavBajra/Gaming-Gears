<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\StoreBrandRequest;
use App\Http\Requests\Admin\UpdateBrandRequest;
use App\Models\Brand;
use Illuminate\Support\Str;
use Inertia\Inertia;
use App\Http\Controllers\Controller;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $brands = Brand::with('media')->paginate(8);

        return Inertia::render('brands/Index', ['brands' => $brands]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('brands/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBrandRequest $request)
    {
        $brand = Brand::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'is_active' => $request->boolean('is_active', true),
        ]);

        if ($request->hasFile('logo')) {
            $brand
                ->addMediaFromRequest('logo')
                ->toMediaCollection('logo');
        }

        return to_route('brands.index')->with('success', 'Brand created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Brand $brand)
    {
        return Inertia::render('brands/Edit', ['brand' => $brand]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBrandRequest $request, Brand $brand)
    {
        $brand->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'is_active' => $request->boolean('is_active', true),
        ]);

        if ($request->hasFile('logo')) {
            $brand
                ->addMediaFromRequest('logo')
                ->toMediaCollection('logo');
        }

        return to_route('brands.index')->with('success', 'Brand Updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Brand::destroy($id);

        return to_route('brands.index');
    }
}
