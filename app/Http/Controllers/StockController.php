<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Stock;
use App\Models\Client;
use App\Models\Product;
use App\Models\StockMove;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockController extends Controller
{
     public function index(Request $request)
    {
        $filters = $request->only(['search', 'client_id', 'payment_status', 'stock_type']);

        $stocks = Stock::query()
            ->with(['client']) // Eager load client for display
            ->when($filters['search'] ?? null, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->whereHas('client', function ($q) use ($search) {
                        $q->where('name', 'like', '%' . $search . '%');
                    })
                    ->orWhere('note', 'like', '%' . $search . '%');
                });
            })
            ->when($filters['client_id'] ?? null, function ($query, $client_id) {
                $query->where('client_id', $client_id);
            })
            ->when($filters['payment_status'] ?? null, function ($query, $payment_status) {
                $query->where('payment_status', $payment_status);
            })
            ->when($filters['stock_type'] ?? null, function ($query, $stock_type) {
                $query->where('stock_type', $stock_type);
            })
            ->orderBy('date', 'desc') // Or another preferred order
            ->paginate(10) // Paginate with 10 items per page
            ->withQueryString(); // Keep query string parameters for pagination links

        // Fetch clients for the filter dropdown (if needed)
        $clients = Client::orderBy('name')->get(['id', 'name']);


        return Inertia::render('Stocks/Index', [
            'stocks' => $stocks,
            'filters' => $filters,
            'clients' => $clients, // Pass clients for the filter dropdown
        ]);
    }

    // You'll also need a batch delete method if you want the bulk delete to work
    public function destroyBatch(Request $request)
    {
        $request->validate(['ids' => 'required|array', 'ids.*' => 'exists:stocks,id']);

        Stock::whereIn('id', $request->ids)->delete();

        return redirect()->back()->with('success', 'Selected stock entries deleted successfully.');
    }

    public function create()
    {
        $clients = Client::all();
        $products = Product::with(['unit', 'category'])->get();
        return Inertia::render('Stocks/Create', [
            'clients' => $clients,
            'products' => $products
        ]);
    }



    public function store(Request $request)
    {
        // 1. Validate the main stock entry data
        $validatedStockData = $request->validate([
            'client_id' => ['required', 'exists:clients,id'],
            'date' => ['required', 'date'],
            'payment_status' => ['required', 'in:unpaid,paid,partial'],
            'note' => ['nullable', 'string', 'max:1000'],
            'items' => ['required', 'array', 'min:1'],
            'initial_payment_amount' => ['nullable', 'numeric', 'min:0'],
            'payment_method' => ['nullable', 'string', 'max:255'],
        ]);

        // 2. Validate each item in the 'items' array
        $itemValidationRules = [
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
            'price' => ['required', 'numeric', 'min:0'],
        ];

        $validatedItems = [];
        $totalAmount = 0;

        foreach ($validatedStockData['items'] as $index => $item) {
            try {
                $validatedItem = validator($item, $itemValidationRules)->validate();
                $validatedItems[] = $validatedItem;
                $totalAmount += ($validatedItem['quantity'] * $validatedItem['price']);
            } catch (ValidationException $e) {
                return redirect()->back()->withErrors([
                    "items.{$index}.product_id" => $e->errors()['product_id'][0] ?? null,
                    "items.{$index}.quantity" => $e->errors()['quantity'][0] ?? null,
                    "items.{$index}.price" => $e->errors()['price'][0] ?? null,
                ])->withInput();
            }
        }

        // Determine initial paid amount for the stock record
        $initialPaidAmount = (float)($request->initial_payment_amount ?? 0);
        if ($initialPaidAmount > $totalAmount) {
             return redirect()->back()->withErrors(['initial_payment_amount' => 'Initial payment cannot exceed the total amount.'])->withInput();
        }

        // Determine payment status based on initial payment
        $paymentStatus = 'unpaid';
        if ($initialPaidAmount > 0 && $initialPaidAmount < $totalAmount) {
            $paymentStatus = 'partial';
        } elseif ($initialPaidAmount >= $totalAmount && $totalAmount > 0) {
            $paymentStatus = 'paid';
        } elseif ($totalAmount == 0 && $initialPaidAmount == 0) {
            $paymentStatus = 'unpaid'; // Or 'paid' if 0 total means paid by default
        }


        DB::transaction(function () use ($validatedStockData, $validatedItems, $totalAmount, $initialPaidAmount, $paymentStatus) {
            // 3. Create the main stock entry
            $stock = Stock::create([
                'client_id' => $validatedStockData['client_id'],
                'date' => $validatedStockData['date'],
                'payment_status' => $paymentStatus,
                'note' => $validatedStockData['note'],
                'total_amount' => $totalAmount,
                'paid_amount' => $initialPaidAmount,
            ]);

            // 4. Attach each validated item to the created stock entry
            foreach ($validatedItems as $item) {
                // Ensure the unit_id is available. You might need to load the product.
                $product = Product::find($item['product_id']);
                if (!$product) {
                    throw ValidationException::withMessages(['product_id' => 'Product not found.']);
                }

                $stockItem = $stock->items()->create($item);

                // 5. Create an FtdStockMove entry for each stock item
                $ftdQuantity = $item['quantity'];
                StockMove::create([
                    'product_id' => $item['product_id'],
                    'stock_id' => $stock->id,
                    'quantity' => $ftdQuantity,
                    'unit_id' => $product->unit_id, // Assuming product has a unit_id
                    'price' => $item['price'],
                ]);
            }

            // 6. Create an initial payment record if an amount was provided
            if ($initialPaidAmount > 0) {
                $stock->payments()->create([
                    'amount' => $initialPaidAmount,
                    'payment_date' => $validatedStockData['date'],
                    'payment_method' => $validatedStockData['payment_method'] ?? 'N/A',
                    'note' => 'Initial payment for stock entry',
                ]);
            }
        }); // End of DB::transaction

        return redirect()->route('stocks.index')->with('success', 'Stock entry added successfully!');
    }
}
