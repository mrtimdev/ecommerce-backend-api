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
    public function khmerOrder(Request $request)
    {
        $sortField = $request->input('sort_field', 'id');
        $sortOrder = $request->input('sort_order', 'asc');
        $perPage = $request->input('per_page', 10);
        $user = Auth::guard('api')->user();
        $query = CustomerOrder::query();
        if ($user) {
            $query->where(function ($q) use ($user) {
                $q->where('user_id', $user->id);
                $q->where('order_type', 'book');
            });
        }
        $orders = $query->orderBy($sortField, $sortOrder)->paginate($perPage);
        return CustomerOrderResource::collection($orders);
    }
    public function KoreaOrder(Request $request)
    {
        $sortField = $request->input('sort_field', 'id');
        $sortOrder = $request->input('sort_order', 'asc');
        $perPage = $request->input('per_page', 10);
        $user = Auth::guard('api')->user();
        $query = CustomerOrder::query();
        if ($user) {
            $query->where(function ($q) use ($user) {
                $q->where('user_id', $user->id);
                $q->where('order_type', 'quote');
            });
        }
        $orders = $query->orderBy($sortField, $sortOrder)->paginate($perPage);
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
        $request->validate([
            'detail' => 'nullable|string',
            'price' => 'required|numeric|min:0|max:999999.99',
            'location' => 'required|string',
            'item_code' => 'required|string|max:50',
            'item_name' => 'nullable|string',
            'link' => 'required|url',
            'link_korea' => 'nullable|url',
            'order_type' => 'required|string|in:book,quote',
        ]);
    
        $user = Auth::guard('api')->user();
        $car = Car::where('code', $request->item_code)->first();
        $type = "khmer";
        $existingOrder = CustomerOrder::where('item_code', $request->item_code)->where('user_id', $user->id)->first();
        if ($existingOrder) {
            return response()->json([
                'message' => 'You have already requested this car.',
            ], 403);
        }
        if($car) {
            if ($car->status === "booked") {
                return response()->json([
                    'message' => 'Sorry! this car has been sold out.',
                ], 403);
            }
            $car->update(['status' => 'requesting']);
        } else {
            $type = "korea";
        }



        $data = [
            'link_korea' => $request->link_korea,
            'link' => $request->link,
            'location' => $request->location,
            'item_code' => $request->item_code,
            'item_name' => $request->item_name,
            'price' => $request->price,
            'detail' => $request->detail,
            'user_id' => $user ? $user->id : null,
            'car_id' => $car ? $car->id : null,
            'type' => $type,
            'order_type' => $request->order_type
        ];
        CustomerOrder::create($data);
        return response()->json(['status' => 'success', 'message' => 'Great, your request has been successfully'], 201);
    }

    // 20|8VA9sr3NQGhcj4jyLpvRLhZ6LYihZZGKvgTmg1qzcb5aa9a7

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
