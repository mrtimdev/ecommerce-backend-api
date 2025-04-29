<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\CustomerOrder as Order;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Resources\Frontend\CustomerOrderResource;

class OrderReportController extends Controller
{
    public function index(Request $request)
    {
        if(!auth()->user()->hasRole(['owner', 'admin']) && !auth()->user()->hasPermission('orders')) {
            return inertia('Admin/Dashboard/Index', [
                'is_access_denied' => true,
                'message' => "<b>Access Denied:</b> You do not have the required permissions to access this feature."
            ]);
        }
        $users = User::where('type', 'frontend')->get();
        return Inertia::render('Admin/Clients/Orders/Index', [
            'user' => null,
            'users' => fn () => $users,
        ]);
    }
    public function getOrdersList(Request $request)
    {
        if ($request->ajax()) {
            $data = Order::with(['car', 'user'])->orderBy('id', 'desc')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->make(true);
        }
    }

    public function getOrderDetails(Request $request, Order $customerOrder)
    {
        return Inertia::render('Admin/Clients/Orders/OrderDetail', [
            'show_modal' => true,
            'order' => new CustomerOrderResource($customerOrder),
        ]);
    }
    public function updateStatus(Request $request, Order $order)
    {
        $order_status = $request->input('status');
        $car_status = "available";
        if($order_status === "delete") {
            if($order->car) {
                if($order->car->orders->count() > 1 && $order->car->status === "booked") {
                    $order->delete();
                    return to_route('orders.index');
                } 
                if($order->car->orders->count() === 1 && $order->car->status === "booked") {
                    $car_status = "booked";
                }
                if($order->car->orders->count() === 1 && $order->car->status === "available") {
                    $car_status = "available";
                    $order->delete();
                } 
                if($order->car->orders->count() === 1 && $order->car->status === "requesting") {
                    $car_status = "available";
                    $order->delete();
                } 
                $order->car->update(['status' => $car_status]);
            } else {
                $order->delete();
            }
            return to_route('orders.index');
        }
        if($order_status === "unapproved" OR $order_status === "unrejected") {
            $order_status = "pending";
        }

        $order->update(['status' => $order_status]);
        
        if($order_status === "approved") {
            $car_status = "booked";
        }
        if($order->car) {
            $order->car->update(['status' => $car_status]);
        }
        
        return to_route('orders.index');
    }

    public function order_by_user(Request $request, User $user)
    {
        if(!auth()->user()->hasRole(['owner', 'admin']) && !auth()->user()->hasPermission('orders')) {
            return inertia('Admin/Dashboard/Index', [
                'is_access_denied' => true,
                'message' => "<b>Access Denied:</b> You do not have the required permissions to access this feature."
            ]);
        }
        $users = User::where('type', 'frontend')->get();
        return Inertia::render('Admin/Clients/Orders/Index', [
            'user' => fn () => $user,
            'users' => fn () => $users,
        ]);
    }
    public function getOrdersListByUser(Request $request, User $user)
    {
        if ($request->ajax()) {
            $data = Order::with(['car', 'user'])->where('user_id', $user->id)->orderBy('id', 'desc')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->make(true);
        }
    }


    public function monthlyOrderReport()
    {
        // Use the Order model to perform the query and grouping.
        $data = Order::select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as orders_count'),
                DB::raw('SUM(price) as total_price')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get();
        $report = $data->map(function ($item) {
            $item->month_name = date("F", mktime(0, 0, 0, $item->month, 10));
            return $item;
        });

        return response()->json($report);
    }
}
