<?php

namespace App\Http\Controllers\Backend\Product;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class UnitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        abort_if(!auth()->user()->can('unit_view'), 403);
        if ($request->ajax()) {
            $units = Unit::latest()->get();
            return DataTables::of($units)
                ->addIndexColumn()
                ->addColumn('title', fn($data) => $data->title)
                ->addColumn('short_name', fn($data) => $data->short_name)
                   ->addColumn('status', function ($data) {
                    $toggleUrl = route('backend.admin.units.toggle', $data->id); 
                    $status = $data->status
                        ? '<span class="badge bg-primary">Active</span>'
                        : '<span class="badge bg-danger">Inactive</span>';
                    return '<button class="btn btn-sm btn-light toggle-status" data-url="' . $toggleUrl . '">' . $status . '</button>';
                })
               ->addColumn('action', function ($data) {
                    return '<div class="btn-group">
                    <button type="button" class="btn bg-gradient-primary btn-flat">Action</button>
                    <button type="button" class="btn bg-gradient-primary btn-flat dropdown-toggle dropdown-icon" data-toggle="dropdown" aria-expanded="false">
                      <span class="sr-only">Toggle Dropdown</span>
                    </button>
                    <div class="dropdown-menu" role="menu">
                      <a class="dropdown-item" href="' . route('backend.admin.units.edit', $data->id) . '" ' .' >
                    <i class="fas fa-edit"></i> Edit
                  </div>';
                })
                ->rawColumns(['title', 'short_name',  'status', 'action'])
                ->toJson();
        }
        return view('backend.units.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        abort_if(!auth()->user()->can('unit_create'), 403);
        return view('backend.units.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        abort_if(!auth()->user()->can('unit_create'), 403);
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'short_name' => 'required|string|max:50',
          
        ]);
        Unit::create($validated);

        return redirect()->route('backend.admin.units.index')->with('success', 'Unit created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        abort_if(!auth()->user()->can('unit_update'), 403);

        $unit = Unit::findOrFail($id);
        return view('backend.units.edit', compact('unit'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        abort_if(!auth()->user()->can('unit_update'), 403);
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'short_name' => 'required|string|max:50',
          
        ]);
        $unit = Unit::findOrFail($id);
        $unit->update($validated);

        return redirect()->route('backend.admin.units.index')->with('success', 'Unit updated successfully!');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function toggleStatus($id)
    {
        abort_if(!auth()->user()->can('unit_update'), 403);
        $unit = Unit::findOrFail($id);
        $unit->status = $unit->status ? 0 : 1;
        $unit->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Unit status updated successfully!',
            'new_status' => $unit->status,
        ]);
    }
}
