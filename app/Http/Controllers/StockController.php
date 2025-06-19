<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Stock;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index()
    {
        $stocks = Stock::with(['client', 'product'])->get();
        return Inertia::render('Stocks/Index', ['stocks' => $stocks]);
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
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            // Add other validation rules
        ]);

        Stock::create($validated);

        return redirect()->route('stocks.index')->with('success', 'Stock added successfully');
    }
}
