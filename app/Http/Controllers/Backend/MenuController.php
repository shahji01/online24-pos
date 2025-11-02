<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Yajra\DataTables\DataTables;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $menus = Menu::latest()->get();

            return DataTables::of($menus)
                ->addIndexColumn()
                ->addColumn('status', fn($data) =>
                    $data->active
                        ? '<span class="badge bg-success">Active</span>'
                        : '<span class="badge bg-danger">Inactive</span>'
                )
              ->addColumn('action', function ($data) {
    $toggleUrl = route('backend.admin.menus.toggle', $data->id);
    $editUrl = route('backend.admin.menus.edit', $data->id);

    return '<div class="btn-group">
        <button type="button" class="btn bg-gradient-primary btn-flat">Action</button>
        <button type="button" class="btn bg-gradient-primary btn-flat dropdown-toggle dropdown-icon" data-bs-toggle="dropdown" aria-expanded="false">
            <span class="sr-only">Toggle Dropdown</span>
        </button>
        <div class="dropdown-menu" role="menu">
            <a class="dropdown-item" href="'.$editUrl.'">
                <i class="fas fa-edit"></i> Edit
            </a>
            <div class="dropdown-divider"></div>
            <button type="button" class="dropdown-item toggle-status" data-url="'.$toggleUrl.'">
                <i class="fas fa-exchange-alt"></i> Toggle Status
            </button>
        </div>
    </div>';
})

                ->rawColumns(['status', 'action'])
                ->toJson();
        }

        return view('backend.menus.index');
    }

    public function create()
    {
        return view('backend.menus.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'url' => 'required|string|unique:menus,url',
            'serial_no' => 'required|numeric',
        ]);

        Menu::create($validated);

        return redirect()->route('backend.admin.menus.index')
            ->with('success', 'Menu created successfully!');
    }

    public function edit($id)
    {
        $menu = Menu::findOrFail($id);
        return view('backend.menus.edit', compact('menu'));
    }

    public function update(Request $request, $id)
    {
        $menu = Menu::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'url' => 'required|string|unique:menus,url,' . $menu->id,
            'serial_no' => 'required|numeric',
        ]);

        $menu->update($validated);

        return redirect()->route('backend.admin.menus.index')
            ->with('success', 'Menu updated successfully!');
    }

    // Toggle Active/Inactive
    public function toggleStatus($id)
    {
        $menu = Menu::findOrFail($id);
        $menu->active = !$menu->active;
        $menu->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Menu status updated successfully!'
        ]);
    }

    public function setDefault($id)
    {
        Menu::where('default', true)->update(['default' => false]);
        $menu = Menu::findOrFail($id);
        $menu->default = true;
        $menu->save();
        Cache::put('default_menu', $menu, 60 * 24);
        return redirect()->back()->with('success', 'Menu set as default successfully!');
    }
}
