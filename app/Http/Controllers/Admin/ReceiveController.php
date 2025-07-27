<?php

namespace App\Http\Controllers\Admin;

use App\Models\Receive;
use App\Models\User as Client;
use App\Models\Package;
use App\Models\ReceiveItem;
use App\Models\PackageItem;
use App\Models\Stock;
use App\Models\StockMove;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ReceiveController extends Controller
{
    // GET /receives
    public function index()
    {
        $receives = Receive::with('client', 'package')->latest()->paginate(15);
        return Inertia::render('Admin/Receives/Index', compact('receives'));
    }

    // GET /admin/receives/create
    public function create()
    {
        $clients = Client::where('type', '=', 'client')->get();

        return Inertia::render('Admin/Receives/Create', [
            'clients' => $clients,
        ]);
    }

    public function getClientPackages($clientId)
    {
        // Fetch packages for the client where status != 'received'
        $packages = Package::where('status', '!=', 'received')
            ->where('client_id', $clientId)
            ->with(['items' => function ($query) {
                $query->with('receiveItems');
            }])
            ->get();

        // Filter items with balance_quantity > 0
        $filteredPackages = $packages->map(function ($package) {
            $package->items = $package->items->map(function ($item) {
                $receivedQty = $item->receiveItems->sum('quantity');
                $item->balance_quantity = $item->quantity - $receivedQty;
                $item->status = $item->status;
                return $item;
            })->filter(function ($item) {
                return $item->balance_quantity > 0;
            })->values();

            return $package;
        })->filter(function ($package) {
            return $package->items->isNotEmpty();
        })->values();

        return response()->json($filteredPackages);
    }


    public function getPackageItems($packageId)
    {
        $package = Package::with(['items' => function($query) {
            $query->with('receiveItems');
        }])->findOrFail($packageId);

        // Filter items with balance_quantity > 0
        $package->items = $package->items->map(function ($item) {
            $receivedQty = $item->receiveItems->sum('quantity');
            $item->balance_quantity = $item->quantity - $receivedQty;
            $item->status = $this->calculateItemStatus($item->quantity, $receivedQty);
            return $item;
        })->filter(function ($item) {
            return $item->balance_quantity > 0;
        })->values();

        return response()->json([
            'package' => $package,
            'items' => $package->items
        ]);
    }
    protected function calculateItemStatus($totalQuantity, $receivedQuantity)
    {
        if ($receivedQuantity <= 0) {
            return 'pending';
        } elseif ($receivedQuantity < $totalQuantity) {
            return 'partial';
        } else {
            return 'completed';
        }
    }





    // POST /receives
    public function store(Request $request)
    {
        $request->validate([
            'client_id' => 'required|exists:users,id',
            'package_id' => 'required|exists:packages,id',
            'date' => 'required|date',
            'note' => 'nullable|string',
            'items' => 'required|array',
            'items.*.package_item_id' => 'required|exists:package_items,id',
            'items.*.quantity' => 'required|numeric|min:0.01',
        ]);

        DB::transaction(function () use ($request) {
            $receive = Receive::create([
                'client_id' => $request->client_id,
                'package_id' => $request->package_id,
                'received_by' => auth()->id(),
                'date' => $request->date,
                'note' => $request->note,
            ]);

            foreach ($request->items as $item) {
                $packageItem = PackageItem::find($item['package_item_id']);

                // 1. Save receive item
                $receiveItem = ReceiveItem::create([
                    'receive_id' => $receive->id,
                    'package_item_id' => $packageItem->id,
                    'quantity' => $item['quantity'],
                ]);

                // 2. Update client stock
                $stock = Stock::firstOrCreate([
                    'product_id' => $packageItem->product_id,
                    'owner_type' => 'client',
                    'owner_id' => $request->client_id,
                    'unit_id' => $packageItem->unit_id,
                ], ['quantity' => 0]);

                $stock->increment('quantity', $item['quantity']);

                // 3. Track stock move
                StockMove::create([
                    'date' => $request->date,
                    'product_id' => $packageItem->product_id,
                    'package_id' => $request->package_id,
                    'stock_id' => $stock->id,
                    'client_id' => $request->client_id,
                    'user_id' => auth()->id(),
                    'quantity' => $item['quantity'],
                    'unit_id' => $packageItem->unit_id,
                    'price' => $packageItem->unit_price,
                    'type' => 'in',
                    'move_type' => 'receive',
                    'owner_type' => 'admin',
                ]);
            }
        });

        return redirect()->route('receives.index')->with('success', 'Receive recorded successfully.');
    }

    // GET /admin/receives/{receive}
    public function show(Receive $receive)
    {
        $receive->load('client', 'package', 'receiveItems.packageItem');
        return Inertia::render('Admin/Receives/Show', ['receive' => $receive]);
    }

    // DELETE /admin/receives/{receive}
    public function destroy(Receive $receive)
    {
        $receive->delete();
        return redirect()->route('receives.index')->with('success', 'Receive deleted.');
    }
}
