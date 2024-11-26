<?php

namespace App\Http\Controllers\Api\V1\Frontend;

use App\Models\Car;
use Illuminate\Http\Request;
use App\Models\CustomerOrder;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Frontend\CustomerOrderResource;

class CustomerOrderController extends Controller
{
    public function index()
    {
        $user = Auth::guard('api')->user();
        $query = CustomerOrder::query();
        if ($user) {
            $query->where(function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        }
        $orders = $query->get();
        return CustomerOrderResource::collection($orders);
    }
    public function show($id)
    {
        $order = CustomerOrder::findOrFail($id);
        return new CustomerOrderResource($order);
    }
    public function showByEmail(Request $request, $email)
    {
        $orders = CustomerOrder::with('orderDetails')->where('email', $email)->get();
        return CustomerOrderResource::collection($orders);
    }
    public function showByPhone(Request $request, $phone)
    {
        $orders = CustomerOrder::with('orderDetails')->where('phone', $phone)->get();
        return CustomerOrderResource::collection($orders);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'nullable|string|max:100',
            'email' => 'nullable|email|max:100',
            'phone' => 'nullable|string|max:15',
            'telegram_or_phone' => 'required|string|max:100',
            'detail' => 'nullable|string',
            'price' => 'required|numeric|min:0|max:999999.99',
            'location' => 'required|string',
            'item_code' => 'required|string|max:50',
            'link' => 'required|url',
            'link_korea' => 'nullable|url',
        ]);
    
        $user = Auth::guard('api')->user();
        $car = Car::where('code', $request->item_code)->first();
        if($car) {
            $car->update(['status' => 'booked']);
        }
        CustomerOrder::create($validated + ['user_id' => $user ? $user->id : null, 'car_id' => $car ? $car->id : null]);
        return response()->json(['status' => 'success'], 201);
    }

    public function update(Request $request, $id)
    {
        $order = CustomerOrder::find($id);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'full_name' => 'sometimes|required|string|max:100',
            'email' => 'sometimes|required|email|max:100',
            'phone' => 'sometimes|required|string|max:15',
            'detail' => 'sometimes|nullable|string',
            'order_details.*.item_code' => 'required|string|max:50',
            'order_details.*.link' => 'required|string|url',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $order->update($request->only('user_id', 'full_name', 'email', 'phone', 'detail'));

        // Update order details
        if ($request->order_details) {
            $order->orderDetails()->delete(); // Delete old details
            foreach ($request->order_details as $detail) {
                $order->orderDetails()->create($detail);
            }
        }

        return response()->json($order->load('orderDetails'));
    }

    // Delete a customer order
    public function destroy($id)
    {
        $order = CustomerOrder::find($id);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        $order->delete();

        return response()->json(['message' => 'Order deleted successfully'], 200);
    }

    public function truncateOrders()
    {
        DB::table('customer_orders')->truncate();
        DB::table('customer_order_details')->truncate();
        return response()->json(['message' => 'Data truncated successfully.']);
    }
}
