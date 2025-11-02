<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Yajra\DataTables\DataTables;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        //abort_if(!auth()->user()->can('menu_view'), 403);
        if ($request->ajax()) {
            $menus = Menu::latest()->get();
            return DataTables::of($menus)
                ->addIndexColumn()
                ->addColumn('name', fn($data) => $data->name)
                ->addColumn('url', fn($data) => $data->url)
                ->addColumn('serial_no', fn($data) => $data->serial_no
                    . ($data->active ? ' (Default Menu)' : ''))
                ->addColumn('action', function ($data) {
                    return '<div class="btn-group">
                    <button type="button" class="btn bg-gradient-primary btn-flat">Action</button>
                    <button type="button" class="btn bg-gradient-primary btn-flat dropdown-toggle dropdown-icon" data-toggle="dropdown" aria-expanded="false">
                      <span class="sr-only">Toggle Dropdown</span>
                    </button>
                    <div class="dropdown-menu" role="menu">
                      <a class="dropdown-item" href="' . route('backend.admin.menus.edit', $data->id) . '" ' . ' >
                    <i class="fas fa-edit"></i> Edit
                </a> <div class="dropdown-divider"></div>
<form action="' . route('backend.admin.menus.destroy', $data->id) . '"method="POST" style="display:inline;">
                   ' . csrf_field() . '
                    ' . method_field("DELETE") . '
<button type="submit" class="dropdown-item" onclick="return confirm(\'Are you sure ?\')"><i class="fas fa-trash"></i> Delete</button>
                  </form><div class="dropdown-divider"></div>
                   
                  </div>';
                })
                ->rawColumns(['name', 'url', 'serial_no', 'action'])
                ->toJson();
        }
        return view('backend.menus.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        //abort_if(!auth()->user()->can('_create'), 403);
        return view('backend.menus.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        //abort_if(!auth()->user()->can('menu_create'), 403);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'url' => 'required|string|unique:menus,url',
            'serial_no' => 'required|string'
        ]);
        $menu = Menu::create($request->only(['name', 'url', 'serial_no']));

        return redirect()->route('backend.admin.menus.index')->with('success', 'Menu created successfully!');
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

        //abort_if(!auth()->user()->can('menu_update'), 403);

        $menu = Menu::findOrFail($id);
        return view('backend.menus.edit', compact('menu'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {

        //abort_if(!auth()->user()->can('menu_update'), 403);
        $menu = Menu::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'url' => 'required|string|unique:menus,url,' . $menu->id,
            'serial_no' => 'required|string'
        ]);
        $menu->update($request->only(['name', 'url', 'serial_no']));
        return redirect()->route('backend.admin.menus.index')->with('success', 'Menu updated successfully!');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {

        //abort_if(!auth()->user()->can('menu_delete'), 403);
        $menu = Menu::findOrFail($id);
        $menu->delete();
        return redirect()->back()->with('success', 'Menu Deleted Successfully');
    }
    public function setDefault($id)
    {
        Menu::where('active', true)->update(['active' => false]);
        $menu = Menu::findOrFail($id);
        $menu->active = true;
        $menu->save();
        Cache::put('default_menu', $menu, 60 * 24);
        return redirect()->back()->with('success', 'Menu Set Default Successfully');
    }
}
