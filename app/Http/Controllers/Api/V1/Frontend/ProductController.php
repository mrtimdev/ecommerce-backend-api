<?php

namespace App\Http\Controllers\Api\V1\Frontend;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{


    public function index(Request $request)
    {
        $user = Auth::guard('api')->user();
        return Product::with(['images','category', 'unit'])
            ->where('client_id', $user->id)
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
            'gallery_images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);



        // merge in client_id from the authenticated user
        $validated['client_id'] = $user->id;

        $product = Product::create($validated);
        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $image) {
                $galleryPath = $image->store('client/products/gallery', 'public');
                $product->images()->create(['image_path' => $galleryPath]);
            }
        }

        return response()->json($product, 201);
    }

    public function show(Product $product)
    {
        return $product->load(['category', 'unit']);
    }

    public function update(Request $request, Product $product)
    {
        $user = Auth::guard('api')->user();

        $request->validate([
            'code' => [
                'sometimes', 'string',
                Rule::unique('products')->ignore($product->id)
                    ->where(fn($q) => $q->where('client_id', $user->id)),
            ],
            'name'        => 'sometimes|string|max:255',
            'price'       => 'sometimes|numeric|min:0',
            'category_id' => 'sometimes|exists:categories,id',
            'unit_id'     => 'sometimes|exists:units,id',
            'description' => 'nullable|string',
            'quantity'    => 'sometimes|integer|min:0',
            'stock_alert' => 'nullable|integer|min:0',
            'is_active'   => 'boolean',
            'gallery_images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $product->update([
            'code'        => $request->code,
            'name'        => $request->name,
            'price'       => $request->price,
            'category_id' => $request->category_id,
            'unit_id'     => $request->unit_id,
            'description' => $request->description,
            'quantity'    => $request->quantity,
            'stock_alert' => $request->stock_alert,
            'is_active'   => $request->is_active,
        ]);

        // Handle gallery images if provided
        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $image) {
                $galleryPath = $image->store('client/products/gallery', 'public');

                // Assuming Product has a `images()` relationship
                $product->images()->create([
                    'image_path' => $galleryPath,
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'product' => $product->fresh('images'), // include fresh images relationship
        ]);
    }

    public function destroy(Product $product)
    {
        // Delete all associated images from storage and DB
        foreach ($product->images as $image) {
            // Delete file from storage
            Storage::disk('public')->delete($image->image_path);

            // Delete DB record
            $image->delete();
        }

        // Now delete the product itself
        $product->delete();

        return response()->json([
            'success' => true,
            'message' => 'Product and its gallery images deleted successfully.'
        ]);
    }
}
