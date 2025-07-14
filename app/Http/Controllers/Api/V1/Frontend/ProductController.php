<?php

namespace App\Http\Controllers\Api\V1\Frontend;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{


    public function index(Request $request)
    {
        $user = Auth::guard('api')->user();
        return Product::with(['category', 'unit'])
            ->where('client_id', $request->user()->id)
            ->latest()
            ->paginate(10);
    }

    public function store(Request $request)
    {
        $user = Auth::guard('api')->user();
        $validated = $request->validate([
            'code'        => [
                'required', 'string',
                Rule::unique('products')->where(fn($q) =>
                    $q->where('client_id', $user->id)
                ),
            ],
            'name'        => 'required|string|max:255',
            'price'       => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'unit_id'     => 'required|exists:units,id',
            'description' => 'nullable|string',
            'quantity'    => 'required|integer|min:0',
            'stock_alert' => 'nullable|integer|min:0',
            'is_active'   => 'boolean',
        ]);

        // merge in client_id from the authenticated user
        $validated['client_id'] = Auth::id();

        $product = Product::create($validated);

        return response()->json($product, 201);
    }

    public function show(Product $product)
    {
        // ensure they can only view their own product:
        $this->authorizeResource($product);
        return $product->load(['category', 'unit']);
    }

    public function update(Request $request, Product $product)
    {
        $this->authorizeResource($product);

        $validated = $request->validate([
            'code' => [
                'sometimes', 'string',
                Rule::unique('products')->ignore($product->id)
                    ->where(fn($q) => $q->where('client_id', Auth::id())),
            ],
            'name'        => 'sometimes|string|max:255',
            'price'       => 'sometimes|numeric|min:0',
            'category_id' => 'sometimes|exists:categories,id',
            'unit_id'     => 'sometimes|exists:units,id',
            'description' => 'nullable|string',
            'quantity'    => 'sometimes|integer|min:0',
            'stock_alert' => 'nullable|integer|min:0',
            'is_active'   => 'boolean',
        ]);

        $product->update($validated);

        return response()->json($product);
    }

    public function destroy(Product $product)
    {
        $this->authorizeResource($product);

        $product->delete();

        return response()->json(['message' => 'Product deleted']);
    }
}
