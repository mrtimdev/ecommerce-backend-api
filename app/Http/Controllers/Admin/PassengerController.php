<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use App\Models\Passenger;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class PassengerController extends Controller
{
    public function index(Request $request)
    {
        return Inertia::render('Admin/Passengers/Index');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'no' => 'required|integer|max:100|unique:passengers,no',
        ]);

        Passenger::create($validated);
        if($request->is_save_and_more) {
            return redirect()->route('passengers.create');
        }
        return redirect()->route('passengers.index')->with('success', 'Car Category created successfully.');
    }

    public function update(Request $request, Passenger $passenger)
    {
        $validated = $request->validate([
            'no' => [
                'required',
                'integer',
                'max:100',
                Rule::unique('passengers', 'no')->ignore($passenger->id), 
            ],
        ]);
        $passenger->update($validated);
        return redirect()->route('passengers.index');
    }

    public function deleteSelected(Request $request)
     {
        $ids = $request->input('ids');
        Passenger::whereIn('id', $ids)->delete();  
        return redirect()->route('passengers.index');
    }
    public function getPassengers(Request $request)
    {
        if ($request->ajax()) {
            $data = Passenger::orderBy('id', 'desc')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->make(true);
        }
    }
}
