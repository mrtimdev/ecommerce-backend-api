<?php

namespace App\Http\Controllers\Admin;

use App\Models\Car;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\CustomerOrder;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $cars = Car::all();
        $ordes = CustomerOrder::all();
        $users = User::where('type', '=', 'backend')->get();
        $user_register = User::where('type', '=', 'client')->get();
        return inertia('Admin/Dashboard/Index', [
            'total_car' => fn() => $cars->count(),
            'total_order' => fn() => $ordes->count(),
            'total_user' => fn() => $users->count(),
            'total_register' => fn() => $user_register->count(),
        ]);
    }
}
