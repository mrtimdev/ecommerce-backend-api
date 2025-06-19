<?php

namespace Database\Seeders;

use App\Models\Unit;
use App\Models\Stock;

// database/seeders/SampleDataSeeder.php
use App\Models\Client;
use App\Models\Driver;
use App\Models\Product;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Delivery;
use App\Models\DeliveryItem;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SampleDataSeeder extends Seeder
{
    public function run()
    {



        // Create 5 clients
        $clients = Client::factory()->count(5)->create();

        // Create 10 products
        $products = Product::factory()->count(10)->create();

        // Create stocks for each client
        $clients->each(function ($client) use ($products) {
            $products->each(function ($product) use ($client) {
                Stock::factory()->create([
                    'client_id' => $client->id,
                    'product_id' => $product->id,
                    'price' => rand(10, 1000),
                    'quantity' => rand(10, 100),
                ]);
            });
        });

        // Create 5 drivers
        $drivers = Driver::factory()->count(5)->create();

        // Create customers for each client
        $clients->each(function ($client) {
            Customer::factory()->count(3)->create([
                'client_id' => $client->id
            ]);
        });

        // Create some deliveries
        $customers = Customer::all();

        foreach ($customers as $customer) {
            $delivery = Delivery::factory()->create([
                'client_id' => $customer->client_id,
                'customer_id' => $customer->id,
                'driver_id' => $drivers->random()->id,
                'status' => rand(0, 1) ? 'completed' : 'pending'
            ]);

            // Add items to delivery
            $stocks = Stock::where('client_id', $customer->client_id)
                ->inRandomOrder()
                ->limit(rand(1, 5))
                ->get();

            foreach ($stocks as $stock) {
                DeliveryItem::factory()->create([
                    'delivery_id' => $delivery->id,
                    'stock_id' => $stock->id,
                    'quantity' => rand(1, 10),
                    'price' => $stock->price
                ]);

                // If delivery is completed, deduct from stock
                if ($delivery->status === 'completed') {
                    $stock->decrement('quantity', rand(1, 10));
                }
            }
        }
    }
}
