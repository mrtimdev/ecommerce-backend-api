<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Inertia\Inertia;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::with(['category', 'unit'])->paginate(10);

        return Inertia::render('Products/Index', [
            'products' => $products,
            // Or if you prefer separate props:
            'pagination' => [
                'links' => $products->links(),
                'meta' => [
                    'current_page' => $products->currentPage(),
                    'from' => $products->firstItem(),
                    'to' => $products->lastItem(),
                    'total' => $products->total(),
                    'per_page' => $products->perPage(),
                ]
            ]
        ]);
    }

    public function create()
    {
        return Inertia::render('Products/Create', [
            'categories' => Category::all(),
            'units' => Unit::all(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:20|unique:products',
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'unit_id' => 'required|exists:units,id',
            'price' => 'nullable|numeric|min:0',
            'cost' => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        Product::create($validated);

        return redirect()
            ->route('products.index')
            ->with('status', 'Product created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return Inertia::render('Products/Show', [
            'product' => $product->load(['category', 'unit']),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        return Inertia::render('Products/Edit', [
            'product' => $product,
            'categories' => Category::all(),
            'units' => Unit::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:20|unique:products,code,'.$product->id,
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'unit_id' => 'required|exists:units,id',
            'price' => 'nullable|numeric|min:0',
            'cost' => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        $product->update($validated);

        return redirect()
            ->route('products.index')
            ->with('status', 'Product updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()
            ->route('products.index')
            ->with('status', 'Product deleted successfully');
    }

    /**
     * Show product history
     */
    public function history(Product $product)
    {
        // Implement history logic if needed
        return Inertia::render('Products/History', [
            'product' => $product->load(['category', 'unit']),
        ]);
    }
}
