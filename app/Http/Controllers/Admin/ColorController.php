<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use App\Models\Color;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class ColorController extends Controller
{
    public function index(Request $request)
    {
        return Inertia::render('Admin/Colors/Index');
    }

    public function create()
    {
        return Inertia::render('Admin/Colors/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => [
                'required',
                'string',
                'max:50',
                'unique:colors,code',
            ],
            'hex' => [
                'required',
                'string',
                'max:50',
                'unique:colors,hex',
            ],
            'name' => 'required|string|max:191|',
            'description' => 'nullable',
        ]);

        Color::create($validated);
        if($request->is_save_and_more) {
            return redirect()->route('colors.create');
        }
        return redirect()->route('colors.index')->with('success', 'Car color created successfully.');
    }

    public function show(Color $Color)
    {
        return Inertia::render('Admin/Colors/Show', ['color' => $Color]);
    }

    public function edit(Color $color)
    {
        return Inertia::render('Admin/Colors/Edit', ['color' => $color]);
    }

    public function update(Request $request, Color $color)
    {
        $validated = $request->validate([
            'code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('colors', 'code')->ignore($color->id), 
            ],
            'hex' => [
                'required',
                'string',
                'max:50',
                Rule::unique('colors', 'hex')->ignore($color->id), 
            ],
            'name' => 'required|string|max:191|',
            'description' => 'nullable',
        ]);
        $color->update($validated);
        return redirect()->route('colors.index');
    }

    public function deleteSelected(Request $request)
     {
        $ids = $request->input('ids');
        Color::whereIn('id', $ids)->delete();  
        return redirect()->route('colors.index');
    }
    public function getColors(Request $request)
    {
        if ($request->ajax()) {
            $data = Color::orderBy('id', 'desc')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->make(true);
        }
    }
}
