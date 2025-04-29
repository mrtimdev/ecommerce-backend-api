<?php

namespace App\Http\Controllers\Admin;

use Storage;
use Inertia\Inertia;
use App\Models\Model;
use App\Models\Option;
use App\Models\HotMark;
use App\Models\OptionGroup;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class OtherOptionController extends Controller
{
    public function hotmarkIndex()
    {
        if(!auth()->user()->hasRole(['owner', 'admin']) && !auth()->user()->hasPermission('cars-hot-marks')) {
            return inertia('Admin/Dashboard/Index', [
                'is_access_denied' => true,
                'message' => "<b>Access Denied:</b> You do not have the required permissions to access this feature."
            ]);
        }
        $hotmarks = HotMark::all();
        return Inertia::render('Admin/OtherOptions/Pages/Hotmarks/Index', [
            'hotmarks' => $hotmarks,
        ]);
    }
    public function hotmarkStore(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:100|unique:hot_marks,code',
            'name' => 'required|string|max:191|unique:hot_marks,name',
        ]);
        Hotmark::create([
            'code' => $request->code,
            'name' => $request->name,
        ]);

        return redirect()->route('hotmarks.index');
    }

    public function hotmarkUpdate(Request $request, Hotmark $hotmark)
    {
        $request->validate([
            'code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('hot_marks', 'code')->ignore($hotmark->id), 
            ],
           'name' => [
                'required',
                'string',
                'max:191',
                Rule::unique('hot_marks', 'name')->ignore($hotmark->id), 
            ],
        ]);
        $hotmark->update([
            'code' => $request->code,
            'name' => $request->name,
        ]);

        return redirect()->route('hotmarks.index');
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
        if(!auth()->user()->hasRole(['owner', 'admin']) && !auth()->user()->hasPermission('cars-options')) {
            return inertia('Admin/Dashboard/Index', [
                'is_access_denied' => true,
                'message' => "<b>Access Denied:</b> You do not have the required permissions to access this feature."
            ]);
        }
        $options = Option::all();
        $optionGroups = OptionGroup::all();
        $models = Model::all();
        return Inertia::render('Admin/OtherOptions/Pages/Options/Index', [
            'options' => fn () => $options,
            'optionGroups' => fn () => $optionGroups,
            'models' => fn () => $models,
        ]);
    }
    public function optionStore(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:100|unique:options,code',
            'name' => 'required|string|max:191|unique:options,name',
            'option_group' => 'required|array',
            'option_group.id' => 'required|integer|exists:option_groups,id',
            'models' => 'array',
            'models.*.id' => 'integer|exists:models,id',
        ]);
        $option = Option::create([
            'code' => $request->code,
            'name' => $request->name,
            'option_group_id' => $request->input('option_group.id'),
        ]);
        if ($request->has('models')) {
            $option->models()->attach(collect($request->input('models'))->pluck('id'));
        }
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
            'code' => [
                'required',
                'string',
                'max:100',
                'regex:/^[^\s]+$/',
                Rule::unique('options', 'code')->ignore($option->id), 
            ],
            'option_group' => 'required|array',
            'option_group.id' => 'required|integer|exists:option_groups,id',
            'models' => 'array',
            'models.*.id' => 'integer|exists:models,id',
        ]);
        $option->update([
            'code' => $request->code,
            'name' => $request->name,
            'option_group_id' => $request->input('option_group.id'),
        ]);
        if ($request->has('models')) {
            $option->models()->sync(collect($request->input('models'))->pluck('id'));
        }
        return redirect()->route('options.index')
            ->with('success', 'option updated successfully.');
    }

    public function deleteSelectedoptions(Request $request)
    {
        $ids = $request->input('ids');
        $options = Option::whereIn('id', $ids)->get();
        foreach($options as $option) {
            $option->options()->detach();
            $option->delete();
        }
        return redirect()->route('options.index');
    }
    public function getOptions(Request $request)
    {
        if ($request->ajax()) {
            $data = Option::with(['optionGroup', 'models'])->orderBy('id', 'desc')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('models_name', function ($row) {
                    return $row->models->pluck('name')->implode(', ');
                })
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
    public function getOptionGroups(Request $request)
    {
        if ($request->ajax()) {
            $data = OptionGroup::orderBy('id', 'desc')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->make(true);
        }
    }

    public function getGroupOptions()
    {
        $group_options = Option::getGroupedOptions();
        return response()->json([
            'group_options' => $group_options
        ]);
    }
}
