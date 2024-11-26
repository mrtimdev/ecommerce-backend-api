<?php

namespace App\Http\Controllers\Admin;

use Storage;
use Inertia\Inertia;
use App\Models\HotMark;
use App\Models\Option;
use App\Models\OptionGroup;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class OtherOptionController extends Controller
{
    public function hotmarkIndex()
    {
        $hotmarks = HotMark::all();

        return Inertia::render('Admin/OtherOptions/Pages/Hotmarks/Index', [
            'hotmarks' => $hotmarks,
        ]);
    }
    public function hotmarkStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:191|unique:hot_marks,name',
        ]);
        Hotmark::create([
            'name' => $request->name,
        ]);

        return redirect()->route('hotmarks.index');
    }

    public function hotmarkUpdate(Request $request, Hotmark $hotmark)
    {
        $request->validate([
           'name' => [
                'required',
                'string',
                'max:191',
                Rule::unique('hot_marks', 'name')->ignore($hotmark->id), 
            ],
        ]);
        $hotmark->update([
            'name' => $request->name,
        ]);

        return redirect()->route('hotmarks.index')
            ->with('success', 'Hotmark updated successfully.');
    }

    public function deleteSelectedHotmarks(Request $request)
    {
        $ids = $request->input('ids');
        $hotmarks = Hotmark::whereIn('id', $ids)->get();
        Hotmark::whereIn('id', $ids)->delete();
        return redirect()->route('hotmarks.index');
        
    }
    public function getHotMarks(Request $request)
    {
        if ($request->ajax()) {
            $data = Hotmark::orderBy('id', 'desc')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->make(true);
        }
    }

    # options

    public function optionIndex()
    {
        $options = Option::all();
        $optionGroups = OptionGroup::all();
        return Inertia::render('Admin/OtherOptions/Pages/Options/Index', [
            'options' => $options,
            'optionGroups' => $optionGroups,
        ]);
    }
    public function optionStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:191|unique:options,name',
            'option_group' => 'required|array',
            'option_group.id' => 'required|integer|exists:option_groups,id',
        ]);
        Option::create([
            'name' => $request->name,
            'option_group_id' => $request->input('option_group.id'),
        ]);
        return redirect()->route('options.index');
    }

    public function optionUpdate(Request $request, Option $option)
    {
        $request->validate([
           'name' => [
                'required',
                'string',
                'max:191',
                Rule::unique('options', 'name')->ignore($option->id), 
            ],
            'option_group' => 'required|array',
            'option_group.id' => 'required|integer|exists:option_groups,id',
        ]);
        $option->update([
            'name' => $request->name,
            'option_group_id' => $request->input('option_group.id'),
        ]);

        return redirect()->route('options.index')
            ->with('success', 'option updated successfully.');
    }

    public function deleteSelectedoptions(Request $request)
    {
        $ids = $request->input('ids');
        $options = Option::whereIn('id', $ids)->get();
        Option::whereIn('id', $ids)->delete();
        return redirect()->route('options.index');
        
    }
    public function getoptions(Request $request)
    {
        if ($request->ajax()) {
            $data = Option::with(['optionGroup'])->orderBy('id', 'desc')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->make(true);
        }
    }

    # option items

    public function optionGroupIndex()
    {
        $optionGroups = OptionGroup::all();

        return Inertia::render('Admin/OtherOptions/Pages/OptionGroups/Index', [
            'optionGroups' => $optionGroups,
        ]);
    }
    public function optionGroupStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:191|unique:option_groups,name',
        ]);
        OptionGroup::create([
            'name' => $request->name,
        ]);

        return redirect()->route('optionGroups.index');
    }

    public function optionGroupUpdate(Request $request, OptionGroup $optionGroup)
    {
        $request->validate([
           'name' => [
                'required',
                'string',
                'max:191',
                Rule::unique('option_groups', 'name')->ignore($optionGroup->id), 
            ],
        ]);
        $optionGroup->update([
            'name' => $request->name,
        ]);

        return redirect()->route('optionGroups.index')
            ->with('success', 'optionGroup updated successfully.');
    }

    public function deleteSelectedOptionGroups(Request $request)
    {
        $ids = $request->input('ids');
        $optionGroups = OptionGroup::whereIn('id', $ids)->get();
        OptionGroup::whereIn('id', $ids)->delete();
        return redirect()->route('optionGroups.index');
        
    }
    public function getoptionGroups(Request $request)
    {
        if ($request->ajax()) {
            $data = OptionGroup::orderBy('id', 'desc')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->make(true);
        }
    }
}
