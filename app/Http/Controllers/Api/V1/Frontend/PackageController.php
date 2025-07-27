<?php

namespace App\Http\Controllers\Api\V1\Frontend;

use Storage;
use App\Models\Package;
use App\Models\Product;
use App\Models\PackageItem;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\PackageResource;
use App\Http\Resources\PartialProductResource;

class PackageController extends Controller
{

    public function index()
    {
        $client = Auth::guard('api')->user();
        $packages = Package::with(['customer', 'items'])
            ->where('client_id', $client->id)
            ->latest()
            ->paginate(15);
        return PackageResource::collection($packages);
    }

    public function loadProducts(Request $request)
    {
        $client = Auth::guard('api')->user();

        $query = Product::with(['category', 'unit', 'images'])
            ->where('client_id', $client->id);

        // Optional: filter by is_active
        if ($request->has('is_active')) {
            $query->where('is_active', filter_var($request->is_active, FILTER_VALIDATE_BOOLEAN));
        }

        // Optional: global search filter
        if ($request->filled('filter')) {
            $search = $request->filter;

            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                ->orWhere('name', 'like', "%{$search}%")
                ->orWhereHas('category', function ($category) use ($search) {
                    $category->where('name', 'like', "%{$search}%");
                })
                ->orWhereHas('unit', function ($unit) use ($search) {
                    $unit->where('name', 'like', "%{$search}%");
                });
            });
        }

        $products = $query->get();

        return PartialProductResource::collection($products);
    }



    public function show($id)
    {
        $package = Package::with('items.product', 'client', 'customer')->findOrFail($id);
        return response()->json($package);
    }

    public function store(Request $request)
    {
        $user = Auth::guard('api')->user();
        $data = $request->validate([
            'date' => 'required|date',
            'reference_no' => [
                'required', 'string', 'max:50',
                Rule::unique('packages')
                    ->where(fn($q) => $q->where('client_id', $user->id)),
            ],
            'customer_id' => 'required|integer',
            'address' => 'required|string',
            'note' => 'nullable|string',
            'status' => 'required|string',
            'image_path' => 'nullable|file|image|max:2048',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required_with:items|integer|min:1',
            'items.*.unit_price' => 'required_with:items|numeric|min:0',
            'items.*.real_unit_price' => 'nullable|numeric|min:0',
            'items.*.note' => 'nullable|string',
        ]);

        $data['status'] = 'pending';
        $data['delivery_status'] = 'pending';
        $data['client_id'] = $user->id;

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $path = $file->store('packages', 'public');
            $data['image_path'] = $path;
        }

        $items = [];
        foreach ($data['items'] as $item) {
            $product = Product::findOrFail($item['product_id']);
            $items[] = [
                'package_id' => null,
                'product_id' => $item['product_id'],
                'unit_id' => $product->unit_id,
                'quantity' => $item['quantity'],
                'real_unit_price' => $item['real_unit_price'],
                'unit_price' => $item['unit_price'],
                'subtotal' => $item['unit_price'] * $item['quantity'],
                'note' => $item['note'] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        $package = Package::create(collect($data)->except('items')->toArray());
        foreach ($items as &$item) {
            $item['package_id'] = $package->id;
        }
        PackageItem::insert($items);

        return response()->json(['success' => true, 'message' => 'Package created successfully'], 201);
    }

    public function update(Request $request, $id)
    {
        $package = Package::findOrFail($id);
        $user = Auth::guard('api')->user();

        if ($package->status !== 'pending' || $package->delivery_status !== 'pending') {
            return response()->json(['message' => 'Cannot update a non-pending package'], 403);
        }

        $data = $request->validate([
            'date' => 'required|date',
            'reference_no' => [
                'required', 'string', 'max:50',
                Rule::unique('packages')->ignore($package->id)
                    ->where(fn($q) => $q->where('client_id', $user->id)),
            ],
            'customer_id' => 'sometimes|integer',
            'address' => 'sometimes|string',
            'note' => 'nullable|string',
            'image_path' => 'nullable|file|image|max:2048',
            'status' => 'sometimes|string',
            'delivery_status' => 'sometimes|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required_with:items|integer|min:1',
            'items.*.unit_price' => 'required_with:items|numeric|min:0',
            'items.*.real_unit_price' => 'nullable|numeric|min:0',
            'items.*.note' => 'nullable|string',
        ]);

        $data['status'] =  $request->status;
        $data['delivery_status'] =  $request->delivery_status;

        if ($request->hasFile('image')) {
            // Delete old image if it exists
            if ($package->image_path && Storage::disk('public')->exists($package->image_path)) {
                Storage::disk('public')->delete($package->image_path);
            }

            $file = $request->file('image');
            $path = $file->store('packages', 'public');
        }

        $package->update(collect($data)->except('items')->toArray());

        PackageItem::where('package_id', $package->id)->delete();


        $items = [];
        foreach ($data['items'] as $item) {
            $product = Product::findOrFail($item['product_id']);
            $items[] = [
                'package_id' => $package->id,
                'product_id' => $item['product_id'],
                'unit_id' => $product->unit_id,
                'quantity' => $item['quantity'],
                'real_unit_price' => $item['real_unit_price'] ?? $item['unit_price'],
                'unit_price' => $item['unit_price'],
                'subtotal' => $item['unit_price'] * $item['quantity'],
                'note' => $item['note'] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        PackageItem::insert($items);

        return response()->json([
            'success' => true,
            'message' => 'Package updated successfully',
        ]);
    }


    public function destroy($id)
    {
        $client = Auth::guard('api')->user();
        $package = Package::where('client_id', $client->id)
                   ->findOrFail($id);
        if ($package->image_path && Storage::disk('public')->exists($package->image_path)) {
            Storage::disk('public')->delete($package->image_path);
        }
        $package->items()->delete();
        $package->delete();

        return response()->json(['success' => true, 'message' => 'Package deleted successfully']);
    }

    public function getByClient(Request $request, $clientId)
    {
        $search = $request->query('query');

        $packages = Package::where('client_id', $clientId)
            ->when($search, function($query) use ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('reference_no', 'like', "%{$search}%")
                    ->orWhere('date', 'like', "%{$search}%")
                    ->orWhereRaw("CONCAT(reference_no, ' ', date, ' ') LIKE ? ", ["%{$search}%"]);
                });
            })
            ->limit(10)
            ->get(['id', 'reference_no', 'date',]);

        return response()->json(
            $packages->map(function($package) {
                return [
                    'id' => $package->id,
                    'name' => "#{$package->reference_no} - ({$package->date})"
                ];
            })
        );
    }
}
