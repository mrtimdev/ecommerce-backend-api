<?php

namespace App\Http\Controllers;

use App\Models\Delivery;
use Illuminate\Http\Request;


class DeliveryController extends Controller
{
    public function create()
    {
        $clients = Client::with('stocks.product')->get();
        $drivers = Driver::all();
        $customers = Customer::all();

        return Inertia::render('Deliveries/Create', [
            'clients' => $clients,
            'drivers' => $drivers,
            'customers' => $customers
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'customer_id' => 'required|exists:customers,id',
            'driver_id' => 'required|exists:drivers,id',
            'items' => 'required|array',
            'items.*.stock_id' => 'required|exists:stocks,id',
            'items.*.quantity' => 'required|integer|min:1',
            'status' => 'required|in:pending,processing,completed,cancelled'
        ]);

        $delivery = Delivery::create([
            'client_id' => $validated['client_id'],
            'customer_id' => $validated['customer_id'],
            'driver_id' => $validated['driver_id'],
            'status' => $validated['status']
        ]);

        foreach ($validated['items'] as $item) {
            $delivery->items()->create([
                'stock_id' => $item['stock_id'],
                'quantity' => $item['quantity']
            ]);

            // Deduct from stock if delivery is completed
            if ($validated['status'] === 'completed') {
                Stock::where('id', $item['stock_id'])
                    ->decrement('quantity', $item['quantity']);
            }
        }

        return redirect()->route('deliveries.index')->with('success', 'Delivery created successfully');
    }
}
