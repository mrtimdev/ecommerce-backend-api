<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $year = $request->input('year', date('Y'));
        $month = $request->input('month', date('m'));

        // Get users with car counts for chart and table
        $users = User::where('type', 'backend')
            ->withCount(['cars' => function ($query) use ($year, $month) {
                if ($year) $query->whereYear('created_at', $year);
                if ($month) $query->whereMonth('created_at', $month);
            }])
            ->having('cars_count', '>', 0) // Only show users with cars
            ->orderByDesc('cars_count')
            ->get();

        // Prepare chart data - top 10 users by car count
        $chartUsers = $users->take(10);

        // Get available years for filter dropdown
        $availableYears = DB::table('cars')
            ->select(DB::raw('YEAR(created_at) as year'))
            ->groupBy(DB::raw('YEAR(created_at)'))
            ->orderBy('year', 'desc')
            ->pluck('year');

        return Inertia::render('Admin/Reports/Users/Index', [
            'chartData' => [
                'labels' => $chartUsers->pluck('name'),
                'data' => $chartUsers->pluck('cars_count'),
            ],
            'users' => $users,
            'filters' => [
                'year' => $year,
                'month' => $month,
            ],
            'availableYears' => $availableYears,
        ]);
    }
}
