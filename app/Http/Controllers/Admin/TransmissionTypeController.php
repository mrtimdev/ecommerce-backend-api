<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use App\Models\TransmissionType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class TransmissionTypeController extends Controller
{
    public function index(Request $request)
    {
        return Inertia::render('Admin/TransmissionTypes/Index');
    }

    public function create()
    {
        return Inertia::render('Admin/TransmissionTypes/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:100|unique:transmission_types,code|regex:/^[^\s]+$/|max:100',
            'name' => 'required|string|max:191|unique:transmission_types,name',
            'is_active' => ['required', 'boolean'],
        ]);

        TransmissionType::create($validated);
        if($request->is_save_and_more) {
            return redirect()->route('transmissionTypes.create');
        }
        return redirect()->route('transmissionTypes.index')->with('success', 'Car Category created successfully.');
    }

    public function show(TransmissionType $TransmissionType)
    {
        return Inertia::render('Admin/TransmissionTypes/Show', ['category' => $TransmissionType]);
    }

    public function edit(TransmissionType $transmissionType)
    {
        return Inertia::render('Admin/TransmissionTypes/Edit', ['transmissionType' => $transmissionType]);
    }

    public function update(Request $request, TransmissionType $transmissionType)
    {
        $validated = $request->validate([
            'code' => [
                'required',
                'string',
                'max:100',
                'regex:/^[^\s]+$/',
                Rule::unique('transmission_types', 'code')->ignore($transmissionType->id), 
            ],
            'name' => [
                'required',
                'string',
                'max:191',
                Rule::unique('transmission_types', 'name')->ignore($transmissionType->id), 
            ],
            'is_active' => ['required', 'boolean'],
        ]);
        $transmissionType->update($validated);
        return redirect()->route('transmissionTypes.index');
    }

    public function deleteSelected(Request $request)
     {
        $ids = $request->input('ids');
        TransmissionType::whereIn('id', $ids)->delete();  
        return redirect()->route('transmissionTypes.index');
    }
    public function getTransmissionTypes(Request $request)
    {
        if ($request->ajax()) {
            $data = TransmissionType::orderBy('id', 'desc')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->make(true);
        }
    }
}
