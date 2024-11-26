<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CustomerOrder;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Resources\Frontend\CustomerOrderResource;

class OrderReportController extends Controller
{
    public function index(Request $request)
    {
        return Inertia::render('Admin/Reports/Orders/Index');
    }
    public function getOrdersList(Request $request)
    {
        if ($request->ajax()) {
            $data = CustomerOrder::with(['car'])->orderBy('id', 'desc')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->make(true);
        }
    }

    public function getOrderDetails(Request $request, CustomerOrder $customerOrder)
    {
        return Inertia::render('Admin/Reports/Orders/OrderDetail', [
            'show_modal' => true,
            'order' => new CustomerOrderResource($customerOrder)
        ]);
    }
    public function updateStatus(Request $request, CustomerOrder $order)
    {
        $order_status = $request->input('status');
        $car_status = "available";
        if($order_status === "delete") {
            if($order->car) {
                $order->car->update(['status' => $car_status]);
            }
            $order->delete();
            return to_route('orders.index');
        }
        if($order_status === "unapproved" OR $order_status === "unrejected") {
            $order_status = "pending";
        }
        $order->update(['status' => $order_status]);
        
        if($order->status === "approved") {
            $car_status = "booked";
        }
        if($order->car) {
            $order->car->update(['status' => $car_status]);
        }
        
        return to_route('orders.index');
    }
}
